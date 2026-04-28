<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Sparepart;
use App\Models\PosTransaction;
use App\Models\PosItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PosController extends Controller
{
    public function index(Request $request)
    {
        // Bookings yang siap di-checkout (Selesai tapi belum ada transaksi)
        $readyBookings = Booking::with('user')
            ->where('status', 'Selesai')
            ->whereDoesntHave('transaction')
            ->orderBy('tanggal', 'desc')
            ->get();

        // Juga tampilkan booking Sedang Dikerjakan untuk bisa langsung selesai + POS
        $activeBookings = Booking::with('user')
            ->where('status', 'Sedang Dikerjakan')
            ->orderBy('tanggal', 'desc')
            ->get();

        $allBookings = $readyBookings->merge($activeBookings);

        // Selected booking
        $selectedBooking = null;
        if ($request->filled('booking_id')) {
            $selectedBooking = Booking::with('user')->find($request->booking_id);
        }

        // Spareparts for catalog (tampilkan semua, termasuk yang habis)
        $spareparts = Sparepart::orderBy('nama')->get();

        return view('admin.pos.index', compact('allBookings', 'selectedBooking', 'spareparts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'nullable|exists:bookings,id',
            'customer_name' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.item_type' => 'required|in:jasa,sparepart',
            'items.*.item_id' => 'nullable|integer',
            'items.*.item_name' => 'required|string',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ], [
            'booking_id.exists'           => 'Booking yang dipilih tidak ditemukan.',
            'customer_name.max'           => 'Nama pelanggan tidak boleh lebih dari 255 karakter.',
            'items.required'              => 'Tambahkan minimal satu item ke keranjang.',
            'items.min'                   => 'Tambahkan minimal satu item ke keranjang.',
            'items.*.item_type.required'  => 'Tipe item wajib diisi.',
            'items.*.item_type.in'        => 'Tipe item tidak valid.',
            'items.*.item_name.required'  => 'Nama item wajib diisi.',
            'items.*.qty.required'        => 'Jumlah item wajib diisi.',
            'items.*.qty.integer'         => 'Jumlah item harus berupa bilangan bulat.',
            'items.*.qty.min'             => 'Jumlah item minimal adalah 1.',
            'items.*.price.required'      => 'Harga item wajib diisi.',
            'items.*.price.numeric'       => 'Harga item harus berupa angka.',
            'items.*.price.min'           => 'Harga item tidak boleh kurang dari 0.',
        ]);

        // Validasi stok sparepart sebelum proses DB transaction
        foreach ($request->items as $item) {
            if ($item['item_type'] === 'sparepart' && $item['item_id']) {
                $sparepart = Sparepart::find($item['item_id']);
                if (!$sparepart || $sparepart->stok < $item['qty']) {
                    $sisa = $sparepart ? $sparepart->stok : 0;
                    return response()->json([
                        'success' => false,
                        'message' => "Stok untuk {$item['item_name']} tidak mencukupi (Sisa: {$sisa})"
                    ], 400);
                }
            }
        }

        return DB::transaction(function () use ($request) {
            $subtotal = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $itemSubtotal = $item['qty'] * $item['price'];
                $subtotal += $itemSubtotal;

                $itemsData[] = array_merge($item, ['subtotal' => $itemSubtotal]);

                // Potong stok sparepart
                if ($item['item_type'] === 'sparepart' && $item['item_id']) {
                    $sparepart = Sparepart::find($item['item_id']);
                    if ($sparepart) {
                        $sparepart->decrement('stok', $item['qty']);
                    }
                }
            }

            $taxAmount = (int) round($subtotal * 0.11);
            $total = $subtotal + $taxAmount;

            // Create transaction
            $transaction = PosTransaction::create([
                'booking_id' => $request->booking_id ?: null,
                'customer_name' => $request->customer_name ?: null,
                'user_id' => Auth::id(),
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total' => $total,
                'status_pembayaran' => 'Lunas',
            ]);

            // Create items
            foreach ($itemsData as $item) {
                PosItem::create([
                    'pos_transaction_id' => $transaction->id,
                    'item_type' => $item['item_type'],
                    'item_id' => $item['item_id'] ?? null,
                    'item_name' => $item['item_name'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            // Update booking status only if booking exists
            if ($request->booking_id) {
                $booking = Booking::find($request->booking_id);
                if ($booking) {
                    $booking->update(['status' => 'Selesai']);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil diproses!',
                'transaction_id' => $transaction->id,
                'total' => $total,
            ]);
        });
    }

    /**
     * Generate and download invoice PDF
     */
    public function invoice($transactionId)
    {
        $transaction = PosTransaction::with(['booking.user', 'items'])->findOrFail($transactionId);

        $pdf = Pdf::loadView('admin.pos.invoice-pdf', compact('transaction'));
        // 58mm is approximately 164.4 points.
        $pdf->setPaper([0, 0, 164.4, 500], 'portrait');

        $filename = $transaction->booking
            ? 'Invoice-' . $transaction->booking->kode_booking . '.pdf'
            : 'Invoice-INV' . str_pad($transaction->id, 5, '0', STR_PAD_LEFT) . '.pdf';

        // Mengubah output PDF menjadi base64 string
        $pdfBase64 = base64_encode($pdf->output());

        // Return HTML view yang membungkus PDF base64
        return view('admin.pos.invoice-preview', compact('pdfBase64', 'filename'));
    }

    /**
     * Generate WhatsApp URL with invoice message
     */
    public function whatsapp($transactionId)
    {
        $transaction = PosTransaction::with(['booking.user', 'items'])->findOrFail($transactionId);

        $phone = '';
        $customerName = $transaction->customer_name ?: 'Walk-in Customer';

        if ($transaction->booking && $transaction->booking->user) {
            $customer = $transaction->booking->user;
            $phone = $customer->phone ?? '';
            $customerName = $customer->name;
        }

        // Format phone number for wa.me (remove leading 0, add 62)
        $phone = preg_replace('/^0/', '62', $phone);
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Build message
        $message = "🧾 *NOTA VESPABOX*\n";
        $message .= "━━━━━━━━━━━━━━━━━━\n\n";

        if ($transaction->booking) {
            $message .= "📋 *Kode Booking:* {$transaction->booking->kode_booking}\n";
            $message .= "🏍 *Kendaraan:* {$transaction->booking->kendaraan}\n";
            $message .= "🔢 *Plat Nomor:* {$transaction->booking->plat_nomor}\n";
        }

        $message .= "👤 *Pelanggan:* {$customerName}\n";
        $message .= "📅 *Tanggal:* {$transaction->created_at->format('d M Y, H:i')} WIB\n\n";
        $message .= "━━━━━━━━━━━━━━━━━━\n";
        $message .= "📝 *RINCIAN TAGIHAN:*\n\n";

        foreach ($transaction->items as $i => $item) {
            $subtotalFormatted = number_format($item->subtotal, 0, ',', '.');
            $message .= ($i + 1) . ". {$item->item_name}\n";
            $message .= "   {$item->qty} x Rp " . number_format($item->price, 0, ',', '.') . " = Rp {$subtotalFormatted}\n";
        }

        $message .= "\n━━━━━━━━━━━━━━━━━━\n";
        $message .= "Subtotal: Rp " . number_format($transaction->subtotal, 0, ',', '.') . "\n";
        $message .= "Pajak (11%): Rp " . number_format($transaction->tax_amount, 0, ',', '.') . "\n";
        $message .= "*TOTAL: Rp " . number_format($transaction->total, 0, ',', '.') . "*\n\n";
        $message .= "✅ Status: *{$transaction->status_pembayaran}*\n\n";
        $message .= "Terima kasih atas kepercayaan Anda! 🙏\n";
        $message .= "_VespaBox_";

        // If no phone, open WA without pre-filled number
        $waUrl = $phone
            ? "https://wa.me/{$phone}?text=" . urlencode($message)
            : "https://wa.me/?text=" . urlencode($message);

        return response()->json([
            'url' => $waUrl,
            'phone' => $phone,
        ]);
    }
}

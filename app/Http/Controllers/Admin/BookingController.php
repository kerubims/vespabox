<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $bookings = Booking::with('user')
            ->whereDate('tanggal', $today)
            ->whereNotIn('status', ['Selesai', 'Dibatalkan'])
            ->orderBy('jam')
            ->get();

        $counts = [
            'menunggu' => $bookings->where('status', 'Menunggu')->count(),
            'dikonfirmasi' => $bookings->where('status', 'Dikonfirmasi')->count(),
            'dikerjakan' => $bookings->where('status', 'Sedang Dikerjakan')->count(),
        ];

        return view('admin.booking.index', compact('bookings', 'counts'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Dikonfirmasi,Sedang Dikerjakan,Selesai,Dibatalkan',
            'cancel_reason' => 'required_if:status,Dibatalkan|nullable|string|max:500',
        ], [
            'status.required' => 'Status wajib dipilih.',
            'status.in'       => 'Status yang dipilih tidak valid.',
            'cancel_reason.required_if' => 'Alasan pembatalan wajib diisi.',
            'cancel_reason.max' => 'Alasan tidak boleh lebih dari 500 karakter.',
        ]);

        $booking = Booking::findOrFail($id);
        
        $updateData = ['status' => $request->status];
        if ($request->status === 'Dibatalkan') {
            $updateData['cancel_reason'] = $request->cancel_reason;
        } else {
            $updateData['cancel_reason'] = null; // Clear if status changed back (though unlikely)
        }

        $booking->update($updateData);

        if ($booking->user) {
            $booking->user->notify(new \App\Notifications\BookingStatusUpdatedNotification($booking));
        }

        \App\Events\QueueUpdated::dispatch('Status antrean ' . $booking->kode_booking . ' diubah');

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Status berhasil diubah']);
        }

        return back()->with('success', 'Status booking berhasil diubah');
    }

    public function riwayat(Request $request)
    {
        $query = Booking::with('user', 'transaction.items')
            ->whereIn('status', ['Selesai', 'Dibatalkan'])
            ->orderBy('tanggal', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('plat_nomor', 'like', "%{$search}%")
                  ->orWhere('kode_booking', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $bookings = $query->paginate(10);

        return view('admin.booking.riwayat', compact('bookings'));
    }
}

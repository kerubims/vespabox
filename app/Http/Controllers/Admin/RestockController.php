<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sparepart;
use App\Models\Restock;
use App\Models\RestockItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RestockController extends Controller
{
    public function index()
    {
        $spareparts = Sparepart::orderBy('nama')->get();
        $restockHistory = Restock::with(['user', 'items.sparepart'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.restock.index', compact('spareparts', 'restockHistory'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.sparepart_id' => 'required|exists:spareparts,id',
            'items.*.qty' => 'required|integer|min:1',
            'catatan' => 'nullable|string|max:500',
        ], [
            'items.required'               => 'Tambahkan minimal satu item restock.',
            'items.min'                    => 'Tambahkan minimal satu item restock.',
            'items.*.sparepart_id.required' => 'Sparepart wajib dipilih.',
            'items.*.sparepart_id.exists'  => 'Sparepart yang dipilih tidak ditemukan.',
            'items.*.qty.required'         => 'Jumlah wajib diisi.',
            'items.*.qty.integer'          => 'Jumlah harus berupa bilangan bulat.',
            'items.*.qty.min'              => 'Jumlah minimal adalah 1.',
            'catatan.max'                  => 'Catatan tidak boleh lebih dari 500 karakter.',
        ]);

        try {
            DB::beginTransaction();

            $kodeRestock = 'RST-' . date('Ymd') . '-' . str_pad(Restock::whereDate('created_at', today())->count() + 1, 3, '0', STR_PAD_LEFT);

            $restock = Restock::create([
                'kode_restock' => $kodeRestock,
                'user_id' => auth()->id(),
                'catatan' => $request->catatan,
                'total_item' => count($request->items),
                'total_qty' => collect($request->items)->sum('qty'),
            ]);

            foreach ($request->items as $item) {
                RestockItem::create([
                    'restock_id' => $restock->id,
                    'sparepart_id' => $item['sparepart_id'],
                    'qty' => $item['qty'],
                    'harga_beli' => $item['harga_beli'] ?? 0,
                ]);

                // Update sparepart stock
                $sparepart = Sparepart::find($item['sparepart_id']);
                $sparepart->increment('stok', $item['qty']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Restock berhasil diproses!',
                'kode_restock' => $kodeRestock,
                'total_item' => $restock->total_item,
                'total_qty' => $restock->total_qty,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses restock: ' . $e->getMessage(),
            ], 500);
        }
    }
}

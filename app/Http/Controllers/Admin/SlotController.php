<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slot;

class SlotController extends Controller
{
    public function index()
    {
        $slots = Slot::orderBy('jam')->get()->groupBy('hari');

        return view('admin.slot.index', compact('slots'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'slots' => 'required|array',
            'slots.*.id' => 'required|exists:slots,id',
            'slots.*.kapasitas' => 'required|integer|min:0',
            'slots.*.is_active' => 'required|boolean',
        ], [
            'slots.required'              => 'Data slot wajib diisi.',
            'slots.array'                 => 'Format data slot tidak valid.',
            'slots.*.id.required'         => 'ID slot wajib diisi.',
            'slots.*.id.exists'           => 'Slot yang dipilih tidak ditemukan.',
            'slots.*.kapasitas.required'  => 'Kapasitas slot wajib diisi.',
            'slots.*.kapasitas.integer'   => 'Kapasitas harus berupa bilangan bulat.',
            'slots.*.kapasitas.min'       => 'Kapasitas tidak boleh kurang dari 0.',
            'slots.*.is_active.required'  => 'Status aktif slot wajib diisi.',
        ]);

        foreach ($request->slots as $slotData) {
            Slot::where('id', $slotData['id'])->update([
                'kapasitas' => $slotData['kapasitas'],
                'is_active' => $slotData['is_active'],
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Slot berhasil diperbarui']);
        }

        return back()->with('success', 'Slot berhasil diperbarui');
    }
}

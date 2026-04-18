<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sparepart;
use Illuminate\Support\Facades\Storage;

class SparepartController extends Controller
{
    public function index(Request $request)
    {
        $query = Sparepart::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        if ($request->filled('filter')) {
            if ($request->filter === 'low_stock') {
                $query->whereColumn('stok', '<', 'stok_minimum');
            } elseif ($request->filter === 'out_of_stock') {
                $query->where('stok', 0);
            }
        }

        $spareparts = $query->orderBy('nama')->paginate(10);

        return view('admin.sparepart.index', compact('spareparts'));
    }

    public function create()
    {
        return view('admin.sparepart.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|unique:spareparts,kode',
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string',
            'harga_beli' => 'nullable|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('spareparts', 'public');
        }

        Sparepart::create($validated);

        return redirect()->route('admin.sparepart.index')
            ->with('success', 'Sparepart berhasil ditambahkan');
    }

    public function edit($id)
    {
        $sparepart = Sparepart::findOrFail($id);
        return view('admin.sparepart.edit', compact('sparepart'));
    }

    public function update(Request $request, $id)
    {
        $sparepart = Sparepart::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string',
            'harga_beli' => 'nullable|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($sparepart->image) {
                Storage::disk('public')->delete($sparepart->image);
            }
            $validated['image'] = $request->file('image')->store('spareparts', 'public');
        } else {
            unset($validated['image']);
        }

        $sparepart->update($validated);

        return redirect()->route('admin.sparepart.index')
            ->with('success', 'Sparepart berhasil diperbarui');
    }

    public function destroy($id)
    {
        $sparepart = Sparepart::findOrFail($id);

        if ($sparepart->image) {
            Storage::disk('public')->delete($sparepart->image);
        }

        $sparepart->delete();

        return redirect()->route('admin.sparepart.index')
            ->with('success', 'Sparepart berhasil dihapus');
    }
}

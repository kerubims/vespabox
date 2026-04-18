@extends('layouts.dashboard')
@section('title', 'Edit Sparepart')
@section('page_title', 'Edit Sparepart')
@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')
<div class="max-w-4xl mx-auto animate-slide-up">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-surface flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.sparepart.index') }}" class="p-1.5 rounded-lg text-gray-400 hover:text-primary hover:bg-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h2 class="text-lg font-bold text-primary">Edit Sparepart</h2>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $sparepart->kode }} - {{ $sparepart->nama }}</p>
                </div>
            </div>
            @if($sparepart->stok > 0)
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-success/10 text-success border border-success/20">Aktif</span>
            @else
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-danger/10 text-danger border border-danger/20">Habis</span>
            @endif
        </div>

        @if($errors->any())
        <div class="mx-6 mt-4 p-4 bg-danger/10 border border-danger/20 text-danger rounded-xl text-sm">
            <ul class="list-disc pl-4 space-y-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('admin.sparepart.update', $sparepart->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-8">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="md:col-span-1 space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Foto Sparepart</label>
                    <div class="w-full aspect-square bg-gray-50 border-2 border-dashed border-gray-300 rounded-2xl flex flex-col items-center justify-center relative overflow-hidden group hover:border-accent transition-colors cursor-pointer">
                        @if($sparepart->image)
                        <img id="preview-image" src="{{ asset('storage/' . $sparepart->image) }}" class="absolute inset-0 w-full h-full object-cover" alt="{{ $sparepart->nama }}">
                        <div id="upload-placeholder" class="text-center p-4 hidden">
                        @else
                        <img id="preview-image" class="absolute inset-0 w-full h-full object-cover hidden" alt="Preview">
                        <div id="upload-placeholder" class="text-center p-4">
                        @endif
                            <svg class="w-10 h-10 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-xs text-gray-500 font-medium">Klik untuk upload foto</p>
                        </div>
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white">
                            <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            <span class="text-xs font-medium">Ubah Foto</span>
                        </div>
                        <input type="file" name="image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="if(this.files[0]){const r=new FileReader();r.onload=e=>{document.getElementById('preview-image').src=e.target.result;document.getElementById('preview-image').classList.remove('hidden');document.getElementById('upload-placeholder').classList.add('hidden')};r.readAsDataURL(this.files[0])}">
                    </div>
                </div>
                <div class="md:col-span-2 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Sparepart</label>
                            <input type="text" value="{{ $sparepart->kode }}" class="w-full px-4 py-2 border border-gray-200 rounded-xl bg-gray-50 text-gray-500 cursor-not-allowed text-sm uppercase" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori" required class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent text-sm bg-white">
                                @foreach(['Mesin','Kelistrikan','Pengereman','CVT','Filter','Pengapian','Oli & Cairan','Suspensi','Aksesoris'] as $kat)
                                <option value="{{ $kat }}" {{ $sparepart->kategori==$kat?'selected':'' }}>{{ $kat }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Sparepart <span class="text-danger">*</span></label>
                        <input type="text" name="nama" required value="{{ old('nama', $sparepart->nama) }}" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent text-sm">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga Beli</label>
                            <div class="relative"><div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-500 font-medium">Rp</div>
                            <input type="number" name="harga_beli" value="{{ old('harga_beli', $sparepart->harga_beli) }}" class="w-full pl-11 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent text-sm"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga Jual <span class="text-danger">*</span></label>
                            <div class="relative"><div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-500 font-medium">Rp</div>
                            <input type="number" name="harga_jual" required value="{{ old('harga_jual', $sparepart->harga_jual) }}" class="w-full pl-11 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent text-sm"></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stok Saat Ini <span class="text-danger">*</span></label>
                            <input type="number" name="stok" required value="{{ old('stok', $sparepart->stok) }}" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Batas Minimum Stok <span class="text-danger">*</span></label>
                            <input type="number" name="stok_minimum" required value="{{ old('stok_minimum', $sparepart->stok_minimum) }}" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent text-sm">
                        </div>
                    </div>
                </div>
            </div>
            <div class="pt-6 border-t border-gray-100 flex justify-between items-center">
                <button type="button" onclick="if(confirm('Hapus item ini?')) document.getElementById('delete-form-{{ $sparepart->id }}').submit()" class="px-4 py-2.5 text-danger font-medium hover:bg-danger/5 rounded-xl transition-colors text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Hapus Item
                </button>
                <div class="flex gap-3">
                    <a href="{{ route('admin.sparepart.index') }}" class="px-6 py-2.5 rounded-xl border border-gray-200 text-gray-600 font-medium hover:bg-gray-50 text-sm">Batal</a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-accent text-white font-semibold hover:bg-accent-hover shadow-md shadow-accent/20 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>

        <form id="delete-form-{{ $sparepart->id }}" action="{{ route('admin.sparepart.destroy', $sparepart->id) }}" method="POST" class="hidden">
            @csrf @method('DELETE')
        </form>
    </div>
</div>
@endsection

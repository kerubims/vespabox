@extends('layouts.dashboard')
@section('title', 'Tambah Sparepart')
@section('page_title', 'Tambah Sparepart')
@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')
<div class="max-w-4xl mx-auto animate-slide-up">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-surface flex items-center gap-3">
            <a href="{{ route('admin.sparepart.index') }}" class="p-1.5 rounded-lg text-gray-400 hover:text-primary hover:bg-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h2 class="text-lg font-bold text-primary">Form Tambah Sparepart</h2>
                <p class="text-sm text-gray-500 mt-0.5">Lengkapi form di bawah untuk menambahkan data ke katalog.</p>
            </div>
        </div>

        @if($errors->any())
        <div class="mx-6 mt-4 p-4 bg-danger/10 border border-danger/20 text-danger rounded-xl text-sm">
            <ul class="list-disc pl-4 space-y-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('admin.sparepart.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="md:col-span-1 space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Foto Sparepart</label>
                    <div class="w-full aspect-square bg-gray-50 border-2 border-dashed border-gray-300 rounded-2xl flex flex-col items-center justify-center relative overflow-hidden group hover:border-accent transition-colors cursor-pointer">
                        <div class="text-center p-4" id="upload-placeholder">
                            <svg class="w-10 h-10 text-gray-400 mx-auto mb-2 group-hover:text-accent transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-xs text-gray-500 font-medium">Klik untuk upload foto</p>
                            <p class="text-[10px] text-gray-400 mt-1">PNG, JPG (Max. 2MB)</p>
                        </div>
                        <img id="preview-image" class="absolute inset-0 w-full h-full object-cover hidden" alt="Preview">
                        <input type="file" name="image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="if(this.files[0]){const r=new FileReader();r.onload=e=>{document.getElementById('preview-image').src=e.target.result;document.getElementById('preview-image').classList.remove('hidden');document.getElementById('upload-placeholder').classList.add('hidden')};r.readAsDataURL(this.files[0])}">
                    </div>
                </div>
                <div class="md:col-span-2 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Sparepart <span class="text-danger">*</span></label>
                            <input type="text" name="kode" required value="{{ old('kode') }}" placeholder="Contoh: SP-009" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm uppercase">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori" required class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm bg-white">
                                <option value="">Pilih Kategori...</option>
                                @foreach(['Mesin','Kelistrikan','Pengereman','CVT','Filter','Pengapian','Oli & Cairan','Suspensi','Aksesoris'] as $kat)
                                <option value="{{ $kat }}" {{ old('kategori')==$kat?'selected':'' }}>{{ $kat }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Sparepart <span class="text-danger">*</span></label>
                        <input type="text" name="nama" required value="{{ old('nama') }}" placeholder="Contoh: Kampas Rem Depan" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga Beli</label>
                            <div class="relative"><div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-500 font-medium">Rp</div>
                            <input type="number" name="harga_beli" value="{{ old('harga_beli') }}" placeholder="0" class="w-full pl-11 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga Jual <span class="text-danger">*</span></label>
                            <div class="relative"><div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-500 font-medium">Rp</div>
                            <input type="number" name="harga_jual" required value="{{ old('harga_jual') }}" placeholder="0" class="w-full pl-11 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm"></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stok Awal <span class="text-danger">*</span></label>
                            <input type="number" name="stok" required value="{{ old('stok',0) }}" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Batas Minimum Stok <span class="text-danger">*</span></label>
                            <input type="number" name="stok_minimum" required value="{{ old('stok_minimum',5) }}" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm">
                        </div>
                    </div>
                </div>
            </div>
            <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('admin.sparepart.index') }}" class="px-6 py-2.5 rounded-xl border border-gray-200 text-gray-600 font-medium hover:bg-gray-50 transition-all text-sm">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-accent text-white font-semibold hover:bg-accent-hover shadow-md shadow-accent/20 transition-all text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

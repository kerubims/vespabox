@extends('layouts.dashboard')
@section('title', 'Manajemen Sparepart')
@section('page_title', 'Data Sparepart')
@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')
<div class="space-y-6 animate-fade-in">
    
    {{-- Success Message --}}
    @if(session('success'))
    <div class="bg-success/10 border border-success/20 text-success px-4 py-3 rounded-xl text-sm font-medium" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
        {{ session('success') }}
    </div>
    @endif

    {{-- Header Actions --}}
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <form action="{{ route('admin.sparepart.index') }}" method="GET" class="flex-1 w-full relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau kode sparepart..." class="w-full sm:max-w-md pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm">
        </form>
        
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <a href="{{ route('admin.sparepart.index', ['filter' => 'low_stock']) }}" class="px-4 py-2 {{ request('filter') === 'low_stock' ? 'bg-warning/10 text-warning border-warning/30' : 'bg-white border-gray-200 text-gray-700' }} border rounded-xl hover:bg-gray-50 flex items-center gap-2 text-sm font-medium transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                Stok Menipis
            </a>
            <a href="{{ route('admin.sparepart.create') }}" class="px-4 py-2 bg-accent text-white rounded-xl hover:bg-accent-hover flex items-center gap-2 text-sm font-medium shadow-md shadow-accent/20 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Tambah Baru
            </a>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-medium">Item</th>
                        <th class="px-6 py-4 font-medium">Kategori</th>
                        <th class="px-6 py-4 font-medium">Harga Jual</th>
                        <th class="px-6 py-4 font-medium">Stok</th>
                        <th class="px-6 py-4 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($spareparts as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center shrink-0 overflow-hidden">
                                        @if($item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->nama }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-800">{{ $item->nama }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">{{ $item->kode }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $item->kategori }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-800">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                @if($item->stok == 0)
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-danger/10 text-danger border border-danger/20">Habis (0)</span>
                                @elseif($item->stok < $item->stok_minimum)
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-warning/10 text-warning border border-warning/20">Menipis ({{ $item->stok }})</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-success/10 text-success border border-success/20">Aman ({{ $item->stok }})</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.sparepart.edit', $item->id) }}" class="p-2 text-gray-400 hover:text-accent bg-white rounded-lg border border-gray-100 shadow-sm transition-all" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.sparepart.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus sparepart {{ $item->nama }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-danger bg-white rounded-lg border border-gray-100 shadow-sm transition-all" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">Belum ada data sparepart</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($spareparts->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $spareparts->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

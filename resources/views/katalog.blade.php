@extends('layouts.app')

@section('title', 'Katalog Sparepart')
@section('meta_description', 'Katalog sparepart original VespaBox dengan harga transparan dan stok ter-update.')

@section('content')
<div class="bg-gradient-to-b from-primary/5 to-transparent py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header & Search --}}
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-10 animate-fade-in">
            <div>
                <h1 class="text-3xl font-bold text-primary">Katalog Sparepart</h1>
                <p class="text-gray-500 mt-2">Cari sparepart yang Anda butuhkan untuk Vespa kesayangan Anda.</p>
            </div>
            <form action="{{ route('katalog') }}" method="GET" class="w-full md:w-96 flex gap-2">
                @if(request('kategori'))
                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                @endif
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama sparepart..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent transition-all">
                </div>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-xl hover:bg-primary-light transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Cari
                </button>
            </form>
        </div>

        {{-- Categories --}}
        <div class="flex flex-nowrap overflow-x-auto no-scrollbar gap-2 mb-8 animate-fade-in pb-2" style="animation-delay: 0.1s">
            <a href="{{ route('katalog', ['search' => request('search')]) }}" class="shrink-0 px-4 py-2 rounded-full text-sm font-medium {{ !request('kategori') ? 'bg-accent text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:border-accent hover:text-accent transition-all' }}">Semua</a>
            @foreach($categories as $cat)
                <a href="{{ route('katalog', ['kategori' => $cat, 'search' => request('search')]) }}" class="shrink-0 px-4 py-2 rounded-full text-sm font-medium {{ request('kategori') == $cat ? 'bg-accent text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:border-accent hover:text-accent transition-all' }}">{{ $cat }}</a>
            @endforeach
        </div>

        {{-- Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($spareparts as $i => $item)
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col animate-slide-up" style="animation-delay: {{ $i * 0.05 }}s">
                    <div class="h-48 bg-gray-50 flex items-center justify-center p-6 border-b border-gray-100 relative group overflow-hidden">
                        <div class="absolute inset-0 bg-primary/5 opacity-0 group-hover:opacity-100 transition-opacity z-10"></div>
                        @if($item->image)
                            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->nama }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <svg class="w-24 h-24 text-gray-300 group-hover:scale-110 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        @endif
                        
                        @if($item->stok == 0)
                            <div class="absolute top-3 right-3 px-2 py-1 bg-danger/10 text-danger text-xs font-bold rounded-lg backdrop-blur-sm">Habis</div>
                        @elseif($item->stok < 5)
                            <div class="absolute top-3 right-3 px-2 py-1 bg-warning/10 text-warning text-xs font-bold rounded-lg backdrop-blur-sm">Sisa {{ $item->stok }}</div>
                        @else
                            <div class="absolute top-3 right-3 px-2 py-1 bg-success/10 text-success text-xs font-bold rounded-lg backdrop-blur-sm">Stok Tersedia</div>
                        @endif
                    </div>
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="text-xs font-medium text-accent mb-1">{{ $item->kategori }}</div>
                        <h3 class="text-sm font-bold text-primary mb-1 line-clamp-2">{{ $item->nama }}</h3>
                        <!-- <p class="text-xs text-gray-400 mb-4">{{ $item->kode }}</p> -->
                        
                        <div class="mt-auto flex items-end justify-between">
                            <div>
                                <!-- <div class="text-xs text-gray-500 mb-0.5">Estimasi Harga</div> -->
                                <div class="text-lg font-bold text-gray-800">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-1 sm:col-span-2 lg:col-span-4 text-center py-12">
                    <p class="text-gray-500">Tidak ada sparepart yang ditemukan.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination Placeholder --}}
        <div class="mt-12 flex justify-center animate-fade-in">
            {{ $spareparts->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

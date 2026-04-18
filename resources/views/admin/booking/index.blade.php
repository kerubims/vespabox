@extends('layouts.dashboard')
@section('title', 'Manajemen Booking')
@section('page_title', 'Booking Aktif')
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

    {{-- Tabs & Filters --}}
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="flex bg-gray-100 p-1 rounded-xl w-full sm:w-auto">
            <button class="flex-1 sm:flex-none px-6 py-2 text-sm font-medium rounded-lg bg-white text-primary shadow-sm">Aktif</button>
            <a href="{{ route('admin.booking.riwayat') }}" class="flex-1 sm:flex-none px-6 py-2 text-sm font-medium rounded-lg text-gray-500 hover:text-primary transition-colors text-center">Riwayat</a>
        </div>
    </div>

    {{-- Kanban / Grid View for Active Bookings --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
        
        {{-- Menunggu --}}
        <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
            <div class="flex items-center justify-between mb-4 px-2">
                <h3 class="font-bold text-gray-700 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-warning"></span>
                    Menunggu
                </h3>
                <span class="px-2 py-0.5 rounded-md bg-gray-200 text-xs font-bold text-gray-600">{{ $counts['menunggu'] }}</span>
            </div>
            <div class="space-y-3">
                @foreach($bookings->where('status', 'Menunggu') as $item)
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-xs font-bold text-gray-400">{{ $item->kode_booking }}</span>
                            <span class="text-xs font-medium text-gray-500">{{ \Carbon\Carbon::parse($item->jam)->format('H:i') }} WIB</span>
                        </div>
                        <h4 class="font-bold text-primary text-lg uppercase">{{ $item->plat_nomor }}</h4>
                        <p class="text-xs text-gray-500 mb-1">{{ $item->kendaraan }}</p>
                        <p class="text-xs text-gray-400 mb-3 line-clamp-1">{{ $item->keluhan }}</p>
                        <div class="flex gap-2">
                            <form action="{{ route('admin.booking.updateStatus', $item->id) }}" method="POST" class="flex-1">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="Dikonfirmasi">
                                <button type="submit" class="w-full py-1.5 bg-accent text-white hover:bg-accent/90 rounded-lg text-xs font-semibold shadow-sm transition-colors">Konfirmasi</button>
                            </form>
                            <form action="{{ route('admin.booking.updateStatus', $item->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="Dibatalkan">
                                <button type="submit" class="py-1.5 px-3 bg-white border border-gray-200 text-gray-500 hover:text-danger hover:border-danger rounded-lg text-xs font-semibold transition-colors" onclick="return confirm('Batalkan booking ini?')">✕</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Dikonfirmasi --}}
        <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
            <div class="flex items-center justify-between mb-4 px-2">
                <h3 class="font-bold text-gray-700 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-info"></span>
                    Dikonfirmasi
                </h3>
                <span class="px-2 py-0.5 rounded-md bg-gray-200 text-xs font-bold text-gray-600">{{ $counts['dikonfirmasi'] }}</span>
            </div>
            <div class="space-y-3">
                @foreach($bookings->where('status', 'Dikonfirmasi') as $item)
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-info/30 hover:shadow-md transition-all">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-xs font-bold text-info">{{ $item->kode_booking }}</span>
                            <span class="text-xs font-medium text-gray-500">{{ \Carbon\Carbon::parse($item->jam)->format('H:i') }} WIB</span>
                        </div>
                        <h4 class="font-bold text-primary text-lg uppercase">{{ $item->plat_nomor }}</h4>
                        <p class="text-xs text-gray-500 mb-1">{{ $item->kendaraan }}</p>
                        <p class="text-xs text-gray-400 mb-3 line-clamp-1">{{ $item->keluhan }}</p>
                        <div class="flex gap-2">
                            <form action="{{ route('admin.booking.updateStatus', $item->id) }}" method="POST" class="flex-1">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="Sedang Dikerjakan">
                                <button type="submit" class="w-full py-1.5 bg-info text-white hover:bg-info/90 rounded-lg text-xs font-semibold shadow-sm transition-colors">Kerjakan</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Sedang Dikerjakan --}}
        <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
            <div class="flex items-center justify-between mb-4 px-2">
                <h3 class="font-bold text-gray-700 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-accent animate-pulse"></span>
                    Dikerjakan
                </h3>
                <span class="px-2 py-0.5 rounded-md bg-gray-200 text-xs font-bold text-gray-600">{{ $counts['dikerjakan'] }}</span>
            </div>
            <div class="space-y-3">
                @foreach($bookings->where('status', 'Sedang Dikerjakan') as $item)
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-accent/30 hover:shadow-md transition-all">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-xs font-bold text-accent">{{ $item->kode_booking }}</span>
                            <span class="text-xs font-medium text-gray-500">{{ \Carbon\Carbon::parse($item->jam)->format('H:i') }} WIB</span>
                        </div>
                        <h4 class="font-bold text-primary text-lg uppercase">{{ $item->plat_nomor }}</h4>
                        <p class="text-xs text-gray-500 mb-1">{{ $item->kendaraan }}</p>
                        <p class="text-xs text-gray-400 mb-3 line-clamp-1">{{ $item->keluhan }}</p>
                        <div class="flex gap-2">
                            <form action="{{ route('admin.booking.updateStatus', $item->id) }}" method="POST" class="flex-1">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="Selesai">
                                <button type="submit" class="w-full py-1.5 bg-success text-white hover:bg-success/90 rounded-lg text-xs font-semibold shadow-sm transition-colors">Selesai & Ke POS</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection

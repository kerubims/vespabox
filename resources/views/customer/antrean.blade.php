@extends('layouts.app')
@section('title', 'Live Antrean')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8 animate-fade-in">
    
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-primary">Live Antrean Hari Ini</h1>
        <p class="text-gray-500 text-sm mt-1">{{ \Carbon\Carbon::today()->translatedFormat('l, d F Y') }}</p>
    </div>

    {{-- My Booking Highlight --}}
    @if($myBooking)
    <div class="bg-gradient-to-r from-accent to-orange-500 rounded-2xl p-6 text-white shadow-lg mb-8">
        <div class="flex items-center gap-2 text-sm font-semibold opacity-90 mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Booking Anda
        </div>
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-xl font-black uppercase">{{ $myBooking->plat_nomor }}</h3>
                <p class="text-sm opacity-90">{{ $myBooking->kendaraan }}</p>
            </div>
            <div class="text-right">
                <div class="px-4 py-1.5 bg-white/20 backdrop-blur-sm rounded-full text-sm font-bold mb-1">{{ $myBooking->status }}</div>
                <p class="text-xs opacity-80">{{ \Carbon\Carbon::parse($myBooking->jam)->format('H:i') }} WIB</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Queue List --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-surface flex items-center justify-between">
            <h3 class="font-bold text-gray-700 text-sm">Daftar Antrean</h3>
            <span class="px-3 py-1 bg-primary/10 text-primary rounded-full text-xs font-bold">{{ $bookings->count() }} kendaraan</span>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($bookings as $index => $booking)
            <div class="px-6 py-4 flex items-center gap-4 {{ $myBooking && $booking->id === $myBooking->id ? 'bg-accent/5 border-l-4 border-accent' : '' }} hover:bg-gray-50 transition-colors">
                {{-- Queue Number --}}
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shrink-0
                    {{ $booking->status === 'Sedang Dikerjakan' ? 'bg-accent text-white animate-pulse' : ($booking->status === 'Selesai' ? 'bg-success/10 text-success' : 'bg-gray-100 text-gray-600') }}">
                    {{ $index + 1 }}
                </div>
                
                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-0.5">
                        <h4 class="font-bold text-primary uppercase text-sm">{{ $booking->plat_nomor }}</h4>
                        @if($myBooking && $booking->id === $myBooking->id)
                        <span class="px-1.5 py-0.5 bg-accent text-white text-[10px] font-bold rounded">ANDA</span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 truncate">{{ $booking->kendaraan }}</p>
                </div>

                {{-- Status --}}
                @php
                    $statusConfig = match($booking->status) {
                        'Sedang Dikerjakan' => ['bg' => 'bg-accent/10 text-accent', 'label' => '🔧 Dikerjakan'],
                        'Dikonfirmasi' => ['bg' => 'bg-info/10 text-info', 'label' => '✅ Dikonfirmasi'],
                        'Menunggu' => ['bg' => 'bg-warning/10 text-warning', 'label' => '⏳ Menunggu'],
                        'Selesai' => ['bg' => 'bg-success/10 text-success', 'label' => '✔ Selesai'],
                        default => ['bg' => 'bg-gray-100 text-gray-600', 'label' => $booking->status],
                    };
                @endphp
                <span class="px-3 py-1.5 rounded-full text-xs font-bold {{ $statusConfig['bg'] }} whitespace-nowrap">{{ $statusConfig['label'] }}</span>

                {{-- Time --}}
                <div class="text-right shrink-0">
                    <p class="text-sm font-bold text-gray-700">{{ \Carbon\Carbon::parse($booking->jam)->format('H:i') }}</p>
                    <p class="text-[10px] text-gray-400">WIB</p>
                </div>
            </div>
            @empty
            <div class="px-6 py-12 text-center text-gray-400 text-sm">
                <p>Belum ada antrean hari ini</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module">
    document.addEventListener('DOMContentLoaded', () => {
        if (window.Echo) {
            window.Echo.channel('queue')
                .listen('QueueUpdated', (e) => {
                    // Refresh halaman untuk memuat antrean terbaru
                    window.location.reload();
                });
        }
    });
</script>
@endpush

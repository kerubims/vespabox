@extends('layouts.app')
@section('title', 'Riwayat Servis')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8 animate-fade-in" x-data="{ activeTab: 'booking' }">
    
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-primary">Riwayat Saya</h1>
        <p class="text-gray-500 text-sm mt-1">Pantau semua booking dan riwayat servis Anda</p>
    </div>

    @if(session('success'))
    <div class="bg-success/10 border border-success/20 text-success px-4 py-3 rounded-xl text-sm font-medium mb-6" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
        {{ session('success') }}
    </div>
    @endif

    {{-- Tabs --}}
    <div class="flex p-1 bg-gray-100 rounded-xl mb-8 max-w-sm mx-auto">
        <button @click="activeTab = 'booking'" 
                :class="activeTab === 'booking' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-primary'"
                class="flex-1 py-2 text-sm font-bold rounded-lg transition-all">
            Jadwal Booking
        </button>
        <button @click="activeTab = 'service'" 
                :class="activeTab === 'service' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-primary'"
                class="flex-1 py-2 text-sm font-bold rounded-lg transition-all">
            Riwayat Servis
        </button>
    </div>

    {{-- Tab: Booking (Active) --}}
    <div x-show="activeTab === 'booking'" class="space-y-4">
        @forelse($activeBookings as $booking)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="text-xs font-bold text-gray-400">{{ $booking->kode_booking }}</span>
                            @if($booking->status === 'Menunggu')
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-warning/10 text-warning border border-warning/20">Menunggu Konfirmasi</span>
                            @else
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-primary/10 text-primary border border-primary/20">Dikonfirmasi</span>
                            @endif
                        </div>
                        <h3 class="text-lg font-black text-primary uppercase">{{ $booking->plat_nomor }}</h3>
                        <p class="text-sm text-gray-500">{{ $booking->kendaraan }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $booking->keluhan }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="font-bold text-gray-800">{{ $booking->tanggal->format('d M Y') }}</p>
                        <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($booking->jam)->format('H:i') }} WIB</p>
                        <div class="mt-4">
                            <a href="{{ route('customer.antrean') }}" class="text-xs font-bold text-accent hover:underline flex items-center justify-end gap-1">
                                <span class="material-symbols-outlined text-xs">analytics</span>
                                Pantau Antrean
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl p-12 shadow-sm border border-gray-100 text-center">
            <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-gray-400">Tidak ada jadwal booking aktif</p>
            <a href="{{ route('customer.booking.create') }}" class="mt-4 inline-block px-6 py-2 bg-accent text-white rounded-xl font-medium text-sm hover:bg-accent-hover">Booking Sekarang</a>
        </div>
        @endforelse
    </div>

    {{-- Tab: Service (Completed/Canceled) --}}
    <div x-show="activeTab === 'service'" class="space-y-4">
        @forelse($serviceHistory as $booking)
        <div x-data="{ showDetail: false }" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="text-xs font-bold text-gray-400">{{ $booking->kode_booking }}</span>
                            @if($booking->status === 'Selesai')
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-success/10 text-success border border-success/20">Selesai</span>
                            @else
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-danger/10 text-danger border border-danger/20">Dibatalkan</span>
                            @endif
                        </div>
                        <h3 class="text-lg font-black text-primary uppercase">{{ $booking->plat_nomor }}</h3>
                        <p class="text-sm text-gray-500">{{ $booking->kendaraan }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="font-bold text-gray-800">{{ $booking->tanggal->format('d M Y') }}</p>
                        <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($booking->jam)->format('H:i') }} WIB</p>
                        @if($booking->transaction)
                        <p class="text-lg font-black text-accent mt-1">Rp {{ number_format($booking->transaction->total, 0, ',', '.') }}</p>
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                @if($booking->status === 'Selesai')
                <div class="mt-4 pt-4 border-t border-gray-100 flex flex-wrap gap-3 items-center">
                    @if($booking->transaction)
                    <button @click="showDetail = true" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Rincian Biaya
                    </button>
                    @endif

                    @if($booking->review)
                    <div class="flex items-center gap-1 text-sm text-gray-500 ml-auto">
                        <span class="text-yellow-400 text-lg">★</span>
                        <span class="font-bold">{{ $booking->review->rating }}/5</span>
                        <span class="text-xs">- sudah diulas</span>
                    </div>
                    @elseif(!$booking->is_reviewed)
                    <a href="{{ route('customer.review.create', $booking->id) }}" class="px-4 py-2 bg-accent text-white rounded-lg text-sm font-medium hover:bg-accent-hover transition-all flex items-center gap-2 ml-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        Tulis Ulasan
                    </a>
                    @endif
                </div>
                @endif
            </div>

            {{-- Detail Modal --}}
            @if($booking->transaction)
            <div x-show="showDetail" 
                 x-transition.opacity 
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" 
                 style="display: none;" x-cloak>
                <div @click.away="showDetail = false" class="bg-white rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-surface">
                        <h3 class="text-lg font-bold text-primary">Rincian Transaksi</h3>
                        <button @click="showDetail = false" class="text-gray-400 hover:text-danger transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                        <div class="mb-4">
                            <p class="text-xs text-gray-500 uppercase tracking-wider font-bold mb-2">Item Servis & Sparepart</p>
                            <div class="space-y-3">
                                @forelse($booking->transaction->items as $item)
                                <div class="flex justify-between items-center text-sm">
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $item->item_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $item->qty }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    </div>
                                    <p class="font-bold text-gray-700">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                </div>
                                @empty
                                <p class="text-sm text-gray-500">Tidak ada rincian item.</p>
                                @endforelse
                            </div>
                        </div>
                        <div class="pt-4 border-t border-gray-100 space-y-2 text-sm">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal</span>
                                <span>Rp {{ number_format($booking->transaction->subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Pajak (11%)</span>
                                <span>Rp {{ number_format($booking->transaction->tax_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-lg font-black text-accent pt-2">
                                <span>Total</span>
                                <span>Rp {{ number_format($booking->transaction->total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @empty
        <div class="bg-white rounded-2xl p-12 shadow-sm border border-gray-100 text-center">
            <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <p class="text-gray-400">Belum ada riwayat servis</p>
        </div>
        @endforelse
    </div>

    @if($serviceHistory->hasPages())
    <div class="mt-6" x-show="activeTab === 'service'">{{ $serviceHistory->links() }}</div>
    @endif
</div>
@endsection

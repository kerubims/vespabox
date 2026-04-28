@extends('layouts.dashboard')
@section('title', 'Riwayat Booking')
@section('page_title', 'Riwayat Booking')
@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')
<div class="space-y-6 animate-fade-in">
    
    {{-- Tabs & Search --}}
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="flex bg-gray-100 p-1 rounded-xl w-full sm:w-auto">
            <a href="{{ route('admin.booking.index') }}" class="flex-1 sm:flex-none px-6 py-2 text-sm font-medium rounded-lg text-gray-500 hover:text-primary transition-colors text-center">Aktif</a>
            <button class="flex-1 sm:flex-none px-6 py-2 text-sm font-medium rounded-lg bg-white text-primary shadow-sm">Riwayat</button>
        </div>
        
        <form action="{{ route('admin.booking.riwayat') }}" method="GET" class="flex items-center gap-3 w-full sm:w-auto">
            <div class="relative flex-1 sm:w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode, plat, atau nama..." class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm">
            </div>
            <button type="submit" class="px-4 py-2.5 bg-accent text-white rounded-xl hover:bg-accent-hover text-sm font-medium transition-all shrink-0">Cari</button>
        </form>
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-medium">Kode</th>
                        <th class="px-6 py-4 font-medium">Pelanggan</th>
                        <th class="px-6 py-4 font-medium">Kendaraan</th>
                        <th class="px-6 py-4 font-medium">Tanggal</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                        <th class="px-6 py-4 font-medium text-right">Total</th>
                        <th class="px-6 py-4 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50/50 transition-colors" x-data="{ showDetail: false }">
                        <td class="px-6 py-4 font-medium text-primary">{{ $booking->kode_booking }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $booking->user->name ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-800 uppercase">{{ $booking->plat_nomor }}</div>
                            <div class="text-xs text-gray-500">{{ $booking->kendaraan }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-800">{{ $booking->tanggal->format('d M Y') }}</div>
                            <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($booking->jam)->format('H:i') }} WIB</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($booking->status === 'Selesai')
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-success/10 text-success border border-success/20">Selesai</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-danger/10 text-danger border border-danger/20">Dibatalkan</span>
                                @if($booking->cancel_reason)
                                <div class="mt-2 text-[11px] text-danger/80 max-w-[150px] leading-tight" title="{{ $booking->cancel_reason }}">
                                    <span class="font-bold">Alasan:</span> {{ $booking->cancel_reason }}
                                </div>
                                @endif
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right font-semibold">
                            @if($booking->transaction)
                                Rp {{ number_format($booking->transaction->total, 0, ',', '.') }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($booking->transaction)
                            <button @click="showDetail = true" class="text-accent hover:text-accent-hover text-sm font-medium transition-colors">
                                Detail
                            </button>
                            
                            {{-- Detail Modal --}}
                            <div x-show="showDetail" 
                                 x-transition.opacity 
                                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 text-left" 
                                 style="display: none;">
                                <div @click.away="showDetail = false" class="bg-white rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden cursor-default">
                                    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-surface">
                                        <h3 class="text-lg font-bold text-primary">Rincian Transaksi #{{ $booking->kode_booking }}</h3>
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
                                        <div class="pt-4 border-t border-gray-100 flex gap-2">
                                            <a href="{{ route('admin.pos.invoice', $booking->transaction->id) }}" target="_blank" class="flex-1 py-2.5 rounded-xl bg-primary text-white font-bold text-sm text-center hover:opacity-90 transition-all flex items-center justify-center gap-1.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                PDF
                                            </a>
                                            <button @click="fetch('/admin/pos/whatsapp/{{ $booking->transaction->id }}').then(r=>r.json()).then(d=>{if(d.url)window.open(d.url,'_blank');else alert('No WA number')})" class="flex-1 py-2.5 rounded-xl bg-green-500 text-white font-bold text-sm text-center hover:bg-green-600 transition-all flex items-center justify-center gap-1.5">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                                WA
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">Belum ada riwayat booking</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($bookings->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $bookings->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

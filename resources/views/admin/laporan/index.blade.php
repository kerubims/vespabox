@extends('layouts.dashboard')
@section('title', 'Laporan & Export')
@section('page_title', 'Laporan')
@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')
<div class="space-y-6 animate-fade-in">
    
    {{-- Filter & Export --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-primary mb-4">Filter Laporan</h2>
        <form action="{{ route('admin.laporan.index') }}" method="GET">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Jenis Laporan</label>
                <select name="jenis" class="w-full px-4 py-2.5 bg-surface border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent">
                    <option value="pendapatan" {{ $jenis=='pendapatan'?'selected':'' }}>Pendapatan Total</option>
                    <option value="jasa" {{ $jenis=='jasa'?'selected':'' }}>Pendapatan Jasa</option>
                    <option value="sparepart" {{ $jenis=='sparepart'?'selected':'' }}>Penjualan Sparepart</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Dari Tanggal</label>
                <input type="date" name="from" value="{{ $from }}" class="w-full px-4 py-2.5 bg-surface border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Sampai Tanggal</label>
                <input type="date" name="to" value="{{ $to }}" class="w-full px-4 py-2.5 bg-surface border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2.5 bg-primary text-white rounded-xl hover:bg-primary-light transition-all text-sm font-medium">Tampilkan</button>
            </div>
        </div>
        </form>

        <div class="flex flex-col sm:flex-row justify-between items-center pt-6 border-t border-gray-100 gap-4">
            <p class="text-sm text-gray-500">Menampilkan laporan: <strong class="text-primary">{{ ucfirst($jenis) }}</strong> ({{ \Carbon\Carbon::parse($from)->format('d M') }} - {{ \Carbon\Carbon::parse($to)->format('d M Y') }})</p>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-gradient-to-br from-primary to-primary-light p-5 rounded-xl text-white shadow-md">
            <p class="text-xs uppercase tracking-wider opacity-80 mb-1">Total Pendapatan</p>
            <h3 class="text-2xl font-bold">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
            <p class="text-xs uppercase tracking-wider text-gray-400 mb-1">Jasa Servis</p>
            <h3 class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalJasa, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
            <p class="text-xs uppercase tracking-wider text-gray-400 mb-1">Penjualan Sparepart</p>
            <h3 class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalSparepart, 0, ',', '.') }}</h3>
        </div>
    </div>

    {{-- Preview Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-surface">
            <h3 class="text-sm font-semibold text-gray-700">Preview Data Laporan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3 font-medium">Tanggal</th>
                        <th class="px-6 py-3 font-medium">Kode Booking</th>
                        <th class="px-6 py-3 font-medium">Pelanggan</th>
                        <th class="px-6 py-3 font-medium">Jasa Servis</th>
                        <th class="px-6 py-3 font-medium">Sparepart</th>
                        <th class="px-6 py-3 font-medium text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transactions as $trx)
                    @php
                        $jasaSum = $trx->items->where('item_type','jasa')->sum('subtotal');
                        $sparepartSum = $trx->items->where('item_type','sparepart')->sum('subtotal');
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $trx->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 font-medium text-primary">{{ $trx->booking->kode_booking ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $trx->booking->user->name ?? '-' }}</td>
                        <td class="px-6 py-4">Rp {{ number_format($jasaSum, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">Rp {{ number_format($sparepartSum, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right font-semibold">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">Tidak ada data transaksi dalam rentang ini</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

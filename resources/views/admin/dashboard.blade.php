@extends('layouts.dashboard')
@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard')
@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')
<div class="space-y-6 animate-fade-in">
    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        @php $kpis = [
            ['label'=>'Pendapatan Hari Ini','value'=>'Rp ' . number_format($pendapatanHariIni, 0, ',', '.'),'icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z','color'=>'accent'],
            ['label'=>'Booking Hari Ini','value'=>$bookingHariIni,'icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z','color'=>'info'],
            ['label'=>'Stok Menipis','value'=>$stokMenipis . ' item','icon'=>'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z','color'=>'warning'],
            ['label'=>'Rating Rata-rata','value'=>number_format($ratingRataRata, 1) . ' ★','icon'=>'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z','color'=>'success'],
        ]; @endphp
        @foreach($kpis as $kpi)
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all" id="kpi-{{ $loop->index }}">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">{{ $kpi['label'] }}</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $kpi['value'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-{{ $kpi['color'] }}/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-{{ $kpi['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $kpi['icon'] }}"/></svg>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Charts Row --}}
    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Revenue Trend --}}
        <div class="lg:col-span-2 bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Pendapatan 7 Hari Terakhir</h3>
            <canvas id="revenueChart" height="120"></canvas>
        </div>
        {{-- Jasa vs Sparepart --}}
        <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Pendapatan: Jasa vs Sparepart</h3>
            <canvas id="doughnutChart" height="200"></canvas>
        </div>
    </div>

    {{-- Booking Hari Ini & Low Stock --}}
    <div class="grid lg:grid-cols-2 gap-6">
        {{-- Booking Table --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-700">Booking Hari Ini</h3>
                <a href="{{ route('admin.booking.index') }}" class="text-xs text-accent font-medium hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm" id="booking-today-table">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase"><tr>
                        <th class="px-4 py-3 text-left">Kode</th>
                        <th class="px-4 py-3 text-left">Pelanggan</th>
                        <th class="px-4 py-3 text-left">Status</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($bookingsToday as $b)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium">{{ $b->kode_booking }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $b->user->name ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $statusColor = match($b->status) {
                                        'Sedang Dikerjakan' => 'accent',
                                        'Dikonfirmasi' => 'info',
                                        'Menunggu' => 'warning',
                                        'Selesai' => 'success',
                                        default => 'gray-500',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-{{ $statusColor }}/10 text-{{ $statusColor }}">{{ $b->status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-gray-400">Belum ada booking hari ini</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Low Stock --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-700">⚠️ Stok Menipis</h3>
                <a href="{{ route('admin.sparepart.index') }}" class="text-xs text-accent font-medium hover:underline">Kelola Stok</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($lowStockItems as $item)
                <div class="px-6 py-3 flex items-center justify-between hover:bg-gray-50">
                    <span class="text-sm text-gray-700">{{ $item->nama }}</span>
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $item->stok == 0 ? 'bg-danger/10 text-danger' : 'bg-warning/10 text-warning' }}">{{ $item->stok }} pcs</span>
                </div>
                @empty
                <div class="px-6 py-6 text-center text-gray-400 text-sm">Semua stok aman 👍</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Line Chart
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Pendapatan',
                data: @json($chartData),
                borderColor: '#ff6b35',
                backgroundColor: 'rgba(255,107,53,0.1)',
                fill: true, tension: 0.4, borderWidth: 2,
                pointBackgroundColor: '#ff6b35', pointRadius: 4,
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { ticks: { callback: v => 'Rp ' + (v/1000000).toFixed(1) + 'jt' } } } }
    });
    // Doughnut Chart
    new Chart(document.getElementById('doughnutChart'), {
        type: 'doughnut',
        data: {
            labels: ['Jasa Servis', 'Sparepart'],
            datasets: [{ data: [{{ $totalJasa }}, {{ $totalSparepart }}], backgroundColor: ['#ff6b35', '#1a1a2e'], borderWidth: 0 }]
        },
        options: { responsive: true, cutout: '70%', plugins: { legend: { position: 'bottom' } } }
    });
});
</script>
@endpush

{{-- Admin Sidebar Navigation Items --}}
{{-- Usage: @include('partials.sidebar-admin') --}}

@php
    $adminMenus = [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'url' => '/admin/dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
        ['label' => 'Manajemen Booking', 'route' => 'admin.booking.index', 'url' => '/admin/booking', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
        ['label' => 'Kasir', 'route' => 'admin.pos.*', 'url' => '/admin/pos', 'icon' => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z'],
        ['label' => 'Sparepart & Stok', 'route' => 'admin.sparepart.*', 'url' => '/admin/sparepart', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
        ['label' => 'Restock', 'route' => 'admin.restock.*', 'url' => '/admin/restock', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
        ['label' => 'Riwayat Servis', 'route' => 'admin.booking.riwayat', 'url' => '/admin/booking/riwayat', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
        ['label' => 'Laporan', 'route' => 'admin.laporan.*', 'url' => '/admin/laporan', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
        ['label' => 'Review Pelanggan', 'route' => 'admin.review.*', 'url' => '/admin/review', 'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
        ['label' => 'Manajemen Slot', 'route' => 'admin.slot.*', 'url' => '/admin/slot', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
    ];
@endphp

@foreach($adminMenus as $menu)
    @php
        $isActive = request()->routeIs($menu['route']);
    @endphp
    <a
        href="{{ $menu['url'] }}"
        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200
            {{ $isActive
                ? 'bg-primary text-white shadow-lg shadow-primary/20'
                : 'text-gray-600 hover:text-primary hover:bg-primary/5' }}"
        id="sidebar-{{ \Illuminate\Support\Str::slug($menu['label']) }}"
    >
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $menu['icon'] }}"/>
        </svg>
        <span class="whitespace-nowrap transition-opacity duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">
            {{ $menu['label'] }}
        </span>
    </a>
@endforeach

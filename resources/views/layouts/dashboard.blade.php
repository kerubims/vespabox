<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — VespaBox</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-surface text-gray-800 min-h-screen" 
    x-data="adminDashboard()"
    x-init="initNotifications()"
>

    <div class="flex min-h-screen">
        {{-- ===== SIDEBAR ===== --}}
        <aside
            class="fixed inset-y-0 left-0 z-40 flex flex-col bg-white text-gray-800 border-r border-gray-100 transition-all duration-300 ease-in-out"
            :class="sidebarOpen ? 'w-64' : 'w-20'"
            id="sidebar"
        >
            {{-- Logo --}}
            <div class="flex items-center justify-center h-20 px-4 border-b border-gray-100 shrink-0 overflow-hidden">
                <a href="/" class="flex items-center justify-center transition-all duration-300" id="sidebar-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="VespaBox" class="transition-all duration-300" :class="sidebarOpen ? 'h-12 w-auto' : 'h-6 w-auto'">
                </a>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1" id="sidebar-nav">
                @yield('sidebar')
            </nav>

            {{-- User Info (bottom) --}}
            <div class="border-t border-gray-100 p-4 shrink-0">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="w-9 h-9 rounded-full bg-primary/10 flex items-center justify-center text-primary font-semibold shrink-0">
                        {{ substr(Auth::user()->nama ?? 'U', 0, 1) }}
                    </div>
                    <div class="transition-opacity duration-300 min-w-0" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">
                        <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->nama ?? 'User' }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email ?? 'user@mail.com' }}</p>
                    </div>
                </div>
            </div>
        </aside>

        {{-- ===== MOBILE SIDEBAR OVERLAY ===== --}}
        <div
            x-show="mobileMenuOpen"
            x-transition:enter="transition-opacity ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-30 bg-black/50 lg:hidden"
            @click="mobileMenuOpen = false"
        ></div>

        {{-- ===== MAIN AREA ===== --}}
        <div class="flex-1 flex flex-col transition-all duration-300" :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-20'">

            {{-- Top Bar --}}
            <header class="sticky top-0 z-20 bg-white/80 backdrop-blur-md border-b border-gray-200/60 h-16 flex items-center justify-between px-4 lg:px-6 shrink-0">
                <div class="flex items-center gap-3">
                    {{-- Toggle Sidebar (Desktop) --}}
                    <button
                        class="hidden lg:flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-all"
                        @click="sidebarOpen = !sidebarOpen"
                        id="toggle-sidebar-btn"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    {{-- Toggle Sidebar (Mobile) --}}
                    <button
                        class="lg:hidden flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:bg-gray-100 transition-all"
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        id="toggle-sidebar-mobile"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    {{-- Page Title --}}
                    <h1 class="text-lg font-semibold text-gray-800">@yield('page_title', 'Dashboard')</h1>
                </div>

                <div class="flex items-center gap-2">
                    {{-- Notification Bell --}}
                    <div class="relative" id="notification-container">
                        <button
                            class="relative flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-all"
                            @click="notifOpen = !notifOpen"
                            id="notification-btn"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            {{-- Unread Badge --}}
                            <span x-show="notifCount > 0" x-text="notifCount" class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-danger text-white text-[10px] font-bold rounded-full flex items-center justify-center" id="notif-badge"></span>
                        </button>

                        {{-- Notification Dropdown --}}
                        <div
                            x-show="notifOpen"
                            @click.away="notifOpen = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden"
                            id="notification-dropdown"
                        >
                            <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                                <h3 class="text-sm font-semibold text-gray-800">Notifikasi</h3>
                                <button @click="markAllAsRead()" class="text-xs text-accent hover:text-accent-hover font-medium" id="mark-all-read-btn">Tandai semua dibaca</button>
                            </div>
                            <div class="max-h-72 overflow-y-auto divide-y divide-gray-50" id="notification-list">
                                <template x-if="notifications.length === 0">
                                    <div class="px-4 py-6 text-center text-gray-500 text-sm">
                                        Tidak ada notifikasi baru.
                                    </div>
                                </template>
                                <template x-for="notif in notifications" :key="notif.id">
                                    <div class="px-4 py-3 hover:bg-gray-50 transition-colors cursor-pointer bg-accent/5" @click="markAsRead(notif.id)">
                                        <div class="flex gap-3">
                                            <div class="w-8 h-8 rounded-full bg-info/10 flex items-center justify-center shrink-0">
                                                <svg class="w-4 h-4 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm text-gray-700"><span class="font-medium" x-text="notif.data.kode_booking"></span> telah dibuat oleh <span x-text="notif.data.customer_name"></span></p>
                                                <p class="text-xs text-gray-400 mt-0.5" x-text="formatTimeAgo(notif.created_at)"></p>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <a href="#" class="block px-4 py-3 text-center text-sm font-medium text-accent hover:bg-gray-50 border-t border-gray-100">Lihat Semua Notifikasi</a>
                        </div>
                    </div>

                    {{-- Profile Dropdown --}}
                    <div class="relative" x-data="{ profileOpen: false }">
                        <button
                            class="relative flex items-center justify-center w-9 h-9 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all border border-gray-200"
                            @click="profileOpen = !profileOpen"
                            id="profile-btn"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </button>

                        <div
                            x-show="profileOpen"
                            @click.away="profileOpen = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                            x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden z-50"
                            id="profile-dropdown"
                        >
                            <div class="px-4 py-3 border-b border-gray-50 bg-gray-50">
                                <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email ?? 'admin@mail.com' }}</p>
                            </div>
                            <div class="py-1 text-sm text-gray-600">
                                <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-2 px-4 py-2.5 hover:bg-primary/5 hover:text-primary transition-colors font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Edit Profil
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="block border-t border-gray-50 mt-1 pt-1">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 hover:bg-red-50 hover:text-danger transition-colors text-left font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 p-4 lg:p-6">
                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="mb-4 px-4 py-3 rounded-lg bg-success/10 border border-success/20 text-success text-sm animate-fade-in" id="flash-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 px-4 py-3 rounded-lg bg-danger/10 border border-danger/20 text-danger text-sm animate-fade-in" id="flash-error">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    {{-- Alpine.js CDN (for interactivity without heavy JS) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
    <script>
        function adminDashboard() {
            return {
                sidebarOpen: true,
                mobileMenuOpen: false,
                notifOpen: false,
                notifCount: 0,
                notifications: [],
                now: new Date(),
                
                formatTimeAgo(dateString) {
                    if (!dateString) return 'Baru saja';
                    // Touch this.now so Alpine re-evaluates when this.now changes
                    const n = this.now; 
                    const date = new Date(dateString);
                    const seconds = Math.floor((n - date) / 1000);
                    
                    if (seconds < 60) return 'Baru saja';
                    
                    const minutes = Math.floor(seconds / 60);
                    if (minutes < 60) return minutes + ' menit yang lalu';
                    
                    const hours = Math.floor(minutes / 60);
                    if (hours < 24) return hours + ' jam yang lalu';
                    
                    const days = Math.floor(hours / 24);
                    if (days < 30) return days + ' hari yang lalu';
                    
                    const months = Math.floor(days / 30);
                    if (months < 12) return months + ' bulan yang lalu';
                    
                    return Math.floor(months / 12) + ' tahun yang lalu';
                },
                
                async initNotifications() {
                    setInterval(() => { this.now = new Date(); }, 60000);
                    // Fetch existing unread notifications
                    try {
                        const res = await fetch('{{ route("admin.notifications.index") }}', {
                            headers: { 'Accept': 'application/json' }
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.notifications = data.notifications;
                            this.notifCount = this.notifications.length;
                        }
                    } catch (e) { console.error('Error fetching notifications:', e); }

                    // Listen to Laravel Echo for real-time notifications
                    if (window.Echo) {
                        const userId = {{ auth()->id() ?? 'null' }};
                        if (userId) {
                            window.Echo.private('App.Models.User.' + userId)
                                .notification((notification) => {
                                    if (!this.notifications.some(n => n.id === notification.id)) {
                                        // Prepend the new notification
                                        this.notifications.unshift({
                                            id: notification.id,
                                            created_at: new Date().toISOString(),
                                            data: {
                                                kode_booking: notification.kode_booking,
                                                customer_name: notification.customer_name
                                            }
                                        });
                                        this.notifCount++;
                                    }
                                });
                        }
                    }
                },
                
                async markAsRead(id) {
                    try {
                        const res = await fetch('{{ route("admin.notifications.read") }}', {
                            method: 'POST',
                            headers: { 
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ id: id })
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.notifications = this.notifications.filter(n => n.id !== id);
                            this.notifCount = this.notifications.length;
                        }
                    } catch (e) { console.error(e); }
                },
                
                async markAllAsRead() {
                    try {
                        const res = await fetch('{{ route("admin.notifications.read") }}', {
                            method: 'POST',
                            headers: { 
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.notifications = [];
                            this.notifCount = 0;
                            this.notifOpen = false;
                        }
                    } catch (e) { console.error(e); }
                }
            }
        }
    </script>
</body>
</html>

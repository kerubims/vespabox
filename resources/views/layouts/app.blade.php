<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'VespaBox - Bengkel servis motor terpercaya. Booking online, sparepart berkualitas, dan layanan profesional.')">
    <title>@yield('title', 'VespaBox') — Vespa Repair</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-surface text-on-surface min-h-screen flex flex-col">

    <!-- TopNavBar -->
    <nav class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-xl shadow-[0px_20px_40px_rgba(25,28,30,0.06)]" x-data="customerNav()" x-init="initNotifications()">
        <div class="flex justify-between items-center px-8 py-4 max-w-7xl mx-auto">
            <a href="/" class="flex items-center">
                <img src="{{ asset('images/logo.png') }}" alt="VespaBox" class="h-14 w-auto">
            </a>
            
            <div class="hidden md:flex space-x-8 items-center">
                <a class="{{ request()->is('/') ? 'text-primary border-b-2 border-primary pb-1' : 'text-slate-500 hover:text-primary transition-colors' }} font-medium text-sm tracking-wide" href="/">Beranda</a>
                <a class="{{ request()->is('katalog') ? 'text-primary border-b-2 border-primary pb-1' : 'text-slate-500 hover:text-primary transition-colors' }} font-medium text-sm tracking-wide" href="/katalog">Katalog</a>
                
                @auth
                    @if(auth()->user()->role === 'customer')
                        <a class="{{ request()->is('customer/riwayat') ? 'text-primary border-b-2 border-primary pb-1' : 'text-slate-500 hover:text-primary transition-colors' }} font-medium text-sm tracking-wide" href="{{ route('customer.riwayat') }}">Riwayat Servis</a>
                        <a class="{{ request()->is('customer/antrean') ? 'text-primary border-b-2 border-primary pb-1' : 'text-slate-500 hover:text-primary transition-colors' }} font-medium text-sm tracking-wide" href="{{ route('customer.antrean') }}">Antrean Langsung</a>
                        <a href="{{ route('customer.booking.create') }}" class="bg-primary hover:bg-primary-container text-on-primary font-medium text-sm px-5 py-2.5 rounded transition-transform scale-95 active:opacity-80">
                            Pesan Servis
                        </a>

                        {{-- Notification Bell --}}
                        <div class="relative flex items-center" id="notification-container">
                            <button
                                class="relative flex items-center justify-center w-9 h-9 rounded-full text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition-all"
                                @click="notifOpen = !notifOpen"
                                id="notification-btn"
                            >
                                <span class="material-symbols-outlined text-[24px]">notifications</span>
                                {{-- Unread Badge --}}
                                <span x-show="notifCount > 0" x-text="notifCount" class="absolute top-0 right-0 w-4 h-4 bg-error text-white text-[10px] font-bold rounded-full flex items-center justify-center" id="notif-badge" x-cloak></span>
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
                                class="absolute right-0 top-full mt-3 w-80 bg-white rounded-xl shadow-[0px_10px_40px_rgba(0,0,0,0.08)] border border-slate-100 overflow-hidden"
                                id="notification-dropdown"
                                x-cloak
                            >
                                <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
                                    <h3 class="text-sm font-semibold text-slate-800">Notifikasi</h3>
                                    <button @click="markAllAsRead()" class="text-xs text-primary hover:text-primary-container font-medium" id="mark-all-read-btn">Tandai semua dibaca</button>
                                </div>
                                <div class="max-h-72 overflow-y-auto divide-y divide-slate-50" id="notification-list">
                                    <template x-if="notifications.length === 0">
                                        <div class="px-4 py-6 text-center text-slate-500 text-sm">
                                            Tidak ada notifikasi baru.
                                        </div>
                                    </template>
                                    <template x-for="notif in notifications" :key="notif.id">
                                        <div class="px-4 py-3 hover:bg-slate-50 transition-colors cursor-pointer bg-primary/5" @click="markAsRead(notif.id)">
                                            <div class="flex gap-3">
                                                <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                                                    <span class="material-symbols-outlined text-[16px] text-primary">notifications_active</span>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-sm text-slate-700" x-text="notif.data.message"></p>
                                                    <p class="text-xs text-slate-400 mt-0.5" x-text="formatTimeAgo(notif.created_at)"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-error hover:text-error-container transition-colors">Keluar</button>
                        </form>
                    @elseif(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="bg-primary hover:bg-primary-container text-on-primary font-medium text-sm px-5 py-2.5 rounded transition-transform scale-95 active:opacity-80">
                            Panel Admin
                        </a>
                    @endif
                @else                    
                    <a href="{{ route('login') }}" class="bg-primary hover:bg-primary-container text-on-primary font-medium text-sm px-5 py-2.5 rounded transition-transform scale-95 active:opacity-80">
                        Pesan Jadwal
                    </a>
                @endauth
            </div>

            <button class="md:hidden text-primary p-2" @click="mobileMenuOpen = !mobileMenuOpen">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileMenuOpen" class="md:hidden border-t border-slate-100 bg-white" x-cloak>
            <div class="px-4 py-4 space-y-4 flex flex-col">
                <a href="/" class="text-sm font-medium text-slate-700">Home</a>
                <a href="/katalog" class="text-sm font-medium text-slate-700">Catalog</a>
                @auth
                    @if(auth()->user()->role === 'customer')
                        <a href="{{ route('customer.riwayat') }}" class="text-sm font-medium text-slate-700">Service History</a>
                        <a href="{{ route('customer.antrean') }}" class="text-sm font-medium text-slate-700">Live Queue</a>
                        <a href="{{ route('customer.booking.create') }}" class="text-sm font-medium text-primary">Book Service</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-error text-left">Logout</button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-primary">Book Appointment</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content Wrapper (added top padding for fixed navbar) -->
    <main class="flex-grow pt-32 pb-24 relative z-10 w-full {{ !request()->is('/') ? 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8' : '' }}">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-auto bg-slate-50 w-full py-12 px-8 border-t border-slate-100">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 max-w-7xl mx-auto">
            <div class="col-span-1 md:col-span-2">
                <div class="mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="VespaBox" class="h-12 w-auto">
                </div>
                <p class="font-body text-sm text-slate-500 max-w-xs mb-6">
                    Perawatan skuter presisi, mendefinisikan ulang pengalaman bengkel.
                </p>
                <div class="font-body text-sm text-slate-500">
                    &copy; {{ date('Y') }} VespaBox. Hak cipta dilindungi undang-undang.
                </div>
            </div>
            <div>
                <h4 class="font-semibold text-primary mb-4 text-sm uppercase tracking-wider">Kontak</h4>
                <ul class="space-y-3">
                     <li><a class="text-slate-400 hover:text-primary transition-colors text-sm" target="_blank" href="https://www.instagram.com/catalogvespabox.official/">Instagram</a></li>
                    <li><a class="text-slate-400 hover:text-primary transition-colors text-sm" target="_blank" href="https://wa.me/6281233345588">WhatsApp</a></li>
                    <p class="text-slate-400 text-sm mb-4 leading-relaxed">
                        Jl. Bromo IIA No.43, Oro-oro Dowo, Kec. Klojen, Kota Malang, Jawa Timur 65119
                    </p>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-primary mb-4 text-sm uppercase tracking-wider">Lokasi</h4>
                <div class="rounded-lg overflow-hidden border border-slate-200 h-32 w-full shadow-sm">
                    <iframe 
                        src="https://maps.google.com/maps?q=VESPABOX+Indonesia&hl=id&z=16&output=embed" 
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </footer>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
    <script>
        function customerNav() {
            return {
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
                    const authUserId = {{ auth()->check() ? auth()->id() : 'null' }};
                    if (!authUserId) return;

                    // Fetch existing unread notifications
                    try {
                        const res = await fetch('{{ auth()->check() && auth()->user()->role === "customer" ? route("customer.notifications.index") : "" }}', {
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
                        window.Echo.private('App.Models.User.' + authUserId)
                            .notification((notification) => {
                                if (!this.notifications.some(n => n.id === notification.id)) {
                                    // Prepend the new notification
                                    this.notifications.unshift({
                                        id: notification.id,
                                        created_at: new Date().toISOString(),
                                        data: {
                                            kode_booking: notification.kode_booking,
                                            message: notification.message || 'Status Booking ' + notification.kode_booking + ' diperbarui',
                                        }
                                    });
                                    this.notifCount++;
                                }
                            });
                    }
                },
                
                async markAsRead(id) {
                    try {
                        const res = await fetch('{{ auth()->check() && auth()->user()->role === "customer" ? route("customer.notifications.read") : "" }}', {
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
                        const res = await fetch('{{ auth()->check() && auth()->user()->role === "customer" ? route("customer.notifications.read") : "" }}', {
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

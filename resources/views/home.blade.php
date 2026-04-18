@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center max-w-7xl mx-auto px-8 w-full">
    <!-- Left: Typography & Actions -->
    <div class="space-y-8 z-10 relative">
        <div class="absolute -top-12 -left-12 w-48 h-48 bg-primary/5 rounded-full blur-3xl -z-10"></div>
        <h1 class="font-headline font-extrabold text-5xl md:text-6xl lg:text-7xl leading-tight tracking-tight text-on-surface">
            Perawatan Ahli untuk Vespa Anda. <span class="text-primary block mt-2">Pesan Servis Secara Online.</span>
        </h1>
        <p class="font-body text-lg text-on-surface-variant max-w-xl leading-relaxed">
            Rasakan layanan mesin skuter yang presisi dengan pendekatan perbaikan profesional kami. Lacak status Vespa Anda secara real-time, tinjau diagnosis yang transparan, dan jadwalkan kunjungan Anda berikutnya dengan mudah dari perangkat apa pun.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 pt-4">
            <a href="{{ route('login') }}" class="bg-gradient-to-br from-primary to-primary-container text-white font-medium px-8 py-4 rounded-lg shadow-[0px_20px_40px_rgba(25,28,30,0.06)] hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                Jadwalkan Servis
                <span class="material-symbols-outlined" data-icon="arrow_forward" style="font-variation-settings: 'FILL' 0;">arrow_forward</span>
            </a>
            <a href="/katalog" class="bg-transparent border border-outline-variant hover:border-primary text-primary font-medium px-8 py-4 rounded-lg transition-all flex items-center justify-center gap-2">
                Lihat Katalog
                <span class="material-symbols-outlined" data-icon="query_stats" style="font-variation-settings: 'FILL' 0;">query_stats</span>
            </a>
        </div>
    </div>

    <!-- Right: Asymmetric Image/Glassmorphism Treatment -->
    <div class="relative h-[600px] w-full hidden lg:block">
        <!-- Large background image slightly offset -->
        <div class="absolute top-0 right-0 w-[90%] h-[90%] rounded-2xl overflow-hidden shadow-[0px_20px_40px_rgba(25,28,30,0.06)] z-0">
            <img src="{{ asset('images/hero section3.gif') }}" 
                 alt="Mechanic working on modern Vespa" 
                 class="w-full h-full object-cover"/>
            <!-- Overlay gradient for professional feel -->
            <div class="absolute inset-0 bg-gradient-to-tr from-surface/40 to-transparent"></div>
        </div>

        <!-- Floating Compact Info Card -->
        <div class="absolute bottom-16 left-0 w-64 bg-white/90 backdrop-blur-xl p-4 rounded-xl shadow-xl border border-white/40 z-20">
            <div class="flex items-center gap-3 mb-3 pb-3 border-b border-slate-100">
                <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-2xl">stars</span>
                </div>
                <div>
                    <p class="font-headline font-bold text-on-surface text-sm">{{ $stats['avg_rating'] }}/5.0 Rating</p>
                    <p class="text-[10px] text-on-surface-variant font-medium">{{ $stats['total_reviews'] }} ulasan pelanggan</p>
                </div>
            </div>
            
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] uppercase tracking-widest text-on-surface-variant font-bold mb-0.5">Total Service</p>
                    <p class="font-headline font-black text-primary text-base">{{ number_format($stats['total_servis']) }}+ <span class="text-[10px] font-medium text-on-surface-variant uppercase ml-0.5">Vespa</span></p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full bg-success/10 text-success text-[10px] font-bold">
                        <span class="w-1.5 h-1.5 rounded-full bg-success animate-pulse"></span>
                        Open
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<section class="mt-24 pt-24 pb-24 bg-surface-container-low w-full relative z-10 w-screen relative left-[50%] right-[50%] ml-[-50vw] mr-[-50vw]">
    <div class="max-w-7xl mx-auto px-8">
        <div class="mb-16 max-w-2xl">
            <h2 class="font-headline text-3xl md:text-4xl font-bold text-on-surface mb-4">Keunggulan VespaBox</h2>
            <p class="font-body text-on-surface-variant text-lg">Keakuratan teknis dipadukan dengan layanan transparan. Temukan mengapa pendekatan diagnostik kami menetapkan standar baru untuk perawatan Vespa.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Feature 1 -->
            <div class="bg-surface-container-lowest p-8 rounded-xl shadow-[0px_20px_40px_rgba(25,28,30,0.06)] hover:-translate-y-1 transition-transform duration-300 relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-1 h-full bg-transparent group-hover:bg-primary transition-colors"></div>
                <div class="w-14 h-14 bg-secondary-container text-primary rounded-lg flex items-center justify-center mb-6">
                    <span class="material-symbols-outlined text-3xl">verified</span>
                </div>
                <h3 class="font-headline text-xl font-bold text-on-surface mb-3">Mekanik Bersertifikat</h3>
                <p class="font-body text-sm text-on-surface-variant leading-relaxed">Spesialis terlatih pabrik menggunakan protokol diagnostik canggih untuk identifikasi kerusakan yang tepat.</p>
            </div>

            <!-- Feature 2 -->
            <div class="bg-surface-container-lowest p-8 rounded-xl shadow-[0px_20px_40px_rgba(25,28,30,0.06)] hover:-translate-y-1 transition-transform duration-300 relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-1 h-full bg-transparent group-hover:bg-primary transition-colors"></div>
                <div class="w-14 h-14 bg-secondary-container text-primary rounded-lg flex items-center justify-center mb-6">
                    <span class="material-symbols-outlined text-3xl">build</span>
                </div>
                <h3 class="font-headline text-xl font-bold text-on-surface mb-3">Suku Cadang Asli</h3>
                <p class="font-body text-sm text-on-surface-variant leading-relaxed">Penggunaan komponen OEM eksklusif yang memastikan integritas dan performa Vespa Anda tetap terjaga.</p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-surface-container-lowest p-8 rounded-xl shadow-[0px_20px_40px_rgba(25,28,30,0.06)] hover:-translate-y-1 transition-transform duration-300 relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-1 h-full bg-transparent group-hover:bg-primary transition-colors"></div>
                <div class="w-14 h-14 bg-secondary-container text-primary rounded-lg flex items-center justify-center mb-6">
                    <span class="material-symbols-outlined text-3xl">timeline</span>
                </div>
                <h3 class="font-headline text-xl font-bold text-on-surface mb-3">Antrean Real-time</h3>
                <p class="font-body text-sm text-on-surface-variant leading-relaxed">Pantau perkembangan Vespa Anda di ruang kerja secara langsung dari perangkat Anda, menghilangkan kecemasan di ruang tunggu.</p>
            </div>

            <!-- Feature 4 -->
            <div class="bg-surface-container-lowest p-8 rounded-xl shadow-[0px_20px_40px_rgba(25,28,30,0.06)] hover:-translate-y-1 transition-transform duration-300 relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-1 h-full bg-transparent group-hover:bg-primary transition-colors"></div>
                <div class="w-14 h-14 bg-secondary-container text-primary rounded-lg flex items-center justify-center mb-6">
                    <span class="material-symbols-outlined text-3xl">history</span>
                </div>
                <h3 class="font-headline text-xl font-bold text-on-surface mb-3">Riwayat Servis</h3>
                <p class="font-body text-sm text-on-surface-variant leading-relaxed">Akses catatan digital komprehensif, laporan inspeksi, dan jadwal pemeliharaan secara aman secara online.</p>
            </div>
        </div>
    </div>
</section>

<!-- Reviews Section -->
@if($reviews->count() > 0)
<section class="py-24 bg-surface" x-data="{ showAll: false }">
    <div class="max-w-7xl mx-auto px-8">
        <div class="text-center mb-16">
            <h2 class="font-headline text-4xl font-extrabold text-on-surface mb-4">Testimoni Pelanggan</h2>
            <p class="font-body text-on-surface-variant text-lg max-w-2xl mx-auto">Dengarkan dari komunitas penggemar Vespa kami yang mempercayakan kendaraan berharga mereka kepada VespaBox.</p>
        </div>

        {{-- First 3 reviews (always visible) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($reviews->take(3) as $review)
            <div class="bg-white/40 backdrop-blur-md p-8 rounded-2xl border border-white/20 shadow-xl relative group overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary to-primary-container"></div>
                <div class="flex items-center gap-1 text-yellow-500 mb-6">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $review->rating)
                            <span class="material-symbols-outlined fill-1">star</span>
                        @else
                            <span class="material-symbols-outlined text-slate-300">star</span>
                        @endif
                    @endfor
                </div>
                <p class="font-body text-on-surface italic leading-relaxed mb-8">"{{ $review->comment }}"</p>
                <div class="flex items-center gap-4">
                    @php
                        $initials = collect(explode(' ', $review->user->name ?? 'U'))->map(fn($w) => strtoupper(mb_substr($w, 0, 1)))->take(2)->join('');
                        $colors = ['bg-primary-fixed text-primary', 'bg-secondary-container text-secondary', 'bg-tertiary-fixed text-tertiary'];
                        $colorClass = $colors[$loop->index % count($colors)];
                    @endphp
                    <div class="w-12 h-12 rounded-full {{ $colorClass }} flex items-center justify-center font-bold">{{ $initials }}</div>
                    <div>
                        <p class="font-headline font-bold text-on-surface">{{ $review->user->name ?? 'Pelanggan' }}</p>
                        @if($review->booking)
                            <p class="font-label text-xs text-on-surface-variant uppercase tracking-widest">{{ $review->booking->kendaraan ?? '' }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Extra reviews (hidden until "show more" clicked) --}}
        @if($reviews->count() > 3)
        <div 
            x-show="showAll" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8"
            x-cloak
        >
            @foreach($reviews->slice(3) as $review)
            <div class="bg-white/40 backdrop-blur-md p-8 rounded-2xl border border-white/20 shadow-xl relative group overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary to-primary-container"></div>
                <div class="flex items-center gap-1 text-yellow-500 mb-6">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $review->rating)
                            <span class="material-symbols-outlined fill-1">star</span>
                        @else
                            <span class="material-symbols-outlined text-slate-300">star</span>
                        @endif
                    @endfor
                </div>
                <p class="font-body text-on-surface italic leading-relaxed mb-8">"{{ $review->comment }}"</p>
                <div class="flex items-center gap-4">
                    @php
                        $initials = collect(explode(' ', $review->user->name ?? 'U'))->map(fn($w) => strtoupper(mb_substr($w, 0, 1)))->take(2)->join('');
                        $colors = ['bg-primary-fixed text-primary', 'bg-secondary-container text-secondary', 'bg-tertiary-fixed text-tertiary'];
                        $colorClass = $colors[$loop->index % count($colors)];
                    @endphp
                    <div class="w-12 h-12 rounded-full {{ $colorClass }} flex items-center justify-center font-bold">{{ $initials }}</div>
                    <div>
                        <p class="font-headline font-bold text-on-surface">{{ $review->user->name ?? 'Pelanggan' }}</p>
                        @if($review->booking)
                            <p class="font-label text-xs text-on-surface-variant uppercase tracking-widest">{{ $review->booking->kendaraan ?? '' }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Show More / Show Less Button --}}
        <div class="text-center mt-12">
            <button 
                @click="showAll = !showAll" 
                class="inline-flex items-center gap-2 px-8 py-3 rounded-xl border-2 border-primary text-primary font-semibold text-sm hover:bg-primary hover:text-white transition-all duration-300"
            >
                <span x-text="showAll ? 'Tampilkan Lebih Sedikit' : 'Tampilkan Lebih Banyak ({{ $reviews->count() - 3 }} lainnya)'"></span>
                <span class="material-symbols-outlined text-[18px] transition-transform" :class="showAll ? 'rotate-180' : ''">expand_more</span>
            </button>
        </div>
        @endif
    </div>
</section>
@endif

@endsection

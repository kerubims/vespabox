@extends('layouts.dashboard')
@section('title', 'Manajemen Review')
@section('page_title', 'Ulasan Pelanggan')
@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')
<div class="space-y-6 animate-fade-in">
    
    @if(session('success'))
    <div class="bg-success/10 border border-success/20 text-success px-4 py-3 rounded-xl text-sm font-medium" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
        {{ session('success') }}
    </div>
    @endif

    {{-- Summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-xl p-5 text-white shadow-sm flex items-center justify-between border border-yellow-300">
            <div>
                <p class="text-xs uppercase tracking-wider opacity-90 mb-1 font-semibold">Rating Rata-rata</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl font-black">{{ number_format($avgRating, 1) }}</h3>
                    <span class="text-sm font-medium opacity-80">dari 5</span>
                </div>
            </div>
            <svg class="w-12 h-12 opacity-50" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-wider text-gray-500 font-semibold mb-1">Total Ulasan</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $totalReviews }}</h3>
            </div>
            <div class="w-12 h-12 bg-primary/5 text-primary rounded-full flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-wider text-gray-500 font-semibold mb-1">Perlu Ditanggapi</p>
                <h3 class="text-2xl font-bold text-danger">{{ $unrepliedCount }}</h3>
            </div>
            <div class="w-12 h-12 bg-danger/5 text-danger rounded-full flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="flex gap-2 w-full sm:w-auto overflow-x-auto no-scrollbar">
            <a href="{{ route('admin.review.index') }}" class="px-4 py-1.5 rounded-full text-sm font-bold {{ !request('filter') ? 'bg-primary text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-accent' }} whitespace-nowrap">Semua</a>
            <a href="{{ route('admin.review.index', ['filter'=>'5star']) }}" class="px-4 py-1.5 rounded-full text-sm font-semibold {{ request('filter')=='5star' ? 'bg-primary text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-accent' }} whitespace-nowrap">5 Bintang</a>
            <a href="{{ route('admin.review.index', ['filter'=>'low']) }}" class="px-4 py-1.5 rounded-full text-sm font-semibold {{ request('filter')=='low' ? 'bg-primary text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-accent' }} whitespace-nowrap">< 4 Bintang</a>
            <a href="{{ route('admin.review.index', ['filter'=>'unreplied']) }}" class="px-4 py-1.5 rounded-full text-sm font-semibold {{ request('filter')=='unreplied' ? 'bg-primary text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-accent' }} whitespace-nowrap relative">
                Belum Ditanggapi
                @if($unrepliedCount > 0)<span class="absolute -top-1 -right-1 w-3 h-3 bg-danger rounded-full border-2 border-white"></span>@endif
            </a>
        </div>
    </div>

    {{-- Reviews List --}}
    <div class="space-y-4">
        @forelse($reviews as $review)
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100" x-data="{ showReplyForm: false }">
            <div class="flex flex-col sm:flex-row gap-6">
                <div class="flex-1">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">{{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}</div>
                            <div>
                                <h4 class="font-bold text-gray-800">{{ $review->user->name ?? '-' }}</h4>
                                <p class="text-xs text-gray-500">{{ $review->created_at->format('d M Y') }} • {{ $review->booking->kode_booking ?? '' }}</p>
                            </div>
                        </div>
                        <div class="flex text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mt-3 leading-relaxed">{{ $review->comment }}</p>

                    @if($review->admin_reply)
                    <div class="mt-4 bg-gray-50 border-l-4 border-accent p-4 rounded-r-xl">
                        <p class="text-xs font-bold text-primary mb-1">Balasan Admin VespaBox:</p>
                        <p class="text-sm text-gray-600">{{ $review->admin_reply }}</p>
                    </div>
                    @endif

                    {{-- Reply Form --}}
                    <div x-show="showReplyForm" x-cloak class="mt-4">
                        <form action="{{ route('admin.review.reply', $review->id) }}" method="POST">
                            @csrf
                            <textarea name="admin_reply" rows="3" placeholder="Tulis balasan..." class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-accent focus:border-accent" required>{{ $review->admin_reply }}</textarea>
                            <div class="flex gap-2 mt-2">
                                <button type="submit" class="px-4 py-2 bg-accent text-white rounded-lg text-sm font-medium hover:bg-accent-hover">Kirim Balasan</button>
                                <button type="button" @click="showReplyForm = false" class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="sm:w-40 sm:border-l sm:border-gray-100 sm:pl-6 flex flex-col gap-2 justify-center shrink-0">
                    <button @click="showReplyForm = !showReplyForm" class="w-full py-2 bg-white border border-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-all text-center">
                        {{ $review->admin_reply ? 'Edit Balasan' : 'Tanggapi' }}
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl p-12 shadow-sm border border-gray-100 text-center text-gray-400">
            <p class="text-sm">Belum ada ulasan</p>
        </div>
        @endforelse
    </div>

    @if($reviews->hasPages())
    <div>{{ $reviews->withQueryString()->links() }}</div>
    @endif
</div>
@endsection

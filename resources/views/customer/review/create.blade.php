@extends('layouts.app')
@section('title', 'Tulis Ulasan')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8 animate-fade-in">
    
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-primary">Tulis Ulasan</h1>
        <p class="text-gray-500 text-sm mt-1">Berikan penilaian Anda untuk layanan kami</p>
    </div>

    @if($booking)
    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-xs font-bold text-gray-400 mb-1">{{ $booking->kode_booking }}</p>
                <h3 class="text-lg font-black text-primary uppercase">{{ $booking->plat_nomor }}</h3>
                <p class="text-sm text-gray-500">{{ $booking->kendaraan }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm font-medium text-gray-600">{{ $booking->tanggal->format('d M Y') }}</p>
                <p class="text-xs text-gray-400">{{ $booking->keluhan }}</p>
            </div>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 p-4 bg-danger/10 border border-danger/20 text-danger rounded-xl text-sm">
        <ul class="list-disc pl-4 space-y-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
    @endif

    <form action="{{ route('customer.review.store') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
        @csrf
        @if($booking)
        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
        @endif

        {{-- Star Rating --}}
        <div x-data="{ rating: {{ old('rating', 5) }}, hover: 0 }">
            <label class="block text-sm font-semibold text-gray-700 mb-3">Rating Keseluruhan</label>
            <div class="flex gap-2 justify-center py-4">
                @for($i = 1; $i <= 5; $i++)
                <button type="button" @click="rating = {{ $i }}" @mouseenter="hover = {{ $i }}" @mouseleave="hover = 0" class="transition-transform hover:scale-110">
                    <svg class="w-10 h-10 transition-colors" :class="(hover || rating) >= {{ $i }} ? 'text-yellow-400' : 'text-gray-200'" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </button>
                @endfor
            </div>
            <input type="hidden" name="rating" :value="rating">
            <p class="text-center text-sm font-medium" :class="rating >= 4 ? 'text-success' : (rating >= 3 ? 'text-warning' : 'text-danger')">
                <span x-show="rating === 5">Luar Biasa! ⭐</span>
                <span x-show="rating === 4">Sangat Bagus 👍</span>
                <span x-show="rating === 3">Cukup Baik 🙂</span>
                <span x-show="rating === 2">Kurang Puas 😕</span>
                <span x-show="rating === 1">Sangat Kecewa 😞</span>
            </p>
        </div>

        {{-- Comment --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Komentar (opsional)</label>
            <textarea name="comment" rows="4" placeholder="Ceritakan pengalaman Anda saat servis di VespaBox..." class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-accent focus:border-accent transition-all resize-none">{{ old('comment') }}</textarea>
        </div>

        <div class="flex gap-3 pt-4 border-t border-gray-100">
            <a href="{{ route('customer.riwayat') }}" class="flex-1 py-3 rounded-xl border border-gray-200 text-gray-600 font-medium text-center hover:bg-gray-50 text-sm">Batal</a>
            <button type="submit" class="flex-1 py-3 rounded-xl bg-accent text-white font-bold hover:bg-accent-hover shadow-md shadow-accent/20 transition-all text-sm flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Kirim Ulasan
            </button>
        </div>
    </form>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Booking Berhasil')

@section('content')
<div class="max-w-3xl mx-auto py-8 animate-slide-up">
    <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden text-center p-8 sm:p-12">
        
        {{-- Success Animation / Icon --}}
        <div class="w-24 h-24 bg-success/10 rounded-full flex items-center justify-center mx-auto mb-6">
            <div class="w-16 h-16 bg-success text-white rounded-full flex items-center justify-center shadow-lg shadow-success/30">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            </div>
        </div>

        <h2 class="text-3xl font-extrabold text-primary mb-2">Booking Berhasil!</h2>
        <p class="text-gray-500 mb-8">Jadwal servis Anda telah tercatat dalam sistem kami.</p>

        {{-- Booking Details Card --}}
        <div class="bg-surface rounded-2xl p-6 mb-8 text-left max-w-md mx-auto border border-gray-100 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1 h-full bg-accent"></div>
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-200 border-dashed">
                <span class="text-sm font-medium text-gray-500">Kode Booking</span>
                <span class="text-2xl font-black text-accent tracking-wider">{{ $booking->kode_booking }}</span>
            </div>
            
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Tanggal Servis</span>
                    <span class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($booking->tanggal)->translatedFormat('d F Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Jam Estimasi</span>
                    <span class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($booking->jam)->format('H:i') }} WIB</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Plat Nomor</span>
                    <span class="font-semibold text-gray-800 uppercase">{{ $booking->plat_nomor }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Kendaraan</span>
                    <span class="font-semibold text-gray-800">{{ $booking->kendaraan }}</span>
                </div>
            </div>
        </div>

        <p class="text-sm text-gray-400 mb-8 max-w-sm mx-auto">
            Harap datang 15 menit sebelum waktu yang ditentukan. Anda dapat memantau antrean secara real-time.
        </p>

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="/customer/antrean" class="w-full sm:w-auto px-8 py-3 rounded-xl bg-accent text-white font-semibold hover:bg-accent-hover shadow-md shadow-accent/20 transition-all">
                Lihat Daftar Antrean
            </a>
        </div>
        
    </div>
</div>
@endsection

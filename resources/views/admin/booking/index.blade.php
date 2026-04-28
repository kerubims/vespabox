@extends('layouts.dashboard')
@section('title', 'Manajemen Booking')
@section('page_title', 'Booking Aktif')
@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')
<div class="space-y-6 animate-fade-in" x-data="{ cancelModalOpen: false, cancelFormAction: '', currentBookingId: '' }">
    
    {{-- Success Message --}}
    @if(session('success'))
    <div class="bg-success/10 border border-success/20 text-success px-4 py-3 rounded-xl text-sm font-medium" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
        {{ session('success') }}
    </div>
    @endif

    {{-- Tabs & Filters --}}
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="flex bg-gray-100 p-1 rounded-xl w-full sm:w-auto">
            <button class="flex-1 sm:flex-none px-6 py-2 text-sm font-medium rounded-lg bg-white text-primary shadow-sm">Aktif</button>
            <a href="{{ route('admin.booking.riwayat') }}" class="flex-1 sm:flex-none px-6 py-2 text-sm font-medium rounded-lg text-gray-500 hover:text-primary transition-colors text-center">Riwayat</a>
        </div>
    </div>

    {{-- Kanban / Grid View for Active Bookings --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
        
        {{-- Menunggu --}}
        <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
            <div class="flex items-center justify-between mb-4 px-2">
                <h3 class="font-bold text-gray-700 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-warning"></span>
                    Menunggu
                </h3>
                <span class="px-2 py-0.5 rounded-md bg-gray-200 text-xs font-bold text-gray-600">{{ $counts['menunggu'] }}</span>
            </div>
            <div class="space-y-3">
                @foreach($bookings->where('status', 'Menunggu') as $item)
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-xs font-bold text-gray-400">{{ $item->kode_booking }}</span>
                            <span class="text-xs font-medium text-gray-500">{{ \Carbon\Carbon::parse($item->jam)->format('H:i') }} WIB</span>
                        </div>
                        <h4 class="font-bold text-primary text-lg uppercase">{{ $item->plat_nomor }}</h4>
                        <p class="text-xs text-gray-500 mb-1">{{ $item->kendaraan }}</p>
                        <p class="text-xs text-gray-400 mb-3 line-clamp-1">{{ $item->keluhan }}</p>
                        <div class="flex gap-2">
                            <form action="{{ route('admin.booking.updateStatus', $item->id) }}" method="POST" class="flex-1">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="Dikonfirmasi">
                                <button type="submit" class="w-full py-1.5 bg-accent text-white hover:bg-accent/90 rounded-lg text-xs font-semibold shadow-sm transition-colors">Konfirmasi</button>
                            </form>
                            <button type="button" class="flex items-center justify-center px-2.5 bg-white border border-gray-200 text-gray-500 hover:text-danger hover:border-danger hover:bg-red-50 rounded-lg transition-colors shrink-0" @click.prevent.stop="cancelModalOpen = true; cancelFormAction = '{{ route('admin.booking.updateStatus', $item->id) }}'; currentBookingId = '{{ $item->kode_booking }}'" title="Batalkan">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Dikonfirmasi --}}
        <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
            <div class="flex items-center justify-between mb-4 px-2">
                <h3 class="font-bold text-gray-700 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-info"></span>
                    Dikonfirmasi
                </h3>
                <span class="px-2 py-0.5 rounded-md bg-gray-200 text-xs font-bold text-gray-600">{{ $counts['dikonfirmasi'] }}</span>
            </div>
            <div class="space-y-3">
                @foreach($bookings->where('status', 'Dikonfirmasi') as $item)
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-info/30 hover:shadow-md transition-all">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-xs font-bold text-info">{{ $item->kode_booking }}</span>
                            <span class="text-xs font-medium text-gray-500">{{ \Carbon\Carbon::parse($item->jam)->format('H:i') }} WIB</span>
                        </div>
                        <h4 class="font-bold text-primary text-lg uppercase">{{ $item->plat_nomor }}</h4>
                        <p class="text-xs text-gray-500 mb-1">{{ $item->kendaraan }}</p>
                        <p class="text-xs text-gray-400 mb-3 line-clamp-1">{{ $item->keluhan }}</p>
                        <div class="flex gap-2">
                            <form action="{{ route('admin.booking.updateStatus', $item->id) }}" method="POST" class="flex-1">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="Sedang Dikerjakan">
                                <button type="submit" class="w-full py-1.5 bg-info text-white hover:bg-info/90 rounded-lg text-xs font-semibold shadow-sm transition-colors">Kerjakan</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Sedang Dikerjakan --}}
        <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
            <div class="flex items-center justify-between mb-4 px-2">
                <h3 class="font-bold text-gray-700 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-accent animate-pulse"></span>
                    Dikerjakan
                </h3>
                <span class="px-2 py-0.5 rounded-md bg-gray-200 text-xs font-bold text-gray-600">{{ $counts['dikerjakan'] }}</span>
            </div>
            <div class="space-y-3">
                @foreach($bookings->where('status', 'Sedang Dikerjakan') as $item)
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-accent/30 hover:shadow-md transition-all">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-xs font-bold text-accent">{{ $item->kode_booking }}</span>
                            <span class="text-xs font-medium text-gray-500">{{ \Carbon\Carbon::parse($item->jam)->format('H:i') }} WIB</span>
                        </div>
                        <h4 class="font-bold text-primary text-lg uppercase">{{ $item->plat_nomor }}</h4>
                        <p class="text-xs text-gray-500 mb-1">{{ $item->kendaraan }}</p>
                        <p class="text-xs text-gray-400 mb-3 line-clamp-1">{{ $item->keluhan }}</p>
                        <div class="flex gap-2">
                            <form action="{{ route('admin.booking.updateStatus', $item->id) }}" method="POST" class="flex-1">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="Selesai">
                                <button type="submit" class="w-full py-1.5 bg-success text-white hover:bg-success/90 rounded-lg text-xs font-semibold shadow-sm transition-colors">Selesai & Ke POS</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- Cancel Modal --}}
    <div x-show="cancelModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="cancelModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="cancelModalOpen" @click.away="cancelModalOpen = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative z-10 inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                <form :action="cancelFormAction" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="Dibatalkan">
                    
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-surface">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-danger/10 text-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800" id="modal-title">
                                Batalkan Booking <span x-text="currentBookingId" class="text-primary font-black ml-1"></span>
                            </h3>
                        </div>
                        <button type="button" @click="cancelModalOpen = false" class="text-gray-400 hover:text-danger transition-colors">
                            <span class="material-symbols-outlined"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
</svg>
</span>
                        </button>
                    </div>
                    
                    <div class="p-6 bg-white">                                            
                        <div>
                            <label for="cancel_reason" class="block text-sm font-bold text-gray-700 mb-2">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea id="cancel_reason" name="cancel_reason" rows="4" class="w-full rounded-xl border border-gray-200 shadow-sm focus:border-danger focus:ring focus:ring-danger/20 transition-all text-sm p-3.5 bg-gray-50 hover:bg-white focus:bg-white outline-none" placeholder="Contoh: Maaf, jadwal bengkel sudah penuh untuk hari ini. Silakan melakukan booking di hari lain..." required></textarea>
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-100 flex justify-end gap-3 rounded-b-2xl">
                        <button type="button" @click="cancelModalOpen = false" class="px-5 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-600 font-medium hover:bg-gray-50 hover:text-gray-800 transition-colors text-sm">
                            Kembali
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-danger text-white font-bold hover:bg-red-600 transition-all text-sm shadow-[0_8px_16px_rgba(220,38,38,0.15)] hover:shadow-[0_8px_20px_rgba(220,38,38,0.25)] hover:-translate-y-0.5">
                            Konfirmasi Pembatalan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

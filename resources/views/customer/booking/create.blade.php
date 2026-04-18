@extends('layouts.app')
@section('title', 'Book a Service')

@section('content')
<div class="max-w-3xl mx-auto space-y-8 animate-fade-in" x-data="bookingForm()">
    {{-- Error Modal --}}
    <div x-show="showErrorModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-cloak>
        <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full overflow-hidden transform transition-all"
             @click.away="showErrorModal = false">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-error/10 text-error rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-4xl">warning</span>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Booking Gagal</h3>
                <p class="text-slate-500 text-sm mb-6">{{ $errors->first('jam') }}</p>
                <button @click="showErrorModal = false" class="w-full py-3 bg-primary text-white font-semibold rounded-xl hover:bg-primary-container transition-colors">
                    Pilih Jam Lain
                </button>
            </div>
        </div>
    </div>

    <div class="text-center mb-8">
        <h1 class="font-headline font-extrabold text-3xl md:text-4xl text-on-surface mb-2">Schedule Your Service</h1>
        <p class="font-body text-on-surface-variant">Complete your vehicle details and select an available time slot.</p>
    </div>
    
    <div class="bg-surface-container-lowest rounded-2xl shadow-[0px_20px_40px_rgba(25,28,30,0.06)] overflow-hidden border border-slate-100">

        <div class="p-6 sm:p-8">
            <form action="{{ route('customer.booking.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    
                    {{-- Bagian 1: Data Kendaraan --}}
                    <div class="space-y-4">
                        <h3 class="font-headline font-semibold text-primary uppercase tracking-wider mb-2 text-sm">1. Vehicle Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Plat Nomor <span class="text-danger">*</span></label>
                                <input type="text" name="plat_nomor" value="{{ old('plat_nomor') }}" required placeholder="B 1234 XY" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent uppercase transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Merek & Tipe <span class="text-danger">*</span></label>
                                <input type="text" name="kendaraan" value="{{ old('kendaraan') }}" required placeholder="Vespa Sprint 150 i-get" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Keluhan / Jenis Servis <span class="text-danger">*</span></label>
                            <textarea name="keluhan" required rows="3" placeholder="Contoh: Servis rutin, ganti oli, tarikan berat..." class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent transition-all resize-none">{{ old('keluhan') }}</textarea>
                        </div>
                    </div>

                    {{-- Bagian 2: Jadwal & Slot --}}
                    <div class="space-y-4 pt-6 border-t border-slate-100">
                        <h3 class="font-headline font-semibold text-primary uppercase tracking-wider mb-2 text-sm">2. Select Schedule</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Pilihan Tanggal --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Tanggal</label>
                                <input type="date" name="tanggal" required x-model="date" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent transition-all">
                                <p class="text-xs text-gray-400 mt-2">*Minggu tutup.</p>
                            </div>

                            {{-- Slot Cerdas (Interactive) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Slot Jam</label>
                                
                                <div x-show="!date" class="text-sm text-gray-500 py-3 text-center border border-dashed border-gray-200 rounded-xl bg-gray-50">
                                    Silakan pilih tanggal terlebih dahulu.
                                </div>

                                <div x-show="date" class="relative min-h-[60px]">
                                    <div x-show="isLoadingSlots" class="absolute inset-0 flex items-center justify-center bg-white/50 z-10">
                                        <div class="w-6 h-6 border-2 border-accent border-t-transparent rounded-full animate-spin"></div>
                                    </div>

                                    <div x-show="!isLoadingSlots && slots.length === 0" class="text-sm text-center py-4 text-gray-500 border border-dashed rounded-xl">
                                        Tidak ada slot tersedia untuk hari ini.
                                    </div>

                                    <div x-show="!isLoadingSlots && slots.length > 0" class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                        <template x-for="slot in slots" :key="slot.time">
                                            <label class="relative">
                                                <input type="radio" name="jam" :value="slot.time" x-model="time" class="peer sr-only" :disabled="!slot.available">
                                                <div class="p-2 text-center rounded-xl border cursor-pointer transition-all"
                                                     :class="{
                                                        'bg-success/10 border-success/30 text-success hover:bg-success/20': slot.available && time !== slot.time,
                                                        'bg-gray-100 border-gray-200 text-gray-400 cursor-not-allowed opacity-60': !slot.available,
                                                        'bg-accent border-accent text-white shadow-md': time === slot.time
                                                     }">
                                                    <div class="font-bold text-sm" x-text="slot.time"></div>
                                                    <div class="text-[10px] mt-0.5" :class="time === slot.time ? 'text-white/80' : 'text-gray-500'" x-text="slot.available ? slot.remaining + ' sisa' : 'Penuh'"></div>
                                                </div>
                                                <div x-show="time === slot.time" class="absolute -top-1 -right-1 w-3 h-3 bg-white rounded-full flex items-center justify-center shadow-sm">
                                                    <div class="w-2 h-2 bg-accent rounded-full"></div>
                                                </div>
                                            </label>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-8 border-t border-slate-100 flex items-center justify-between">
                        <a href="{{ route('customer.riwayat') }}" class="text-sm text-slate-500 hover:text-primary font-medium transition-colors">Cancel</a>
                        <button type="submit" class="px-6 py-3 rounded-lg bg-primary text-on-primary font-medium hover:bg-primary-container shadow-md transition-all flex items-center gap-2" :disabled="!time">
                            <span class="material-symbols-outlined text-sm">check_circle</span>
                            Confirm Booking
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function bookingForm() {
        return {
            date: '{{ old('tanggal') }}',
            time: '{{ old('jam') }}',
            slots: [],
            isLoadingSlots: false,
            showErrorModal: {{ $errors->has('jam') ? 'true' : 'false' }},
            days: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
            
            async fetchAvailability(selectedDate) {
                this.isLoadingSlots = true;
                // Don't reset time if it's from old() and we are initializing
                if (!this.time || this.time === '{{ old('jam') }}') {
                    // keep it
                } else {
                    this.time = '';
                }

                try {
                    const response = await fetch(`{{ route('customer.booking.checkAvailability') }}?date=${selectedDate}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!response.ok) throw new Error('Failed to fetch availability');
                    
                    this.slots = await response.json();
                } catch (error) {
                    console.error('Error fetching availability:', error);
                    this.slots = [];
                } finally {
                    this.isLoadingSlots = false;
                }
            },

            init() {
                // Initial fetch if date is present from old()
                if (this.date) {
                    this.fetchAvailability(this.date);
                }

                this.$watch('date', (value) => {
                    if (value) {
                        const d = new Date(value);
                        const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                        const selectedDayName = dayNames[d.getDay()];
                        
                        if (this.days.includes(selectedDayName)) {
                            this.fetchAvailability(value);
                        } else {
                            alert('Maaf, bengkel tutup pada hari Minggu. Silakan pilih hari lain.');
                            this.date = '';
                            this.slots = [];
                        }
                    }
                });
            }
        }
    }
</script>
@endpush

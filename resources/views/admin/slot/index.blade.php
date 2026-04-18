@extends('layouts.dashboard')
@section('title', 'Manajemen Slot Booking')
@section('page_title', 'Slot Cerdas')
@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')
<div class="space-y-6 animate-fade-in" x-data="slotManager()">
    
    @if(session('success'))
    <div class="bg-success/10 border border-success/20 text-success px-4 py-3 rounded-xl text-sm font-medium" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-surface flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-lg font-bold text-primary flex items-center gap-2">
                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Pengaturan Kapasitas Harian
                </h2>
                <p class="text-sm text-gray-500 mt-1">Atur jumlah maksimal booking kendaraan per jam operasional.</p>
            </div>
            <button @click="saveSlots()" :disabled="isSaving" class="px-6 py-2.5 rounded-xl bg-accent text-white font-semibold hover:bg-accent-hover shadow-md shadow-accent/20 transition-all text-sm flex items-center gap-2 shrink-0 disabled:opacity-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span x-text="isSaving ? 'Menyimpan...' : 'Simpan Perubahan'"></span>
            </button>
        </div>

        <div class="p-6">
            <div class="flex overflow-x-auto pb-4 mb-4 gap-2 no-scrollbar border-b border-gray-100">
                <template x-for="day in days" :key="day">
                    <button class="px-6 py-2.5 rounded-xl font-medium text-sm transition-all whitespace-nowrap"
                            :class="activeDay === day ? 'bg-primary text-white shadow-md' : 'bg-gray-50 text-gray-600 hover:bg-gray-100'"
                            @click="activeDay = day" x-text="day"></button>
                </template>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                <template x-for="slot in getSlotsForActiveDay()" :key="slot.id">
                    <div class="border border-gray-200 rounded-xl p-4 bg-white hover:border-accent hover:shadow-sm transition-all group">
                        <div class="flex justify-between items-center mb-3">
                            <div class="font-bold text-gray-800 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span x-text="slot.jam"></span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" x-model="slot.is_active">
                                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-success"></div>
                            </label>
                        </div>
                        <div :class="slot.is_active ? 'opacity-100' : 'opacity-50 pointer-events-none'">
                            <label class="block text-xs text-gray-500 mb-1">Maksimal Booking</label>
                            <div class="flex items-center gap-2">
                                <button type="button" class="w-8 h-8 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 flex items-center justify-center font-bold" @click="if(slot.kapasitas > 0) slot.kapasitas--">-</button>
                                <input type="number" x-model.number="slot.kapasitas" class="flex-1 w-full text-center px-2 py-1.5 border border-gray-200 rounded-lg text-sm font-bold focus:ring-1 focus:ring-accent focus:border-accent" min="0">
                                <button type="button" class="w-8 h-8 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 flex items-center justify-center font-bold" @click="slot.kapasitas++">+</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            
            <div class="mt-8 p-4 bg-info/10 rounded-xl border border-info/20 flex gap-3">
                <svg class="w-5 h-5 text-info shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div class="text-sm text-info">
                    <p class="font-bold mb-1">Informasi Fitur Slot Cerdas</p>
                    <p>Sistem akan secara otomatis menyembunyikan jam yang sudah mencapai batas maksimal booking (kapasitas penuh) pada halaman pelanggan.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@php
    $formattedSlots = $slots->map(function($daySlots, $day) {
        return $daySlots->map(function($s) {
            return [
                'id' => $s->id, 
                'jam' => \Carbon\Carbon::parse($s->jam)->format('H:i'), 
                'kapasitas' => $s->kapasitas, 
                'is_active' => (bool)$s->is_active
            ];
        });
    });
@endphp
<script>
function slotManager() {
    return {
        days: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
        activeDay: 'Senin',
        isSaving: false,
        slots: @json($formattedSlots),
        getSlotsForActiveDay() {
            return this.slots[this.activeDay] || [];
        },
        async saveSlots() {
            this.isSaving = true;
            const allSlots = [];
            Object.values(this.slots).forEach(daySlots => {
                daySlots.forEach(s => allSlots.push({ id: s.id, kapasitas: s.kapasitas, is_active: s.is_active ? 1 : 0 }));
            });
            try {
                const res = await fetch('{{ route("admin.slot.update") }}', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ slots: allSlots })
                });
                
                if (!res.ok) {
                    const errorText = await res.text();
                    console.error('Server error:', errorText);
                    throw new Error(`Server returned ${res.status}: ${res.statusText}`);
                }

                const data = await res.json();
                alert(data.success ? 'Slot berhasil disimpan!' : 'Gagal menyimpan');
            } catch (e) { 
                console.error(e);
                alert('Terjadi kesalahan: ' + e.message); 
            }
            this.isSaving = false;
        }
    }
}
</script>
@endpush

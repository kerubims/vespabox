@extends('layouts.dashboard')
@section('title', 'Restock Sparepart')
@section('page_title', 'Restock Sparepart')
@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')
<div class="h-[calc(100vh-8rem)] flex flex-col lg:flex-row gap-6 animate-fade-in overflow-hidden" x-data="restockSystem()">
    
    {{-- Left: Pencarian & Katalog Sparepart --}}
    <div class="flex-1 min-w-0 bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-surface">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" x-model="searchQuery" placeholder="Cari nama sparepart atau kode item..." class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm shadow-sm">
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mt-3 w-full pb-1">
                <button class="px-2 py-1.5 rounded-full text-xs font-bold truncate text-center" :class="activeFilter==='all'?'bg-primary text-white':'bg-white border border-gray-200 text-gray-600 hover:border-accent'" @click="activeFilter='all'" title="Semua">Semua</button>
                <template x-for="cat in categories" :key="cat">
                    <button class="px-2 py-1.5 rounded-full text-xs font-semibold truncate text-center" :class="activeFilter===cat?'bg-primary text-white':'bg-white border border-gray-200 text-gray-600 hover:border-accent'" @click="activeFilter=cat" x-text="cat" :title="cat"></button>
                </template>
            </div>
        </div>
        <div class="flex-1 overflow-y-auto p-4 bg-gray-50/50">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <template x-for="item in filteredProducts" :key="item.id">
                    <div class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm transition-all flex flex-col h-full relative hover:border-accent cursor-pointer group" 
                         @click="addToCart(item)">
                        <div class="h-24 bg-gray-50 rounded-lg flex items-center justify-center mb-3 relative overflow-hidden transition-colors group-hover:bg-primary/5">
                            <template x-if="item.image">
                                <img :src="item.image" :alt="item.nama" class="absolute inset-0 w-full h-full object-cover">
                            </template>
                            <template x-if="!item.image">
                                <svg class="w-10 h-10 text-gray-300 transition-colors group-hover:text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                            </template>
                            
                            {{-- Badge Stok Rendah --}}
                            <div x-show="item.stok <= item.stok_minimum && item.stok > 0" class="absolute top-2 right-2">
                                <span class="bg-warning/10 text-warning text-[10px] font-bold px-2 py-0.5 rounded-md">Stok Rendah</span>
                            </div>
                            <div x-show="item.stok === 0" class="absolute top-2 right-2">
                                <span class="bg-danger/10 text-danger text-[10px] font-bold px-2 py-0.5 rounded-md">Habis</span>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <h4 class="text-xs font-bold text-gray-800 line-clamp-2 mb-1" x-text="item.nama"></h4>
                            <p class="text-[10px] text-gray-400 font-medium" x-text="item.kode"></p>
                            <div class="flex items-center justify-between mt-2">
                                <p class="text-[10px] font-medium" :class="item.stok <= item.stok_minimum ? 'text-danger' : 'text-gray-500'">
                                    Stok: <span x-text="item.stok" class="font-bold"></span>
                                </p>
                                <span class="text-[10px] bg-accent/10 text-accent px-2 py-0.5 rounded font-bold" x-text="item.kategori"></span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div x-show="filteredProducts.length === 0" class="h-full flex flex-col items-center justify-center text-gray-400 py-12">
                <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <p class="text-sm">Sparepart tidak ditemukan</p>
            </div>
        </div>
    </div>

    {{-- Right: Daftar Restock --}}
    <div class="w-full lg:w-96 bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col overflow-hidden shrink-0">
        <div class="p-4 border-b border-gray-100 bg-surface">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-accent/10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-primary text-sm">Daftar Restock</h3>
                    <p class="text-xs text-gray-400">Klik item di kiri untuk menambahkan</p>
                </div>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-3">
            <template x-for="(cartItem, index) in cart" :key="cartItem.id">
                <div class="flex gap-3 items-center pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-gray-800 line-clamp-1" x-text="cartItem.nama"></h4>
                        <p class="text-[10px] text-gray-400 mt-0.5">Stok saat ini: <span x-text="cartItem.stok" class="font-semibold"></span></p>
                    </div>
                    <div class="flex items-center gap-1.5 shrink-0">
                        <button class="w-7 h-7 rounded-lg bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-danger/10 hover:text-danger transition-colors font-bold text-sm" @click="decreaseQty(index)">−</button>
                        <input type="number" x-model.number="cartItem.qty" min="1" class="w-12 h-7 text-center text-sm font-bold border border-gray-200 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none">
                        <button class="w-7 h-7 rounded-lg bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-success/10 hover:text-success transition-colors font-bold text-sm" @click="increaseQty(index)">+</button>
                    </div>
                    <button class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-300 hover:bg-danger/10 hover:text-danger transition-colors shrink-0" @click="removeItem(index)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </template>
            <div x-show="cart.length === 0" class="h-full flex flex-col items-center justify-center text-gray-400">
                <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <p class="text-sm">Belum ada item restock</p>
                <p class="text-xs text-gray-300 mt-1">Klik sparepart di sebelah kiri</p>
            </div>
        </div>

        <div class="p-4 bg-surface border-t border-gray-100">
            {{-- Catatan --}}
            <div class="mb-3">
                <input type="text" x-model="catatan" placeholder="Catatan (opsional)..." class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-accent focus:border-accent">
            </div>

            {{-- Summary --}}
            <div class="space-y-2 mb-4 text-sm">
                <div class="flex justify-between text-gray-500">
                    <span>Total Item</span>
                    <span class="font-semibold text-gray-800" x-text="cart.length + ' produk'"></span>
                </div>
                <div class="flex justify-between text-gray-500">
                    <span>Total Quantity</span>
                    <span class="font-semibold text-gray-800" x-text="totalQty + ' unit'"></span>
                </div>
            </div>

            <button class="w-full py-3.5 rounded-xl bg-accent text-white font-bold hover:bg-accent-hover shadow-md shadow-accent/20 transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed" 
                    :disabled="cart.length === 0 || isProcessing" 
                    @click="showConfirm = true">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span x-text="isProcessing ? 'Memproses...' : 'Proses Restock'"></span>
            </button>
        </div>
    </div>

    {{-- CONFIRMATION MODAL --}}
    <div x-show="showConfirm" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4" style="display: none;">
        <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl overflow-hidden" x-transition.scale.90>
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-warning/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-xl font-black text-primary mb-2">Konfirmasi Restock</h3>
                <p class="text-sm text-gray-500 mb-4">Apakah Anda yakin daftar item dan kuantitas restock sudah benar? Stok akan ditambahkan secara otomatis.</p>
                <div class="bg-surface p-3 rounded-xl mb-4 text-left">
                    <p class="text-sm text-gray-600 flex justify-between">Total Produk: <span class="font-bold text-gray-800" x-text="cart.length"></span></p>
                    <p class="text-sm text-gray-600 flex justify-between">Total Unit: <span class="font-bold text-gray-800" x-text="totalQty"></span></p>
                </div>
                <div class="flex gap-3">
                    <button @click="showConfirm = false" class="flex-1 py-3 rounded-xl bg-gray-100 text-gray-700 font-bold transition-all hover:bg-gray-200">
                        Batal
                    </button>
                    <button @click="showConfirm = false; processRestock()" class="flex-1 py-3 rounded-xl bg-accent text-white font-bold transition-all hover:bg-accent-hover shadow-md">
                        Ya, Lanjutkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- SUCCESS MODAL --}}
    <div x-show="showSuccess" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4" style="display: none;">
        <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl overflow-hidden" x-transition.scale.90>
            <div class="p-8 text-center">
                <div class="w-16 h-16 bg-success/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h3 class="text-xl font-black text-primary mb-2">Restock Berhasil!</h3>
                <p class="text-sm text-gray-500 mb-1">Stok sparepart telah diperbarui otomatis.</p>
                <p class="text-xs text-gray-400 mt-2">Kode Restock:</p>
                <p class="text-lg font-black text-accent mt-1" x-text="successKode"></p>
                <div class="flex gap-4 justify-center mt-4">
                    <div class="text-center">
                        <p class="text-2xl font-black text-primary" x-text="successTotalItem"></p>
                        <p class="text-xs text-gray-400">Produk</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-black text-primary" x-text="successTotalQty"></p>
                        <p class="text-xs text-gray-400">Unit</p>
                    </div>
                </div>
            </div>
            <div class="px-6 pb-6 space-y-3">
                <button @click="closeSuccess()" class="w-full py-3 rounded-xl bg-primary text-white font-bold transition-all hover:opacity-90">
                    Kembali ke Restock
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function restockSystem() {
    return {
        searchQuery: '',
        activeFilter: 'all',
        isProcessing: false,
        showConfirm: false,
        showSuccess: false,
        successKode: '',
        successTotalItem: 0,
        successTotalQty: 0,
        catatan: '',
        products: [
            @foreach($spareparts as $sp)
            { id: {{ $sp->id }}, kode: @json($sp->kode), nama: @json($sp->nama), kategori: @json($sp->kategori), stok: {{ $sp->stok }}, stok_minimum: {{ $sp->stok_minimum }}, harga_beli: {{ $sp->harga_beli ?? 0 }}, image: @json($sp->image ? asset('storage/' . $sp->image) : null) },
            @endforeach
        ],
        cart: [],

        get categories() {
            return [...new Set(this.products.map(p => p.kategori))];
        },

        get filteredProducts() {
            let items = this.products;
            if (this.activeFilter !== 'all') items = items.filter(p => p.kategori === this.activeFilter);
            if (this.searchQuery) items = items.filter(p => p.nama.toLowerCase().includes(this.searchQuery.toLowerCase()) || p.kode.toLowerCase().includes(this.searchQuery.toLowerCase()));
            return items;
        },

        get totalQty() {
            return this.cart.reduce((t, i) => t + (i.qty || 0), 0);
        },

        addToCart(product) {
            const existing = this.cart.find(i => i.id === product.id);
            if (existing) {
                existing.qty++;
            } else {
                this.cart.push({ ...product, qty: 1 });
            }
        },

        increaseQty(i) {
            this.cart[i].qty++;
        },

        decreaseQty(i) {
            if (this.cart[i].qty > 1) {
                this.cart[i].qty--;
            } else {
                this.cart.splice(i, 1);
            }
        },

        removeItem(i) {
            this.cart.splice(i, 1);
        },

        async processRestock() {
            if (this.cart.length === 0) return;
            this.isProcessing = true;
            try {
                const items = this.cart.map(i => ({
                    sparepart_id: i.id,
                    qty: i.qty,
                    harga_beli: i.harga_beli || 0
                }));
                const res = await fetch('{{ route("admin.restock.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ items, catatan: this.catatan })
                });
                const data = await res.json();
                if (data.success) {
                    this.successKode = data.kode_restock;
                    this.successTotalItem = data.total_item;
                    this.successTotalQty = data.total_qty;
                    this.showSuccess = true;
                } else {
                    alert('Gagal: ' + (data.message || 'Terjadi kesalahan'));
                }
            } catch (e) {
                alert('Error: ' + e.message);
            }
            this.isProcessing = false;
        },

        closeSuccess() {
            window.location.href = '{{ route("admin.restock.index") }}';
        }
    }
}
</script>
@endpush

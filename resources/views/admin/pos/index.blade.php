@extends('layouts.dashboard')
@section('title', 'POS / Kasir')
@section('page_title', 'Point of Sale')
@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')
<div class="h-[calc(100vh-8rem)] flex flex-col lg:flex-row gap-6 animate-fade-in" x-data="posSystem()">
    
    {{-- Left: Pencarian & Katalog --}}
    <div class="flex-1 bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-surface">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" x-model="searchQuery" placeholder="Cari nama sparepart atau kode item..." class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm shadow-sm">
            </div>
            <div class="flex gap-2 mt-3 w-full md:w-1/2 overflow-x-auto no-scrollbar pb-1">
                <button class="px-4 py-1.5 rounded-full text-xs font-bold whitespace-nowrap" :class="activeFilter==='all'?'bg-primary text-white':'bg-white border border-gray-200 text-gray-600 hover:border-accent'" @click="activeFilter='all'">Semua</button>
                <button class="px-4 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap" :class="activeFilter==='jasa'?'bg-primary text-white':'bg-white border border-gray-200 text-gray-600 hover:border-accent'" @click="activeFilter='jasa'">Jasa Servis</button>
                <button class="px-4 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap" :class="activeFilter==='sparepart'?'bg-primary text-white':'bg-white border border-gray-200 text-gray-600 hover:border-accent'" @click="activeFilter='sparepart'">Sparepart</button>
            </div>
        </div>
        <div class="flex-1 overflow-y-auto p-4 bg-gray-50/50">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <template x-for="item in filteredProducts" :key="item.id + '-' + item.type">
                    <div class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm transition-all flex flex-col h-full relative" 
                         :class="item.type === 'sparepart' && item.stok < 1 ? 'opacity-60 cursor-not-allowed bg-gray-50' : 'hover:border-accent cursor-pointer group'" 
                         @click="addToCart(item)">
                        <div class="h-24 bg-gray-50 rounded-lg flex items-center justify-center mb-3 relative overflow-hidden transition-colors" :class="item.type === 'sparepart' && item.stok < 1 ? '' : 'group-hover:bg-primary/5'">
                            <template x-if="item.image">
                                <img :src="item.image" :alt="item.name" class="absolute inset-0 w-full h-full object-cover">
                            </template>
                            <template x-if="!item.image">
                                <div>
                                    <svg x-show="item.type==='jasa'" class="w-10 h-10 text-gray-300 transition-colors" :class="item.type === 'sparepart' && item.stok < 1 ? '' : 'group-hover:text-accent'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                                    <svg x-show="item.type==='sparepart'" class="w-10 h-10 text-gray-300 transition-colors" :class="item.type === 'sparepart' && item.stok < 1 ? '' : 'group-hover:text-accent'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                                </div>
                            </template>
                            
                            {{-- Badge Habis --}}
                            <div x-show="item.type === 'sparepart' && item.stok < 1" class="absolute inset-0 bg-white/60 flex items-center justify-center backdrop-blur-[1px]">
                                <span class="bg-red-500 text-white text-[10px] font-black px-2 py-1 rounded-md tracking-wider uppercase shadow-sm transform -rotate-12">HABIS</span>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <h4 class="text-xs font-bold text-gray-800 line-clamp-2 mb-1" x-text="item.name"></h4>
                            <p class="text-sm font-black text-accent" x-text="formatRupiah(item.price)"></p>
                            <p x-show="item.type === 'sparepart' && item.stok > 0" class="text-[10px] text-gray-500 mt-1 font-medium">Sisa stok: <span x-text="item.stok"></span></p>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    {{-- Right: Cart / Bill --}}
    <div class="w-full lg:w-96 bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col overflow-hidden shrink-0">
        <div class="p-4 border-b border-gray-100 bg-surface">
            {{-- Mode Tabs --}}
            <div class="flex bg-gray-100 p-1 rounded-xl mb-3">
                <button class="flex-1 px-3 py-2 text-xs font-bold rounded-lg transition-all" :class="mode === 'booking' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-primary'" @click="mode = 'booking'; walkInName = ''">
                    📋 Booking
                </button>
                <button class="flex-1 px-3 py-2 text-xs font-bold rounded-lg transition-all" :class="mode === 'walkin' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-primary'" @click="mode = 'walkin'; selectedBookingId = ''; customerName = ''; customerPlat = ''; customerKendaraan = ''">
                    🚶 Walk-in
                </button>
            </div>

            {{-- Booking Mode --}}
            <div x-show="mode === 'booking'">
                <select x-model="selectedBookingId" @change="selectBooking()" class="w-full px-3 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-accent focus:border-accent">
                    <option value="">-- Pilih Booking --</option>
                    @foreach($allBookings as $bk)
                    <option value="{{ $bk->id }}" data-name="{{ $bk->user->name ?? '-' }}" data-plat="{{ $bk->plat_nomor }}" data-kendaraan="{{ $bk->kendaraan }}" {{ $selectedBooking && $selectedBooking->id == $bk->id ? 'selected' : '' }}>
                        {{ $bk->kode_booking }} — {{ $bk->plat_nomor }} ({{ $bk->status }})
                    </option>
                    @endforeach
                </select>
                <div x-show="customerName" class="flex items-center gap-3 bg-white p-3 rounded-xl border border-accent/20 mt-3">
                    <div class="w-10 h-10 bg-accent/10 rounded-lg flex items-center justify-center text-accent font-bold">VB</div>
                    <div>
                        <h3 class="font-bold text-sm text-primary" x-text="customerName"></h3>
                        <p class="text-xs text-gray-500 font-medium" x-text="customerPlat + ' - ' + customerKendaraan"></p>
                    </div>
                </div>
            </div>

            {{-- Walk-in Mode --}}
            <div x-show="mode === 'walkin'">
                <input type="text" x-model="walkInName" placeholder="Nama pelanggan (opsional)" class="w-full px-3 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-accent focus:border-accent">                
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-3">
            <template x-for="(cartItem, index) in cart" :key="index">
                <div class="flex gap-3 items-start pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-gray-800 line-clamp-1" x-text="cartItem.name"></h4>
                        <p class="text-xs text-gray-400 mt-0.5" x-text="formatRupiah(cartItem.price)"></p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="w-6 h-6 rounded-md bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-danger/10 hover:text-danger" @click="decreaseQty(index)">-</button>
                        <span class="text-sm font-bold w-4 text-center" x-text="cartItem.qty"></span>
                        <button class="w-6 h-6 rounded-md bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-success/10 hover:text-success" @click="increaseQty(index)">+</button>
                    </div>
                    <div class="text-sm font-bold text-primary w-20 text-right" x-text="formatRupiah(cartItem.price * cartItem.qty)"></div>
                </div>
            </template>
            <div x-show="cart.length === 0" class="h-full flex flex-col items-center justify-center text-gray-400">
                <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <p class="text-sm">Keranjang kosong</p>
            </div>
        </div>

        <div class="p-4 bg-surface border-t border-gray-100">
            <div class="space-y-2 mb-4 text-sm">
                <div class="flex justify-between text-gray-500"><span>Subtotal</span><span class="font-semibold text-gray-800" x-text="formatRupiah(subtotal)"></span></div>
                <div class="flex justify-between text-gray-500"><span>Pajak (11%)</span><span class="font-semibold text-gray-800" x-text="formatRupiah(tax)"></span></div>
                <div class="flex justify-between text-lg pt-2 border-t border-gray-200 mt-2">
                    <span class="font-bold text-primary">Total Tagihan</span>
                    <span class="font-black text-accent" x-text="formatRupiah(total)"></span>
                </div>
            </div>
            <button class="w-full py-3.5 rounded-xl bg-accent text-white font-bold hover:bg-accent-hover shadow-md shadow-accent/20 transition-all flex items-center justify-center gap-2 disabled:opacity-50" :disabled="cart.length === 0 || (mode === 'booking' && !selectedBookingId) || isProcessing" @click="showConfirm = true">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span x-text="isProcessing ? 'Memproses...' : 'Proses Pembayaran'"></span>
            </button>
        </div>
    </div>

    {{-- CONFIRMATION MODAL --}}
    <div x-show="showConfirm" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4" style="display: none;">
        <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl overflow-hidden" x-transition.scale.90>
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-warning/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-xl font-black text-primary mb-2">Konfirmasi Pembayaran</h3>
                <p class="text-sm text-gray-500 mb-4">Apakah Anda yakin daftar tagihan dan total sudah sesuai? Transaksi ini tidak dapat dibatalkan setelah diproses.</p>
                <div class="bg-surface p-3 rounded-xl mb-4 text-left">
                    <p class="text-sm text-gray-600 flex justify-between">Pelanggan: <span class="font-bold text-gray-800" x-text="mode === 'booking' ? customerName : (walkInName || 'Walk-in')"></span></p>
                    <p class="text-sm text-gray-600 flex justify-between mt-1">Total Tagihan: <span class="font-bold text-accent" x-text="formatRupiah(total)"></span></p>
                </div>
                <div class="flex gap-3">
                    <button @click="showConfirm = false" class="flex-1 py-3 rounded-xl bg-gray-100 text-gray-700 font-bold transition-all hover:bg-gray-200">
                        Batal
                    </button>
                    <button @click="showConfirm = false; processPayment()" class="flex-1 py-3 rounded-xl bg-accent text-white font-bold transition-all hover:bg-accent-hover shadow-md">
                        Ya, Lanjutkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- SUCCESS MODAL --}}
    <div x-show="showSuccess" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4" style="display: none;">
        <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl overflow-hidden">
            <div class="p-8 text-center">
                <div class="w-16 h-16 bg-success/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h3 class="text-xl font-black text-primary mb-2">Pembayaran Berhasil!</h3>
                <p class="text-sm text-gray-500 mb-1">Transaksi telah diproses dan stok otomatis diperbarui.</p>
                <p class="text-2xl font-black text-accent mt-3" x-text="formatRupiah(successTotal)"></p>
            </div>
            <div class="px-6 pb-6 space-y-3">
                <a :href="invoiceUrl" target="_blank" class="w-full py-3 rounded-xl bg-primary text-white font-bold shadow-md transition-all flex items-center justify-center gap-2 hover:opacity-90">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Cetak Nota PDF
                </a>
                <button x-show="isBookingTransaction" @click="sendWhatsapp()" class="w-full py-3 rounded-xl bg-green-500 text-white font-bold shadow-md transition-all flex items-center justify-center gap-2 hover:bg-green-600">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Kirim Nota ke WhatsApp
                </button>
                <button @click="closeSuccess()" class="w-full py-3 rounded-xl bg-gray-100 text-gray-700 font-bold transition-all hover:bg-gray-200">
                    Kembali ke Kasir
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function posSystem() {
    return {
        searchQuery: '',
        activeFilter: 'all',
        isProcessing: false,
        showConfirm: false,
        showSuccess: false,
        successTotal: 0,
        transactionId: null,
        invoiceUrl: '',
        isBookingTransaction: false,
        mode: 'booking',
        walkInName: '',
        selectedBookingId: '{{ $selectedBooking ? $selectedBooking->id : '' }}',
        customerName: '{{ $selectedBooking ? $selectedBooking->user->name : '' }}',
        customerPlat: '{{ $selectedBooking ? $selectedBooking->plat_nomor : '' }}',
        customerKendaraan: '{{ $selectedBooking ? $selectedBooking->kendaraan : '' }}',
        products: [
            { id: 'j1', name: 'Servis Ringan (Tune Up)', price: 150000, type: 'jasa', itemId: null, image: null },
            { id: 'j2', name: 'Servis Besar / Turun Mesin', price: 650000, type: 'jasa', itemId: null, image: null },
            { id: 'j3', name: 'Servis CVT', price: 85000, type: 'jasa', itemId: null, image: null },
            { id: 'j4', name: 'Jasa Ganti Kampas Rem', price: 150000, type: 'jasa', itemId: null, image: null },
            { id: 'j5', name: 'Jasa Ganti Ban', price: 50000, type: 'jasa', itemId: null, image: null },
            @foreach($spareparts as $sp)
            { id: 's{{ $sp->id }}', name: @json($sp->nama), price: {{ $sp->harga_jual }}, type: 'sparepart', itemId: {{ $sp->id }}, stok: {{ $sp->stok }}, image: @json($sp->image ? asset('storage/' . $sp->image) : null) },
            @endforeach
        ],
        cart: [],
        get filteredProducts() {
            let items = this.products;
            if (this.activeFilter !== 'all') items = items.filter(p => p.type === this.activeFilter);
            if (this.searchQuery) items = items.filter(p => p.name.toLowerCase().includes(this.searchQuery.toLowerCase()));
            return items;
        },
        get subtotal() { return this.cart.reduce((t, i) => t + (i.price * i.qty), 0); },
        get tax() { return Math.round(this.subtotal * 0.11); },
        get total() { return this.subtotal + this.tax; },
        selectBooking() {
            const opt = document.querySelector(`select option[value="${this.selectedBookingId}"]`);
            if (opt) {
                this.customerName = opt.dataset.name || '';
                this.customerPlat = opt.dataset.plat || '';
                this.customerKendaraan = opt.dataset.kendaraan || '';
            } else {
                this.customerName = ''; this.customerPlat = ''; this.customerKendaraan = '';
            }
        },
        addToCart(product) {
            const existing = this.cart.find(i => i.id === product.id);
            if (existing) {
                if (product.type === 'sparepart' && existing.qty >= product.stok) {
                    alert(`Stok ${product.name} tidak mencukupi (Sisa: ${product.stok})`);
                    return;
                }
                existing.qty++;
            } else {
                if (product.type === 'sparepart' && product.stok < 1) {
                    alert(`Stok ${product.name} habis`);
                    return;
                }
                this.cart.push({ ...product, qty: 1 });
            }
        },
        increaseQty(i) {
            const item = this.cart[i];
            const product = this.products.find(p => p.id === item.id);
            if (product && product.type === 'sparepart' && item.qty >= product.stok) {
                alert(`Stok ${product.name} tidak mencukupi (Sisa: ${product.stok})`);
                return;
            }
            this.cart[i].qty++;
        },
        decreaseQty(i) { if (this.cart[i].qty > 1) this.cart[i].qty--; else this.cart.splice(i, 1); },
        formatRupiah(n) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(n); },
        async processPayment() {
            if (this.mode === 'booking' && !this.selectedBookingId) { alert('Pilih booking terlebih dahulu!'); return; }
            if (this.cart.length === 0) return;
            this.isProcessing = true;
            try {
                const items = this.cart.map(i => ({ item_type: i.type, item_id: i.itemId, item_name: i.name, qty: i.qty, price: i.price }));
                const payload = { items };
                if (this.mode === 'booking') {
                    payload.booking_id = this.selectedBookingId;
                } else {
                    payload.customer_name = this.walkInName || 'Walk-in';
                }
                const res = await fetch('{{ route("admin.pos.store") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                if (data.success) {
                    this.transactionId = data.transaction_id;
                    this.successTotal = data.total;
                    this.invoiceUrl = '/admin/pos/invoice/' + data.transaction_id;
                    this.isBookingTransaction = this.mode === 'booking';
                    this.showSuccess = true;
                } else { alert('Gagal: ' + (data.message || 'Terjadi kesalahan')); }
            } catch (e) { alert('Error: ' + e.message); }
            this.isProcessing = false;
        },
        async sendWhatsapp() {
            try {
                const res = await fetch('/admin/pos/whatsapp/' + this.transactionId);
                const data = await res.json();
                if (data.url) {
                    window.open(data.url, '_blank');
                } else {
                    alert('Nomor WhatsApp pelanggan tidak tersedia.');
                }
            } catch (e) { alert('Gagal: ' + e.message); }
        },
        closeSuccess() {
            window.location.href = '{{ route("admin.pos.index") }}';
        }
    }
}
</script>
@endpush

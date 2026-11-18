<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kasir POS - KopiPOS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-100 text-stone-800 antialiased h-screen flex flex-col overflow-hidden"
      x-data="posApp()"
      x-init="initData()">

    <!-- Topbar Kasir -->
    <header class="h-16 bg-stone-900 text-white flex items-center justify-between px-4 sm:px-6 shrink-0 z-20">
        <div class="flex items-center space-x-3">
            <div class="w-9 h-9 rounded-xl bg-amber-600 flex items-center justify-center font-bold text-white shadow-md shadow-amber-600/30">
                ☕
            </div>
            <div>
                <h1 class="font-bold text-sm sm:text-base tracking-wide flex items-center space-x-2">
                    <span>KopiPOS</span>
                    <span class="text-[10px] font-semibold bg-amber-600/30 text-amber-300 border border-amber-500/40 px-2 py-0.5 rounded-full">KASIR</span>
                </h1>
                <p class="text-xs text-stone-400 hidden sm:block">Sistem Transaksi Langsung</p>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('transactions.index') }}"
               class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-stone-800 hover:bg-stone-700 text-stone-200 text-xs font-semibold border border-stone-700 transition">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Riwayat Transaksi</span>
            </a>

            @if (Auth::user()->role === 'admin')
                <a href="{{ route('dashboard') }}"
                   class="hidden sm:inline-flex items-center space-x-1 px-3 py-1.5 rounded-lg bg-stone-800 hover:bg-stone-700 text-stone-200 text-xs font-semibold border border-stone-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Dashboard Admin</span>
                </a>
            @endif

            <div class="text-right hidden sm:block">
                <div class="text-xs font-semibold text-white">{{ Auth::user()->name }}</div>
                <div class="text-[11px] text-stone-400 capitalize">{{ Auth::user()->role }}</div>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="p-2 rounded-lg bg-rose-950/60 hover:bg-rose-900 border border-rose-800 text-rose-300 text-xs transition"
                        title="Keluar">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </header>

    <!-- Main Workspace (Grid 2 Kolom) -->
    <div class="flex-1 flex flex-col lg:flex-row overflow-hidden">

        <!-- SISI KIRI: Katalog & Produk (Flex-1) -->
        <div class="flex-1 flex flex-col bg-stone-100 overflow-hidden border-r border-stone-200">
            <!-- Filter Bar -->
            <div class="p-4 bg-white border-b border-stone-200 space-y-3 shrink-0">
                <!-- Search Box -->
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-stone-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text"
                           x-model="search"
                           placeholder="Cari menu kopi, makanan, snack..."
                           class="w-full pl-10 pr-4 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:outline-hidden focus:bg-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition">
                </div>

                <!-- Category Pills -->
                <div class="flex items-center space-x-2 overflow-x-auto pb-1 scrollbar-none">
                    <button type="button"
                            @click="selectedCategory = null"
                            :class="selectedCategory === null ? 'bg-amber-600 text-white shadow-xs' : 'bg-stone-100 text-stone-600 hover:bg-stone-200'"
                            class="px-4 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition cursor-pointer">
                        Semua Menu (<span x-text="products.length"></span>)
                    </button>
                    @foreach ($categories as $cat)
                        <button type="button"
                                @click="selectedCategory = {{ $cat->id }}"
                                :class="selectedCategory === {{ $cat->id }} ? 'bg-amber-600 text-white shadow-xs' : 'bg-stone-100 text-stone-600 hover:bg-stone-200'"
                                class="px-4 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition cursor-pointer">
                            {{ $cat->nama }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Products Grid Area -->
            <div class="flex-1 overflow-y-auto p-4 sm:p-6">
                <template x-if="filteredProducts.length === 0">
                    <div class="h-64 flex flex-col items-center justify-center text-stone-400 text-sm">
                        <span class="text-3xl mb-2">🔍</span>
                        <p>Menu tidak ditemukan</p>
                    </div>
                </template>

                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
                    <template x-for="p in filteredProducts" :key="p.id">
                        <div @click="addToCart(p)"
                             :class="p.stok <= 0 ? 'opacity-50 cursor-not-allowed' : 'hover:border-amber-500 hover:shadow-md cursor-pointer active:scale-98'"
                             class="bg-white rounded-2xl border border-stone-200 p-3 flex flex-col transition duration-150 select-none group">
                            <!-- Gambar -->
                            <div class="relative w-full aspect-square rounded-xl bg-stone-100 overflow-hidden mb-3 border border-stone-100">
                                <template x-if="p.image_url">
                                    <img :src="p.image_url" :alt="p.nama" class="w-full h-full object-cover group-hover:scale-105 transition duration-200">
                                </template>
                                <template x-if="!p.image_url">
                                    <div class="w-full h-full flex items-center justify-center text-3xl text-stone-300">
                                        ☕
                                    </div>
                                </template>
                                <!-- Tag Stok -->
                                <span class="absolute top-2 right-2 text-[10px] font-bold px-2 py-0.5 rounded-full shadow-xs"
                                      :class="p.stok <= 0 ? 'bg-rose-600 text-white' : (p.stok <= 10 ? 'bg-amber-500 text-white' : 'bg-stone-900/70 text-white backdrop-blur-xs')"
                                      x-text="p.stok <= 0 ? 'Habis' : 'Stok: ' + p.stok">
                                </span>
                            </div>

                            <!-- Info -->
                            <div class="flex-1 flex flex-col justify-between">
                                <div>
                                    <p class="text-[11px] text-stone-400 uppercase tracking-wider font-semibold" x-text="p.category_name"></p>
                                    <h3 class="font-bold text-stone-800 text-sm line-clamp-2 leading-tight group-hover:text-amber-600 transition" x-text="p.nama"></h3>
                                </div>
                                <div class="mt-2 pt-2 border-t border-stone-100 flex items-center justify-between">
                                    <span class="text-sm font-extrabold text-stone-900" x-text="formatRupiah(p.harga)"></span>
                                    <span class="w-6 h-6 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center text-xs font-bold group-hover:bg-amber-600 group-hover:text-white transition">
                                        +
                                    </span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- SISI KANAN: Keranjang & Pembayaran (w-full lg:w-96 xl:w-[420px]) -->
        <div class="w-full lg:w-96 xl:w-[420px] bg-white flex flex-col border-t lg:border-t-0 shrink-0">
            <!-- Header Keranjang -->
            <div class="p-4 border-b border-stone-200 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <h2 class="font-bold text-stone-800 text-base">Pesanan Pelanggan</h2>
                    <span class="text-xs bg-stone-100 text-stone-600 font-bold px-2 py-0.5 rounded-full" x-text="totalItemsCount() + ' item'"></span>
                </div>
                <button type="button"
                        @click="clearCart()"
                        x-show="cart.length > 0"
                        class="text-xs font-semibold text-rose-500 hover:text-rose-700 transition cursor-pointer">
                    Kosongkan
                </button>
            </div>

            <!-- Daftar Item Keranjang -->
            <div class="flex-1 overflow-y-auto p-4 space-y-3 divide-y divide-stone-100">
                <template x-if="cart.length === 0">
                    <div class="h-64 flex flex-col items-center justify-center text-stone-400 text-sm">
                        <svg class="w-12 h-12 text-stone-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <p class="font-medium text-stone-500">Keranjang masih kosong</p>
                        <p class="text-xs text-stone-400 mt-1">Klik item di katalog untuk menambahkan</p>
                    </div>
                </template>

                <template x-for="item in cart" :key="item.id">
                    <div class="pt-3 first:pt-0 flex items-center justify-between space-x-3">
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-stone-800 text-sm truncate" x-text="item.nama"></h4>
                            <p class="text-xs text-stone-400" x-text="formatRupiah(item.harga) + ' × ' + item.kuantitas"></p>
                            <p class="text-xs font-bold text-amber-600" x-text="formatRupiah(item.harga * item.kuantitas)"></p>
                        </div>

                        <!-- Stepper Qty -->
                        <div class="flex items-center space-x-1 bg-stone-100 rounded-xl p-1 border border-stone-200">
                            <button type="button"
                                    @click="decreaseQty(item)"
                                    class="w-7 h-7 rounded-lg bg-white hover:bg-stone-200 text-stone-700 flex items-center justify-center text-xs font-bold shadow-2xs transition cursor-pointer">
                                -
                            </button>
                            <span class="w-8 text-center text-xs font-bold text-stone-800" x-text="item.kuantitas"></span>
                            <button type="button"
                                    @click="increaseQty(item)"
                                    class="w-7 h-7 rounded-lg bg-white hover:bg-stone-200 text-stone-700 flex items-center justify-center text-xs font-bold shadow-2xs transition cursor-pointer">
                                +
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Rangkuman & Pembayaran -->
            <div class="p-4 bg-stone-50 border-t border-stone-200 space-y-3 shrink-0">
                <!-- Total -->
                <div class="flex items-center justify-between text-base">
                    <span class="text-stone-600 font-semibold">Total Tagihan</span>
                    <span class="text-xl font-black text-stone-900" x-text="formatRupiah(totalBelanja())"></span>
                </div>

                <!-- Tombol Buka Modal Bayar -->
                <button type="button"
                        @click="openPaymentModal()"
                        :disabled="cart.length === 0"
                        class="w-full py-3.5 px-4 bg-amber-600 hover:bg-amber-500 disabled:bg-stone-300 disabled:cursor-not-allowed text-white font-bold text-sm rounded-xl shadow-md shadow-amber-600/30 transition cursor-pointer flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>Bayar Sekarang (F8)</span>
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL PEMBAYARAN -->
    <div x-show="showPaymentModal"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto bg-stone-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div @click.away="if (!processing) showPaymentModal = false"
             class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl space-y-5 border border-stone-200">

            <div class="flex items-center justify-between border-b border-stone-100 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-stone-900">Pembayaran Pesanan</h3>
                    <p class="text-xs text-stone-500">Pilih metode dan selesaikan transaksi</p>
                </div>
                <button @click="showPaymentModal = false" :disabled="processing" class="text-stone-400 hover:text-stone-600 text-sm font-bold">✕</button>
            </div>

            <!-- Error Banner -->
            <template x-if="errorMessage">
                <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold" x-text="errorMessage"></div>
            </template>

            <!-- Total Yang Harus Dibayar -->
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 text-center">
                <div class="text-xs uppercase tracking-wider text-amber-700 font-bold">Total Pembayaran</div>
                <div class="text-3xl font-black text-amber-900 mt-1" x-text="formatRupiah(totalBelanja())"></div>
            </div>

            <!-- Pilihan Metode Pembayaran -->
            <div>
                <label class="block text-xs font-semibold text-stone-600 uppercase tracking-wider mb-2">Metode Pembayaran</label>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button"
                            @click="paymentMethod = 'tunai'"
                            :class="paymentMethod === 'tunai' ? 'border-amber-600 bg-amber-50/50 text-amber-900 ring-2 ring-amber-500/20' : 'border-stone-200 text-stone-700 hover:bg-stone-50'"
                            class="p-3.5 border rounded-2xl flex flex-col items-center justify-center space-y-1 font-bold text-sm transition cursor-pointer">
                        <span class="text-2xl">💵</span>
                        <span>Tunai (Cash)</span>
                    </button>
                    <button type="button"
                            @click="paymentMethod = 'qris'"
                            :class="paymentMethod === 'qris' ? 'border-amber-600 bg-amber-50/50 text-amber-900 ring-2 ring-amber-500/20' : 'border-stone-200 text-stone-700 hover:bg-stone-50'"
                            class="p-3.5 border rounded-2xl flex flex-col items-center justify-center space-y-1 font-bold text-sm transition cursor-pointer">
                        <span class="text-2xl">📱</span>
                        <span>QRIS Statis/Dinamis</span>
                    </button>
                </div>
            </div>

            <!-- Input Uang Tunai (Hanya jika Tunai) -->
            <template x-if="paymentMethod === 'tunai'">
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-stone-600 uppercase tracking-wider mb-2">Uang Diterima (Rp)</label>
                        <input type="number"
                               x-model.number="cashReceived"
                               placeholder="0"
                               step="1000"
                               class="w-full px-4 py-3 text-lg font-bold rounded-xl border border-stone-300 text-stone-900 focus:outline-hidden focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                    </div>

                    <!-- Quick Cash Buttons -->
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" @click="cashReceived = totalBelanja()" class="py-1.5 px-2 bg-stone-100 hover:bg-stone-200 rounded-lg text-xs font-bold text-stone-700">Uang Pas</button>
                        <button type="button" @click="cashReceived = 50000" class="py-1.5 px-2 bg-stone-100 hover:bg-stone-200 rounded-lg text-xs font-bold text-stone-700">50.000</button>
                        <button type="button" @click="cashReceived = 100000" class="py-1.5 px-2 bg-stone-100 hover:bg-stone-200 rounded-lg text-xs font-bold text-stone-700">100.000</button>
                    </div>

                    <!-- Kembalian -->
                    <div class="p-3 rounded-xl bg-stone-100 flex items-center justify-between text-sm">
                        <span class="font-semibold text-stone-600">Kembalian:</span>
                        <span class="font-black text-stone-900"
                              :class="calculateChange() < 0 ? 'text-rose-600' : 'text-emerald-600'"
                              x-text="calculateChange() < 0 ? 'Uang kurang ' + formatRupiah(Math.abs(calculateChange())) : formatRupiah(calculateChange())">
                        </span>
                    </div>
                </div>
            </template>

            <!-- Info QRIS -->
            <template x-if="paymentMethod === 'qris'">
                <div class="p-4 rounded-2xl bg-indigo-50 border border-indigo-200 text-center space-y-2">
                    <p class="text-xs text-indigo-700 font-semibold">Tunjukkan QRIS kasir kepada pelanggan.</p>
                    <p class="text-[11px] text-indigo-500">Pastikan notifikasi dana masuk telah diterima sebelum menyelesaikan transaksi.</p>
                </div>
            </template>

            <!-- Tombol Konfirmasi -->
            <div class="pt-2">
                <button type="button"
                        @click="submitTransaction()"
                        :disabled="processing || (paymentMethod === 'tunai' && cashReceived < totalBelanja())"
                        class="w-full py-3.5 px-4 bg-emerald-600 hover:bg-emerald-500 disabled:bg-stone-300 disabled:cursor-not-allowed text-white font-bold text-sm rounded-xl shadow-md shadow-emerald-600/30 transition flex items-center justify-center space-x-2 cursor-pointer">
                    <span x-show="!processing">Konfirmasi & Simpan Transaksi</span>
                    <span x-show="processing">Sedang Memproses...</span>
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL SUKSES TRANSAKSI -->
    <div x-show="showSuccessModal"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto bg-stone-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-sm w-full p-6 text-center space-y-4 shadow-2xl border border-stone-200">
            <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-3xl mx-auto">
                ✓
            </div>
            <h3 class="text-lg font-bold text-stone-900">Transaksi Berhasil!</h3>
            <p class="text-xs text-stone-500 font-mono" x-text="completedTransaction?.nomor_invoice"></p>

            <div class="bg-stone-50 rounded-2xl p-4 text-left text-xs space-y-2 border border-stone-100">
                <div class="flex justify-between">
                    <span class="text-stone-500">Metode</span>
                    <span class="font-bold text-stone-800 uppercase" x-text="completedTransaction?.metode_pembayaran"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-stone-500">Total Tagihan</span>
                    <span class="font-bold text-stone-800" x-text="formatRupiah(completedTransaction?.total_harga || 0)"></span>
                </div>
                <template x-if="completedTransaction?.metode_pembayaran === 'tunai'">
                    <div>
                        <div class="flex justify-between text-stone-500">
                            <span>Uang Diterima</span>
                            <span class="font-bold text-stone-800" x-text="formatRupiah(completedTransaction?.uang_diterima || 0)"></span>
                        </div>
                        <div class="flex justify-between text-emerald-700 font-bold pt-1 border-t border-stone-200 mt-1">
                            <span>Kembalian</span>
                            <span x-text="formatRupiah(completedTransaction?.kembalian || 0)"></span>
                        </div>
                    </div>
                </template>
            </div>

            <div class="space-y-2 pt-2">
                <a :href="completedTransaction?.receipt_url"
                   target="_blank"
                   class="block w-full py-3 px-4 bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs rounded-xl shadow-xs transition">
                    Cetak Struk Belanja
                </a>
                <button type="button"
                        @click="showSuccessModal = false"
                        class="w-full py-2.5 px-4 bg-stone-100 hover:bg-stone-200 text-stone-700 font-semibold text-xs rounded-xl transition cursor-pointer">
                    Transaksi Baru
                </button>
            </div>
        </div>
    </div>

    <!-- Alpine State Machine Logic -->
    <script>
        function posApp() {
            return {
                products: @json($productsJson),
                cart: [],
                search: '',
                selectedCategory: null,

                // Modal state
                showPaymentModal: false,
                showSuccessModal: false,
                paymentMethod: 'tunai',
                cashReceived: 0,
                errorMessage: '',
                processing: false,
                completedTransaction: null,

                initData() {
                    // Hotkey F8 untuk bayar
                    window.addEventListener('keydown', (e) => {
                        if (e.key === 'F8') {
                            e.preventDefault();
                            if (this.cart.length > 0) this.openPaymentModal();
                        }
                    });
                },

                get filteredProducts() {
                    return this.products.filter(p => {
                        const matchCat = this.selectedCategory === null || p.category_id === this.selectedCategory;
                        const matchSearch = this.search.trim() === '' || p.nama.toLowerCase().includes(this.search.toLowerCase());
                        return matchCat && matchSearch;
                    });
                },

                addToCart(product) {
                    if (product.stok <= 0) {
                        alert('Stok menu ini habis!');
                        return;
                    }

                    const existing = this.cart.find(item => item.id === product.id);
                    if (existing) {
                        if (existing.kuantitas + 1 > product.stok) {
                            alert('Kuantitas melebihi stok yang tersedia (' + product.stok + ')');
                            return;
                        }
                        existing.kuantitas++;
                    } else {
                        this.cart.push({
                            id: product.id,
                            nama: product.nama,
                            harga: product.harga,
                            kuantitas: 1,
                            maxStok: product.stok
                        });
                    }
                },

                increaseQty(item) {
                    const prod = this.products.find(p => p.id === item.id);
                    if (item.kuantitas + 1 > (prod ? prod.stok : item.maxStok)) {
                        alert('Stok tidak mencukupi.');
                        return;
                    }
                    item.kuantitas++;
                },

                decreaseQty(item) {
                    item.kuantitas--;
                    if (item.kuantitas <= 0) {
                        this.cart = this.cart.filter(c => c.id !== item.id);
                    }
                },

                clearCart() {
                    if (confirm('Kosongkan semua item di keranjang?')) {
                        this.cart = [];
                    }
                },

                totalBelanja() {
                    return this.cart.reduce((sum, item) => sum + (item.harga * item.kuantitas), 0);
                },

                totalItemsCount() {
                    return this.cart.reduce((sum, item) => sum + item.kuantitas, 0);
                },

                formatRupiah(angka) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(angka);
                },

                openPaymentModal() {
                    this.errorMessage = '';
                    this.cashReceived = this.totalBelanja();
                    this.showPaymentModal = true;
                },

                calculateChange() {
                    return this.cashReceived - this.totalBelanja();
                },

                async submitTransaction() {
                    this.errorMessage = '';
                    this.processing = true;

                    const payload = {
                        metode_pembayaran: this.paymentMethod,
                        uang_diterima: this.paymentMethod === 'tunai' ? this.cashReceived : this.totalBelanja(),
                        items: this.cart.map(i => ({ id: i.id, kuantitas: i.kuantitas }))
                    };

                    try {
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const res = await fetch("{{ route('pos.store') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': token
                            },
                            body: JSON.stringify(payload)
                        });

                        const data = await res.json();

                        if (!res.ok) {
                            throw new Error(data.pesan || 'Terjadi kesalahan saat memproses transaksi.');
                        }

                        // Update stok lokal di UI
                        this.cart.forEach(cartItem => {
                            const p = this.products.find(prod => prod.id === cartItem.id);
                            if (p) p.stok -= cartItem.kuantitas;
                        });

                        this.completedTransaction = data.data;
                        this.cart = [];
                        this.showPaymentModal = false;
                        this.showSuccessModal = true;
                    } catch (err) {
                        this.errorMessage = err.message;
                    } finally {
                        this.processing = false;
                    }
                }
            };
        }
    </script>
</body>
</html>

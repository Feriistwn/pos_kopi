@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Ringkasan Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Penjualan Hari Ini -->
        <div class="bg-white rounded-2xl p-5 border border-stone-200 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-stone-500">Penjualan Hari Ini</span>
                <span class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                    Rp
                </span>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-bold text-stone-800">
                    Rp {{ number_format($totalPenjualanHariIni, 0, ',', '.') }}
                </div>
                <p class="text-xs text-stone-500 mt-1">Transaksi berstatus selesai</p>
            </div>
        </div>

        <!-- Card 2: Pesanan Hari Ini -->
        <div class="bg-white rounded-2xl p-5 border border-stone-200 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-stone-500">Pesanan Hari Ini</span>
                <span class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </span>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-bold text-stone-800">
                    {{ number_format($totalPesananHariIni, 0, ',', '.') }}
                </div>
                <p class="text-xs text-stone-500 mt-1">Struk sukses tercatat</p>
            </div>
        </div>

        <!-- Card 3: Total Produk -->
        <div class="bg-white rounded-2xl p-5 border border-stone-200 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-stone-500">Total Menu</span>
                <span class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </span>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-bold text-stone-800">{{ $totalProduk }}</div>
                <p class="text-xs text-stone-500 mt-1">Item aktif di menu</p>
            </div>
        </div>

        <!-- Card 4: Total Kategori -->
        <div class="bg-white rounded-2xl p-5 border border-stone-200 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-stone-500">Total Kategori</span>
                <span class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </span>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-bold text-stone-800">{{ $totalKategori }}</div>
                <p class="text-xs text-stone-500 mt-1">Kelompok produk</p>
            </div>
        </div>
    </div>

    <!-- Tables Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Stok Menipis -->
        <div class="bg-white rounded-2xl border border-stone-200 p-5 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="font-bold text-stone-800 text-base">Peringatan Stok Menipis</h2>
                    <p class="text-xs text-stone-500">Produk dengan stok 10 item atau kurang</p>
                </div>
                <a href="{{ route('products.index') }}" class="text-xs font-semibold text-amber-600 hover:text-amber-700">Lihat Semua →</a>
            </div>

            @if ($produkStokMenipis->isEmpty())
                <div class="py-8 text-center text-stone-400 text-sm">
                    Stok seluruh menu aman dan tersedia.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="text-xs text-stone-400 uppercase border-b border-stone-100">
                            <tr>
                                <th class="pb-3 font-semibold">Produk</th>
                                <th class="pb-3 font-semibold">Kategori</th>
                                <th class="pb-3 font-semibold text-right">Sisa Stok</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @foreach ($produkStokMenipis as $item)
                                <tr class="hover:bg-stone-50">
                                    <td class="py-3 font-medium text-stone-800">{{ $item->nama }}</td>
                                    <td class="py-3 text-stone-500 text-xs">{{ $item->category->nama ?? '-' }}</td>
                                    <td class="py-3 text-right">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $item->stok == 0 ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700' }}">
                                            {{ $item->stok }} porsi
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Transaksi Terbaru -->
        <div class="bg-white rounded-2xl border border-stone-200 p-5 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="font-bold text-stone-800 text-base">Transaksi Terbaru</h2>
                    <p class="text-xs text-stone-500">5 transaksi terakhir yang tercatat</p>
                </div>
            </div>

            @if ($transaksiTerbaru->isEmpty())
                <div class="py-8 text-center text-stone-400 text-sm">
                    Belum ada transaksi hari ini. Kasir siap digunakan di Fase 3!
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="text-xs text-stone-400 uppercase border-b border-stone-100">
                            <tr>
                                <th class="pb-3 font-semibold">Invoice</th>
                                <th class="pb-3 font-semibold">Metode</th>
                                <th class="pb-3 font-semibold text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @foreach ($transaksiTerbaru as $trx)
                                <tr class="hover:bg-stone-50">
                                    <td class="py-3">
                                        <div class="font-medium text-stone-800 font-mono text-xs">{{ $trx->nomor_invoice }}</div>
                                        <div class="text-[11px] text-stone-400">{{ $trx->created_at->format('H:i') }} • {{ $trx->user->name ?? 'Kasir' }}</div>
                                    </td>
                                    <td class="py-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold uppercase {{ $trx->metode_pembayaran === 'qris' ? 'bg-indigo-100 text-indigo-700' : 'bg-stone-100 text-stone-700' }}">
                                            {{ $trx->metode_pembayaran }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-right font-bold text-stone-800">
                                        Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

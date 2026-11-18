@extends('layouts.admin')

@section('title', 'Riwayat Transaksi')
@section('page_title', 'Laporan & Riwayat Transaksi')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-stone-800">Daftar Transaksi Kasir</h2>
            <p class="text-xs text-stone-500">Pantau seluruh catatan penjualan, rincian produk, dan struk belanja</p>
        </div>
        <a href="{{ route('pos.index') }}"
           class="inline-flex items-center space-x-2 px-4 py-2.5 bg-amber-600 hover:bg-amber-500 text-white text-sm font-semibold rounded-xl shadow-xs transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Buka Kasir POS</span>
        </a>
    </div>

    <!-- Ringkasan Filter Terpilih -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-stone-200 shadow-xs">
            <div class="text-xs font-semibold uppercase tracking-wider text-stone-500">Total Pendapatan</div>
            <div class="text-2xl font-black text-stone-800 mt-2">
                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
            </div>
            <p class="text-xs text-stone-400 mt-1">Sesuai rentang tanggal filter</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-stone-200 shadow-xs">
            <div class="text-xs font-semibold uppercase tracking-wider text-stone-500">Jumlah Transaksi</div>
            <div class="text-2xl font-black text-stone-800 mt-2">
                {{ number_format($totalTransaksi, 0, ',', '.') }}
            </div>
            <p class="text-xs text-stone-400 mt-1">Struk berhasil diselesaikan</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-stone-200 shadow-xs">
            <div class="text-xs font-semibold uppercase tracking-wider text-emerald-700">Total Tunai (Cash)</div>
            <div class="text-2xl font-black text-emerald-700 mt-2">
                Rp {{ number_format($totalTunai, 0, ',', '.') }}
            </div>
            <p class="text-xs text-stone-400 mt-1">Pembayaran uang tunai</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-stone-200 shadow-xs">
            <div class="text-xs font-semibold uppercase tracking-wider text-indigo-700">Total QRIS</div>
            <div class="text-2xl font-black text-indigo-700 mt-2">
                Rp {{ number_format($totalQris, 0, ',', '.') }}
            </div>
            <p class="text-xs text-stone-400 mt-1">Pembayaran non-tunai</p>
        </div>
    </div>

    <!-- Filter Form Card -->
    <div class="bg-white rounded-2xl border border-stone-200 p-5 shadow-xs">
        <form method="GET" action="{{ route('transactions.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Dari Tanggal -->
            <div>
                <label class="block text-[11px] font-bold text-stone-500 uppercase tracking-wider mb-1">Mulai Tanggal</label>
                <input type="date"
                       name="tanggal_mulai"
                       value="{{ $startDate }}"
                       class="w-full px-3 py-2 rounded-xl border border-stone-300 text-stone-800 text-xs focus:outline-hidden focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
            </div>

            <!-- Sampai Tanggal -->
            <div>
                <label class="block text-[11px] font-bold text-stone-500 uppercase tracking-wider mb-1">Sampai Tanggal</label>
                <input type="date"
                       name="tanggal_selesai"
                       value="{{ $endDate }}"
                       class="w-full px-3 py-2 rounded-xl border border-stone-300 text-stone-800 text-xs focus:outline-hidden focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
            </div>

            <!-- Kasir -->
            <div>
                <label class="block text-[11px] font-bold text-stone-500 uppercase tracking-wider mb-1">Kasir</label>
                <select name="kasir_id"
                        class="w-full px-3 py-2 rounded-xl border border-stone-300 text-stone-800 text-xs focus:outline-hidden focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                    <option value="">Semua Kasir</option>
                    @foreach ($kasirList as $k)
                        <option value="{{ $k->id }}" {{ $kasirId == $k->id ? 'selected' : '' }}>
                            {{ $k->name }} ({{ $k->role }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Metode -->
            <div>
                <label class="block text-[11px] font-bold text-stone-500 uppercase tracking-wider mb-1">Metode</label>
                <select name="metode"
                        class="w-full px-3 py-2 rounded-xl border border-stone-300 text-stone-800 text-xs focus:outline-hidden focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                    <option value="">Semua Metode</option>
                    <option value="tunai" {{ $metode === 'tunai' ? 'selected' : '' }}>Tunai</option>
                    <option value="qris" {{ $metode === 'qris' ? 'selected' : '' }}>QRIS</option>
                </select>
            </div>

            <!-- Cari & Tombol -->
            <div>
                <label class="block text-[11px] font-bold text-stone-500 uppercase tracking-wider mb-1">Invoice</label>
                <div class="flex space-x-2">
                    <input type="text"
                           name="cari"
                           value="{{ $cari }}"
                           placeholder="INV-..."
                           class="w-full px-3 py-2 rounded-xl border border-stone-300 text-stone-800 text-xs focus:outline-hidden focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                    <button type="submit"
                            class="px-4 py-2 bg-stone-800 hover:bg-stone-700 text-white text-xs font-bold rounded-xl transition">
                        Cari
                    </button>
                    @if ($cari || $kasirId || $metode || $startDate !== Carbon\Carbon::today()->toDateString() || $endDate !== Carbon\Carbon::today()->toDateString())
                        <a href="{{ route('transactions.index') }}"
                           class="px-3 py-2 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold rounded-xl transition flex items-center justify-center"
                           title="Reset Filter">
                            ↺
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Tabel Transaksi -->
    <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs">
        <table class="w-full text-left text-sm">
            <thead class="bg-stone-50 text-xs text-stone-500 uppercase border-b border-stone-200">
                <tr>
                    <th class="px-6 py-3 font-semibold">Nomor Invoice</th>
                    <th class="px-6 py-3 font-semibold">Waktu</th>
                    <th class="px-6 py-3 font-semibold">Kasir</th>
                    <th class="px-6 py-3 font-semibold">Metode</th>
                    <th class="px-6 py-3 font-semibold text-center">Item</th>
                    <th class="px-6 py-3 font-semibold text-right">Total</th>
                    <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($transactions as $trx)
                    <tr class="hover:bg-stone-50/70 transition">
                        <td class="px-6 py-4 font-mono font-bold text-amber-700 text-xs">
                            {{ $trx->nomor_invoice }}
                        </td>
                        <td class="px-6 py-4 text-xs text-stone-600">
                            <div>{{ $trx->created_at->format('d/m/Y') }}</div>
                            <div class="text-[11px] text-stone-400">{{ $trx->created_at->format('H:i') }} WIB</div>
                        </td>
                        <td class="px-6 py-4 text-xs text-stone-800 font-medium">
                            {{ $trx->user->name ?? 'Kasir' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase {{ $trx->metode_pembayaran === 'qris' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                {{ $trx->metode_pembayaran }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center text-xs text-stone-600">
                            {{ $trx->details->sum('kuantitas') }} porsi
                        </td>
                        <td class="px-6 py-4 text-right font-black text-stone-900 text-sm">
                            Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('transactions.show', $trx) }}"
                                   class="px-2.5 py-1.5 rounded-lg bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-semibold transition"
                                   title="Lihat Rincian">
                                    Detail
                                </a>
                                <a href="{{ route('pos.receipt', $trx) }}"
                                   target="_blank"
                                   class="px-2.5 py-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 text-xs font-semibold transition"
                                   title="Cetak Ulang Struk">
                                    🖨️ Struk
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-stone-400">
                            Tidak ada data transaksi yang sesuai dengan filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($transactions->hasPages())
        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    @endif
</div>
@endsection

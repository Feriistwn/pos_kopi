@extends('layouts.admin')

@section('title', 'Detail Transaksi ' . $transaction->nomor_invoice)
@section('page_title', 'Rincian Transaksi')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('transactions.index') }}"
           class="inline-flex items-center space-x-2 text-xs font-semibold text-stone-600 hover:text-stone-900 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali ke Riwayat Transaksi</span>
        </a>

        <a href="{{ route('pos.receipt', $transaction) }}"
           target="_blank"
           class="inline-flex items-center space-x-2 px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold rounded-xl shadow-xs transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            <span>Cetak Struk Belanja</span>
        </a>
    </div>

    <!-- Info Card -->
    <div class="bg-white rounded-2xl border border-stone-200 p-6 sm:p-8 shadow-xs space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-stone-100 pb-5 gap-3">
            <div>
                <span class="text-xs font-semibold text-stone-400 uppercase tracking-wider">Nomor Faktur / Invoice</span>
                <h2 class="text-2xl font-black text-amber-700 font-mono">{{ $transaction->nomor_invoice }}</h2>
            </div>
            <div class="sm:text-right">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase {{ $transaction->status === 'selesai' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                    Status: {{ $transaction->status }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
            <div>
                <p class="text-stone-400 uppercase font-semibold">Tanggal & Jam</p>
                <p class="font-bold text-stone-800 mt-1">{{ $transaction->created_at->format('d M Y, H:i') }} WIB</p>
            </div>
            <div>
                <p class="text-stone-400 uppercase font-semibold">Petugas Kasir</p>
                <p class="font-bold text-stone-800 mt-1">{{ $transaction->user->name ?? 'Kasir' }}</p>
            </div>
            <div>
                <p class="text-stone-400 uppercase font-semibold">Metode Pembayaran</p>
                <p class="font-bold text-stone-800 mt-1 uppercase">{{ $transaction->metode_pembayaran }}</p>
            </div>
            <div>
                <p class="text-stone-400 uppercase font-semibold">Total Item</p>
                <p class="font-bold text-stone-800 mt-1">{{ $transaction->details->sum('kuantitas') }} porsi</p>
            </div>
        </div>

        <!-- Tabel Item -->
        <div class="border border-stone-100 rounded-xl overflow-hidden">
            <table class="w-full text-left text-xs">
                <thead class="bg-stone-50 uppercase text-stone-400 font-semibold border-b border-stone-100">
                    <tr>
                        <th class="px-4 py-3">Nama Menu</th>
                        <th class="px-4 py-3 text-right">Harga Satuan</th>
                        <th class="px-4 py-3 text-center">Kuantitas</th>
                        <th class="px-4 py-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @foreach ($transaction->details as $item)
                        <tr>
                            <td class="px-4 py-3 font-medium text-stone-800">
                                <div class="flex items-center space-x-2">
                                    <span>{{ $item->nama_produk ?? ($item->product->nama ?? 'Menu Kedai Kopi') }}</span>
                                    @if (!$item->product)
                                        <span class="text-[10px] bg-stone-100 text-stone-500 px-1.5 py-0.5 rounded-sm">Item Diarsipkan/Dihapus</span>
                                    @endif
                                </div>
                                @if (isset($item->product->category))
                                    <div class="text-[10px] text-stone-400">{{ $item->product->category->nama }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right text-stone-600">
                                Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-center font-bold text-stone-800">
                                {{ $item->kuantitas }}
                            </td>
                            <td class="px-4 py-3 text-right font-bold text-stone-900">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-stone-50 border-t border-stone-200">
                    <tr>
                        <td colspan="3" class="px-4 py-3 font-bold text-right text-stone-700 text-sm">TOTAL KESELURUHAN</td>
                        <td class="px-4 py-3 font-black text-right text-base text-amber-700">
                            Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

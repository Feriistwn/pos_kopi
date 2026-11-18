@extends('layouts.admin')

@section('title', 'Produk Menu')
@section('page_title', 'Kelola Produk & Menu')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-stone-800">Daftar Produk</h2>
            <p class="text-xs text-stone-500">Kelola harga, stok, dan gambar menu kedai kopi</p>
        </div>
        <a href="{{ route('products.create') }}"
           class="inline-flex items-center space-x-2 px-4 py-2.5 bg-amber-600 hover:bg-amber-500 text-white text-sm font-semibold rounded-xl shadow-xs transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Tambah Produk</span>
        </a>
    </div>

    <!-- Filter & Pencarian -->
    <div class="bg-white rounded-2xl border border-stone-200 p-4 shadow-xs">
        <form method="GET" action="{{ route('products.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <input type="text"
                       name="cari"
                       value="{{ $cari }}"
                       placeholder="Cari nama menu..."
                       class="w-full px-4 py-2 rounded-xl border border-stone-300 text-stone-800 placeholder-stone-400 focus:outline-hidden focus:border-amber-500 focus:ring-1 focus:ring-amber-500 text-sm">
            </div>
            <div>
                <select name="kategori"
                        class="w-full px-4 py-2 rounded-xl border border-stone-300 text-stone-800 focus:outline-hidden focus:border-amber-500 focus:ring-1 focus:ring-amber-500 text-sm">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $kategoriId == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex space-x-2">
                <button type="submit"
                        class="px-4 py-2 bg-stone-800 hover:bg-stone-700 text-white text-sm font-semibold rounded-xl transition">
                    Filter
                </button>
                @if ($cari || $kategoriId)
                    <a href="{{ route('products.index') }}"
                       class="px-4 py-2 bg-stone-100 hover:bg-stone-200 text-stone-700 text-sm font-semibold rounded-xl transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs">
        <table class="w-full text-left text-sm">
            <thead class="bg-stone-50 text-xs text-stone-500 uppercase border-b border-stone-200">
                <tr>
                    <th class="px-6 py-3 font-semibold">Produk</th>
                    <th class="px-6 py-3 font-semibold">Kategori</th>
                    <th class="px-6 py-3 font-semibold text-right">Harga</th>
                    <th class="px-6 py-3 font-semibold text-center">Stok</th>
                    <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($products as $prod)
                    <tr class="hover:bg-stone-50/70 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                @if ($prod->image)
                                    <img src="{{ asset('storage/'.$prod->image) }}"
                                         alt="{{ $prod->nama }}"
                                         class="w-12 h-12 rounded-xl object-cover border border-stone-200">
                                @else
                                    <div class="w-12 h-12 rounded-xl bg-stone-100 border border-stone-200 flex items-center justify-center text-xl text-stone-400">
                                        ☕
                                    </div>
                                @endif
                                <div>
                                    <div class="font-semibold text-stone-800">{{ $prod->nama }}</div>
                                    <div class="text-[11px] text-stone-400">ID: #{{ $prod->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-800 border border-amber-200">
                                {{ $prod->category->nama ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-stone-800">
                            Rp {{ number_format($prod->harga, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $prod->stok <= 10 ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }}">
                                {{ $prod->stok }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('products.edit', $prod) }}"
                                   class="p-2 text-stone-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition"
                                   title="Ubah">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('products.destroy', $prod) }}"
                                      method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus produk {{ $prod->nama }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-2 text-stone-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition"
                                            title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-stone-400">
                            Tidak ada produk ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($products->hasPages())
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection

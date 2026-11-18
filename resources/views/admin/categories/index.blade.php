@extends('layouts.admin')

@section('title', 'Kategori Menu')
@section('page_title', 'Kelola Kategori Menu')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-stone-800">Daftar Kategori</h2>
            <p class="text-xs text-stone-500">Kelompokkan menu agar kasir mudah menemukan item</p>
        </div>
        <a href="{{ route('categories.create') }}"
           class="inline-flex items-center space-x-2 px-4 py-2.5 bg-amber-600 hover:bg-amber-500 text-white text-sm font-semibold rounded-xl shadow-xs transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Tambah Kategori</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs">
        <table class="w-full text-left text-sm">
            <thead class="bg-stone-50 text-xs text-stone-500 uppercase border-b border-stone-200">
                <tr>
                    <th class="px-6 py-3 font-semibold">Nama Kategori</th>
                    <th class="px-6 py-3 font-semibold text-center">Jumlah Produk</th>
                    <th class="px-6 py-3 font-semibold">Dibuat Pada</th>
                    <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($categories as $cat)
                    <tr class="hover:bg-stone-50/70 transition">
                        <td class="px-6 py-4 font-semibold text-stone-800">
                            {{ $cat->nama }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-stone-100 text-stone-700">
                                {{ $cat->products_count }} item
                            </span>
                        </td>
                        <td class="px-6 py-4 text-stone-500 text-xs">
                            {{ $cat->created_at ? $cat->created_at->format('d M Y, H:i') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('categories.edit', $cat) }}"
                                   class="p-2 text-stone-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition"
                                   title="Ubah">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('categories.destroy', $cat) }}"
                                      method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus kategori {{ $cat->nama }}?');">
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
                        <td colspan="4" class="px-6 py-12 text-center text-stone-400">
                            Belum ada kategori. Silakan tambahkan kategori baru.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($categories->hasPages())
        <div class="mt-4">
            {{ $categories->links() }}
        </div>
    @endif
</div>
@endsection

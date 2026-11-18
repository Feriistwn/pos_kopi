@extends('layouts.admin')

@section('title', 'Ubah Kategori')
@section('page_title', 'Ubah Kategori Menu')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-2xl border border-stone-200 p-6 sm:p-8 shadow-xs">
        <div class="mb-6">
            <h2 class="text-lg font-bold text-stone-800">Edit Kategori</h2>
            <p class="text-xs text-stone-500">Perbarui nama kategori yang sudah ada</p>
        </div>

        <form action="{{ route('categories.update', $category) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="nama" class="block text-xs font-semibold text-stone-600 uppercase tracking-wider mb-2">
                    Nama Kategori <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                       name="nama"
                       id="nama"
                       value="{{ old('nama', $category->nama) }}"
                       required
                       autofocus
                       class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-stone-800 focus:outline-hidden focus:border-amber-500 focus:ring-1 focus:ring-amber-500 text-sm">
                @error('nama')
                    <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-stone-100">
                <a href="{{ route('categories.index') }}"
                   class="px-4 py-2.5 rounded-xl border border-stone-300 text-stone-600 hover:bg-stone-50 text-sm font-semibold transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white text-sm font-semibold shadow-xs transition">
                    Perbarui Kategori
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

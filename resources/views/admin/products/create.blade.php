@extends('layouts.admin')

@section('title', 'Tambah Produk')
@section('page_title', 'Tambah Produk Menu')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border border-stone-200 p-6 sm:p-8 shadow-xs">
        <div class="mb-6">
            <h2 class="text-lg font-bold text-stone-800">Produk Baru</h2>
            <p class="text-xs text-stone-500">Lengkapi informasi item menu di bawah ini</p>
        </div>

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5" x-data="{ imagePreview: null }">
            @csrf

            <!-- Nama -->
            <div>
                <label for="nama" class="block text-xs font-semibold text-stone-600 uppercase tracking-wider mb-2">
                    Nama Menu <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                       name="nama"
                       id="nama"
                       value="{{ old('nama') }}"
                       required
                       autofocus
                       placeholder="Misal: Caramel Macchiato"
                       class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-stone-800 placeholder-stone-400 focus:outline-hidden focus:border-amber-500 focus:ring-1 focus:ring-amber-500 text-sm">
                @error('nama')
                    <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kategori -->
            <div>
                <label for="category_id" class="block text-xs font-semibold text-stone-600 uppercase tracking-wider mb-2">
                    Kategori <span class="text-rose-500">*</span>
                </label>
                <select name="category_id"
                        id="category_id"
                        required
                        class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-stone-800 focus:outline-hidden focus:border-amber-500 focus:ring-1 focus:ring-amber-500 text-sm">
                    <option value="">Pilih Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nama }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Harga & Stok Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="harga" class="block text-xs font-semibold text-stone-600 uppercase tracking-wider mb-2">
                        Harga (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs text-stone-400 font-bold">Rp</span>
                        <input type="number"
                               name="harga"
                               id="harga"
                               value="{{ old('harga') }}"
                               min="0"
                               step="500"
                               required
                               placeholder="25000"
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-stone-300 text-stone-800 placeholder-stone-400 focus:outline-hidden focus:border-amber-500 focus:ring-1 focus:ring-amber-500 text-sm">
                    </div>
                    @error('harga')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="stok" class="block text-xs font-semibold text-stone-600 uppercase tracking-wider mb-2">
                        Stok Awal <span class="text-rose-500">*</span>
                    </label>
                    <input type="number"
                           name="stok"
                           id="stok"
                           value="{{ old('stok', 0) }}"
                           min="0"
                           required
                           placeholder="50"
                           class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-stone-800 placeholder-stone-400 focus:outline-hidden focus:border-amber-500 focus:ring-1 focus:ring-amber-500 text-sm">
                    @error('stok')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Upload Gambar -->
            <div>
                <label class="block text-xs font-semibold text-stone-600 uppercase tracking-wider mb-2">
                    Foto Produk (Opsional)
                </label>
                <div class="flex items-center space-x-4">
                    <template x-if="imagePreview">
                        <img :src="imagePreview" class="w-16 h-16 rounded-xl object-cover border border-stone-200">
                    </template>
                    <input type="file"
                           name="image"
                           id="image"
                           accept="image/jpeg,image/png,image/jpg,image/webp"
                           @change="
                               const file = $event.target.files[0];
                               if (file) {
                                   const reader = new FileReader();
                                   reader.onload = (e) => { imagePreview = e.target.result };
                                   reader.readAsDataURL(file);
                               }
                           "
                           class="block w-full text-xs text-stone-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 cursor-pointer">
                </div>
                <p class="mt-1 text-[11px] text-stone-400">Format JPG, PNG, atau WEBP (Maksimal 2 MB)</p>
                @error('image')
                    <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-stone-100">
                <a href="{{ route('products.index') }}"
                   class="px-4 py-2.5 rounded-xl border border-stone-300 text-stone-600 hover:bg-stone-50 text-sm font-semibold transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white text-sm font-semibold shadow-xs transition">
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

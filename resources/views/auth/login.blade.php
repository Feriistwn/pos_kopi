<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - KopiPOS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-900 text-stone-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo / Brand Header -->
        <div class="text-center mb-8">
            <div class="inline-flex w-16 h-16 rounded-2xl bg-amber-600 items-center justify-center text-3xl shadow-lg shadow-amber-600/40 mb-3">
                ☕
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">KopiPOS</h1>
            <p class="text-sm text-stone-400 mt-1">Sistem Kasir & Manajemen Kedai Kopi</p>
        </div>

        <!-- Card Form -->
        <div class="bg-stone-800 border border-stone-700/80 rounded-2xl p-6 sm:p-8 shadow-xl">
            <h2 class="text-lg font-semibold text-white mb-6">Masuk ke Akun Anda</h2>

            @if (session('sukses'))
                <div class="mb-4 p-3 rounded-xl bg-emerald-950/60 border border-emerald-800 text-emerald-200 text-sm">
                    {{ session('sukses') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-3 rounded-xl bg-rose-950/60 border border-rose-800 text-rose-200 text-sm">
                    @foreach ($errors->all() as $err)
                        <p>{{ $err }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-semibold text-stone-300 uppercase tracking-wider mb-2">
                        Alamat Email
                    </label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autofocus
                           placeholder="nama@pos.com"
                           class="w-full px-4 py-2.5 bg-stone-900 border border-stone-700 rounded-xl text-white placeholder-stone-500 focus:outline-hidden focus:border-amber-500 focus:ring-1 focus:ring-amber-500 text-sm transition">
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-stone-300 uppercase tracking-wider mb-2">
                        Kata Sandi
                    </label>
                    <input type="password"
                           id="password"
                           name="password"
                           required
                           placeholder="••••••••"
                           class="w-full px-4 py-2.5 bg-stone-900 border border-stone-700 rounded-xl text-white placeholder-stone-500 focus:outline-hidden focus:border-amber-500 focus:ring-1 focus:ring-amber-500 text-sm transition">
                </div>

                <div class="flex items-center justify-between text-sm pt-1">
                    <label class="flex items-center space-x-2 cursor-pointer text-stone-300">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded-sm bg-stone-900 border-stone-700 text-amber-600 focus:ring-amber-500">
                        <span class="text-xs">Ingat saya</span>
                    </label>
                </div>

                <button type="submit"
                        class="w-full mt-2 py-3 px-4 bg-amber-600 hover:bg-amber-500 text-white font-semibold text-sm rounded-xl shadow-md shadow-amber-600/30 transition duration-150">
                    Masuk Sekarang
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-stone-700 text-xs text-stone-400 space-y-1">
                <p class="font-semibold text-stone-300">Akun Pengujian:</p>
                <p>• Admin: <span class="text-amber-400 font-mono">admin@pos.com</span> / <span class="text-amber-400 font-mono">password</span></p>
                <p>• Kasir: <span class="text-amber-400 font-mono">kasir@pos.com</span> / <span class="text-amber-400 font-mono">password</span></p>
            </div>
        </div>
    </div>
</body>
</html>

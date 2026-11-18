<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();

        $totalPenjualanHariIni = Transaction::whereDate('created_at', $today)
            ->where('status', 'selesai')
            ->sum('total_harga');

        $totalPesananHariIni = Transaction::whereDate('created_at', $today)
            ->where('status', 'selesai')
            ->count();

        $totalProduk = Product::count();
        $totalKategori = Category::count();

        $produkStokMenipis = Product::with('category')
            ->where('stok', '<=', 10)
            ->orderBy('stok', 'asc')
            ->limit(5)
            ->get();

        $transaksiTerbaru = Transaction::with(['user', 'details.product'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPenjualanHariIni',
            'totalPesananHariIni',
            'totalProduk',
            'totalKategori',
            'produkStokMenipis',
            'transaksiTerbaru'
        ));
    }
}

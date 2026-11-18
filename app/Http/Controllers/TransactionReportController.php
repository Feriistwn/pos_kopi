<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionReportController extends Controller
{
    public function index(Request $request): View
    {
        $startDate = $request->query('tanggal_mulai', Carbon::today()->toDateString());
        $endDate = $request->query('tanggal_selesai', Carbon::today()->toDateString());
        $kasirId = $request->query('kasir_id');
        $metode = $request->query('metode');
        $cari = $request->query('cari');

        $query = Transaction::with(['user', 'details.product'])
            ->when($startDate, function ($q) use ($startDate) {
                $q->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($q) use ($endDate) {
                $q->whereDate('created_at', '<=', $endDate);
            })
            ->when($kasirId, function ($q) use ($kasirId) {
                $q->where('user_id', $kasirId);
            })
            ->when($metode, function ($q) use ($metode) {
                $q->where('metode_pembayaran', $metode);
            })
            ->when($cari, function ($q) use ($cari) {
                $q->where('nomor_invoice', 'like', "%{$cari}%");
            });

        // Hitung total ringkasan untuk filter terpilih (sebelum pagination)
        $totalPendapatan = (clone $query)->where('status', 'selesai')->sum('total_harga');
        $totalTransaksi = (clone $query)->where('status', 'selesai')->count();
        $totalTunai = (clone $query)->where('status', 'selesai')->where('metode_pembayaran', 'tunai')->sum('total_harga');
        $totalQris = (clone $query)->where('status', 'selesai')->where('metode_pembayaran', 'qris')->sum('total_harga');

        $transactions = $query->latest()
            ->paginate(15)
            ->withQueryString();

        $kasirList = User::whereIn('role', ['admin', 'kasir'])->orderBy('name')->get();

        return view('admin.transactions.index', compact(
            'transactions',
            'kasirList',
            'startDate',
            'endDate',
            'kasirId',
            'metode',
            'cari',
            'totalPendapatan',
            'totalTransaksi',
            'totalTunai',
            'totalQris'
        ));
    }

    public function show(Transaction $transaction): View
    {
        $transaction->load(['user', 'details.product.category']);

        return view('admin.transactions.show', compact('transaction'));
    }
}

<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionReportController;
use Illuminate\Support\Facades\Route;

// Tamu (Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Sudah Login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Fitur yang bisa diakses Admin dan Kasir (POS & Riwayat Transaksi)
    Route::middleware('role:admin,kasir')->group(function () {
        Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
        Route::post('/pos/transaksi', [PosController::class, 'store'])->name('pos.store');
        Route::get('/pos/struk/{transaction}', [PosController::class, 'receipt'])->name('pos.receipt');
        Route::get('/transactions', [TransactionReportController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{transaction}', [TransactionReportController::class, 'show'])->name('transactions.show');
    });

    // Khusus Admin (Dashboard Ringkasan & Master Data)
    Route::middleware('role:admin')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('products', ProductController::class)->except(['show']);
    });
});

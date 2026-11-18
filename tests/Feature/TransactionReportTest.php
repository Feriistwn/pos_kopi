<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\User;
use Tests\TestCase;

class TransactionReportTest extends TestCase
{
    public function test_admin_can_view_transaction_history(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/transactions');
        $response->assertStatus(200);
        $response->assertSee('Daftar Transaksi Kasir');
        $response->assertSee('Total Pendapatan');
    }

    public function test_kasir_can_also_view_transaction_history(): void
    {
        $kasir = User::where('role', 'kasir')->first();

        $response = $this->actingAs($kasir)->get('/transactions');
        $response->assertStatus(200);
        $response->assertSee('Daftar Transaksi Kasir');
        $response->assertSee('Total Pendapatan');
    }

    public function test_admin_can_filter_transactions_by_method(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/transactions?metode=tunai');
        $response->assertStatus(200);
        $response->assertSee('Daftar Transaksi Kasir');
    }

    public function test_admin_and_kasir_can_view_transaction_detail(): void
    {
        $kasir = User::where('role', 'kasir')->first();
        $trx = Transaction::first();

        $response = $this->actingAs($kasir)->get('/transactions/'.$trx->id);
        $response->assertStatus(200);
        $response->assertSee($trx->nomor_invoice);
        $response->assertSee('TOTAL KESELURUHAN');
    }
}

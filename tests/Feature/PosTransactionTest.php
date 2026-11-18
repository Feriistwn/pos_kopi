<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Tests\TestCase;

class PosTransactionTest extends TestCase
{
    public function test_kasir_can_access_pos_screen(): void
    {
        $kasir = User::where('role', 'kasir')->first();

        $response = $this->actingAs($kasir)->get('/pos');
        $response->assertStatus(200);
        $response->assertSee('KopiPOS');
        $response->assertSee('KASIR');
    }

    public function test_kasir_can_checkout_cash_transaction_and_reduce_stock(): void
    {
        $kasir = User::where('role', 'kasir')->first();
        $product = Product::first();
        $initialStock = $product->stok;

        $response = $this->actingAs($kasir)->postJson('/pos/transaksi', [
            'metode_pembayaran' => 'tunai',
            'uang_diterima' => 50000,
            'items' => [
                [
                    'id' => $product->id,
                    'kuantitas' => 2,
                ],
            ],
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'sukses' => true,
        ]);

        $product->refresh();
        $this->assertEquals($initialStock - 2, $product->stok);

        $this->assertDatabaseHas('transactions', [
            'user_id' => $kasir->id,
            'metode_pembayaran' => 'tunai',
            'total_harga' => $product->harga * 2,
            'status' => 'selesai',
        ]);
    }

    public function test_checkout_fails_if_stock_is_insufficient(): void
    {
        $kasir = User::where('role', 'kasir')->first();
        $product = Product::first();

        $response = $this->actingAs($kasir)->postJson('/pos/transaksi', [
            'metode_pembayaran' => 'tunai',
            'uang_diterima' => 9999999,
            'items' => [
                [
                    'id' => $product->id,
                    'kuantitas' => $product->stok + 50,
                ],
            ],
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'sukses' => false,
        ]);
    }

    public function test_receipt_view_renders_correctly(): void
    {
        $kasir = User::where('role', 'kasir')->first();
        $transaction = Transaction::first();

        $response = $this->actingAs($kasir)->get('/pos/struk/'.$transaction->id);
        $response->assertStatus(200);
        $response->assertSee($transaction->nomor_invoice);
        $response->assertSee('KEDAI KOPI POS');
    }
}

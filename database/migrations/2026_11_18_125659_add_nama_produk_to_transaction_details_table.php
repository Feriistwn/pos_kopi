<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->string('nama_produk', 255)->after('product_id')->nullable();
        });

        // Isi nama_produk untuk data transaksi yang sudah ada
        DB::statement('
            UPDATE transaction_details td
            LEFT JOIN products p ON td.product_id = p.id
            SET td.nama_produk = COALESCE(p.nama, "Menu Kedai Kopi")
            WHERE td.nama_produk IS NULL
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropColumn('nama_produk');
        });
    }
};

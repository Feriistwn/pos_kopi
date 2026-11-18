<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kopi = Category::where('nama', 'Kopi')->first();
        $nonKopi = Category::where('nama', 'Non-Kopi')->first();
        $makanan = Category::where('nama', 'Makanan')->first();
        $snack = Category::where('nama', 'Snack')->first();

        $products = [
            // Kopi
            [
                'category_id' => $kopi->id,
                'nama' => 'Espresso',
                'harga' => 15000,
                'stok' => 100,
                'image' => null,
            ],
            [
                'category_id' => $kopi->id,
                'nama' => 'Americano',
                'harga' => 20000,
                'stok' => 100,
                'image' => null,
            ],
            [
                'category_id' => $kopi->id,
                'nama' => 'Cappuccino',
                'harga' => 25000,
                'stok' => 100,
                'image' => null,
            ],
            [
                'category_id' => $kopi->id,
                'nama' => 'Caffe Latte',
                'harga' => 28000,
                'stok' => 100,
                'image' => null,
            ],

            // Non-Kopi
            [
                'category_id' => $nonKopi->id,
                'nama' => 'Matcha Latte',
                'harga' => 27000,
                'stok' => 50,
                'image' => null,
            ],
            [
                'category_id' => $nonKopi->id,
                'nama' => 'Cokelat Panas',
                'harga' => 22000,
                'stok' => 50,
                'image' => null,
            ],

            // Makanan
            [
                'category_id' => $makanan->id,
                'nama' => 'Nasi Goreng Spesial',
                'harga' => 25000,
                'stok' => 30,
                'image' => null,
            ],
            [
                'category_id' => $makanan->id,
                'nama' => 'Mie Goreng Jawa',
                'harga' => 23000,
                'stok' => 30,
                'image' => null,
            ],

            // Snack
            [
                'category_id' => $snack->id,
                'nama' => 'Kentang Goreng',
                'harga' => 18000,
                'stok' => 40,
                'image' => null,
            ],
            [
                'category_id' => $snack->id,
                'nama' => 'Roti Bakar Cokelat Keju',
                'harga' => 15000,
                'stok' => 40,
                'image' => null,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}

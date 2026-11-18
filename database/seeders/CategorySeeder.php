<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['nama' => 'Kopi'],
            ['nama' => 'Non-Kopi'],
            ['nama' => 'Makanan'],
            ['nama' => 'Snack'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

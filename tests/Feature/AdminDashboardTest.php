<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_dashboard(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/');
        $response->assertStatus(200);
        $response->assertSee('Ringkasan Dashboard');
        $response->assertSee('Penjualan Hari Ini');
    }

    public function test_admin_can_list_and_create_category(): void
    {
        $admin = User::where('role', 'admin')->first();

        $this->actingAs($admin)->get('/categories')->assertStatus(200);

        $response = $this->actingAs($admin)->post('/categories', [
            'nama' => 'Minuman Signature',
        ]);
        $response->assertRedirect('/categories');
        $this->assertDatabaseHas('categories', ['nama' => 'Minuman Signature']);
    }

    public function test_admin_can_list_and_create_product_with_image(): void
    {
        Storage::fake('public');
        $admin = User::where('role', 'admin')->first();
        $cat = Category::first();

        $file = UploadedFile::fake()->image('coffee.jpg');

        $response = $this->actingAs($admin)->post('/products', [
            'nama' => 'Kopi Tubruk Tuban',
            'category_id' => $cat->id,
            'harga' => 12000,
            'stok' => 25,
            'image' => $file,
        ]);

        $response->assertRedirect('/products');
        $this->assertDatabaseHas('products', ['nama' => 'Kopi Tubruk Tuban']);
    }
}

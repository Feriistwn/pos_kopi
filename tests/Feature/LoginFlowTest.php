<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class LoginFlowTest extends TestCase
{
    public function test_admin_can_login_via_post(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@pos.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();
    }

    public function test_kasir_can_login_via_post(): void
    {
        $response = $this->post('/login', [
            'email' => 'kasir@pos.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/pos');
        $this->assertAuthenticated();
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'admin@pos.com',
            'password' => 'salah',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}

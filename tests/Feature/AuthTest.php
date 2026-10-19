<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_view_login_and_register_pages(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('เข้าสู่ระบบ');

        $this->get('/register')
            ->assertOk()
            ->assertSee('สมัครสมาชิก');
    }

    public function test_user_can_register_and_first_user_becomes_admin(): void
    {
        $response = $this->post('/register', [
            'name' => 'สมชาย มั่งคั่ง',
            'email' => 'somchai@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::query()->where('email', 'somchai@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->is_admin);
        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_subsequent_registered_user_is_not_admin(): void
    {
        // First user
        User::query()->create([
            'name' => 'Admin First',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'is_admin' => true,
        ]);

        $this->post('/register', [
            'name' => 'Regular User',
            'email' => 'regular@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::query()->where('email', 'regular@example.com')->first();
        $this->assertNotNull($user);
        $this->assertFalse($user->is_admin);
    }

    public function test_user_can_login_and_logout(): void
    {
        $user = User::query()->create([
            'name' => 'Test User',
            'email' => 'login@example.com',
            'password' => bcrypt('secret123'),
            'is_admin' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'login@example.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);

        $logoutResponse = $this->post('/logout');
        $logoutResponse->assertRedirect(route('home'));
        $this->assertGuest();
    }
}

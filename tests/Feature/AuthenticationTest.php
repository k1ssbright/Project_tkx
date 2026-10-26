<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitor_can_register_and_is_logged_in_as_member(): void
    {
        $this->get('/register')->assertOk()->assertSee('สมัครสมาชิก');

        $this->post('/register', [
            'name' => 'สมชาย',
            'email' => 'Somchai@Example.com',
            'password' => 'secret-pass',
            'password_confirmation' => 'secret-pass',
        ])->assertRedirect(route('home'));

        $user = User::query()->where('email', 'somchai@example.com')->first();

        $this->assertNotNull($user);
        $this->assertFalse($user->is_admin);
        $this->assertAuthenticatedAs($user);

        $this->get('/')->assertSee('สมชาย')->assertSee('ออกจากระบบ')->assertDontSee('หลังบ้าน');
    }

    public function test_registration_validates_input(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);

        $this->post('/register', [
            'name' => '',
            'email' => 'taken@example.com',
            'password' => 'short',
            'password_confirmation' => 'different',
        ])->assertSessionHasErrors(['name', 'email', 'password']);

        $this->assertGuest();
    }

    public function test_member_can_login_and_logout(): void
    {
        $user = User::factory()->create(['email' => 'member@example.com']);

        $this->get('/login')->assertOk()->assertSee('เข้าสู่ระบบ');

        $this->post('/login', [
            'email' => 'member@example.com',
            'password' => 'password',
        ])->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
        $this->get('/login')->assertRedirect();

        $this->post('/logout')->assertRedirect(route('home'));
        $this->assertGuest();
    }

    public function test_admin_is_redirected_to_dashboard_after_login(): void
    {
        User::factory()->admin()->create(['email' => 'admin@example.com']);

        $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->get('/')->assertSee('หลังบ้าน');
    }

    public function test_wrong_password_is_rejected_and_throttled(): void
    {
        User::factory()->create(['email' => 'member@example.com']);

        foreach (range(1, 5) as $attempt) {
            $this->post('/login', [
                'email' => 'member@example.com',
                'password' => 'wrong-password',
            ])->assertSessionHasErrors(['email' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง']);
        }

        $this->post('/login', [
            'email' => 'member@example.com',
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }
}

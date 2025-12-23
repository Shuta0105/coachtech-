<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_is_required_on_login()
    {
        $this->get('/login');
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123'
        ]);
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email']);
        $this->followRedirects($response)
            ->assertSee('メールアドレスを入力してください');
    }

    public function test_password_is_required_on_login()
    {
        $this->get('/login');
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => ''
        ]);
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['password']);
        $this->followRedirects($response)
            ->assertSee('パスワードを入力してください');
    }

    public function test_login_fails_with_unregistered_credentials()
    {
        $this->get('/login');
        $response = $this->post('/login', [
            'email' => 'notfound@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/login');

        $response->assertSessionHasErrors([
            'email' => 'ログイン情報が登録されていません'
        ]);
    }

    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }
}

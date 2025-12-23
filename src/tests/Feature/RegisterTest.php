<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_name_is_required_on_register()
    {
        $this->get('/register');
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['name']);
        $this->followRedirects($response)
            ->assertSee('お名前を入力してください');
    }

    public function test_email_is_required_on_register()
    {
        $this->get('/register');
        $response = $this->post('/register', [
            'name' => 'test',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['email']);
        $this->followRedirects($response)
            ->assertSee('メールアドレスを入力してください');
    }

    public function test_password_is_required_on_register()
    {
        $this->get('/register');
        $response = $this->post('/register', [
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => 'password123'
        ]);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['password']);
        $this->followRedirects($response)
            ->assertSee('パスワードを入力してください');
    }

    public function test_password_is_short_on_register()
    {
        $this->get('/register');
        $response = $this->post('/register', [
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => '123',
            'password_confirmation' => '123'
        ]);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['password']);
        $this->followRedirects($response)
            ->assertSee('パスワードは8文字以上で入力してください');
    }

    public function test_password_is_different_on_register()
    {
        $this->get('/register');
        $response = $this->post('/register', [
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password123'
        ]);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['password_confirmation']);
        $this->followRedirects($response)
            ->assertSee('パスワードと一致しません');
    }

    public function test_user_can_register_and_redirect_to_verification()
    {
        $this->get('/register');
        $response = $this->post('/register', [
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $response->assertRedirect('/verify');
        $this->assertDatabaseHas('users', [
            'name' => 'test',
            'email' => 'test@example.com'
        ]);
    }
}

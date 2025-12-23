<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_checkout_session()
    {
        $user = User::factory()->create();
        /** @var \App\Models\User $user */
        $this->actingAs($user);

        $user_profile = UserProfile::factory()->create([
            'user_id' => $user->id,
        ]);

        $item = Item::factory()->create();

        $response = $this->get("/purchase/{$item->id}");
        $response->assertStatus(200);

        $mockSession = \Mockery::mock(\Stripe\Checkout\Session::class);
        $mockSession->id = 'cs_test_12345';
        \Stripe\Checkout\Session::shouldReceive('create')
            ->once()
            ->andReturn($mockSession);
 
        $response = $this->postJson("/create-checkout-session/{$item->id}", [
            'paymethod' => 1,
            'post_code' => $user_profile->post_code,
            'address' => $user_profile->address,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['id']);
    }

    public function test_order_creates_order_in_database()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $mockSession = \Mockery::mock(\Stripe\Checkout\Session::class);
    $mockSession->payment_status = 'paid';
    $mockSession->metadata = (object)[
        'user_id' => $user->id,
        'item_id' => $item->id,
        'paymethod' => 1,
        'post_code' => '123-4567',
        'address' => 'Tokyo, Shibuya 1-2-3',
        'building' => 'Shibuyaビル101'
    ];

    \Stripe\Checkout\Session::shouldReceive('retrieve')
        ->once()
        ->andReturn($mockSession);

    $response = $this->get("/stripe/order?session_id=cs_test_12345");
    $response->assertRedirect('/');

    $this->assertDatabaseHas('orders', [
        'user_id' => $user->id,
        'item_id' => $item->id,
        'paymethod' => 1,
        'post_code' => '123-4567',
        'address' => 'Tokyo, Shibuya 1-2-3',
        'building' => 'Shibuyaビル101'
    ]);
    }

}

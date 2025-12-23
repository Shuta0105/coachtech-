<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShippingAddressTest extends TestCase
{
    use RefreshDatabase;

    public function test_registered_address_is_reflected_in_checkout()
    {
        $user = User::factory()->create();
        /** @var \App\Models\User $user */
        $this->actingAs($user);

        $item = Item::factory()->create();

        $response = $this->post("/purchase/address/{$item->id}", [
            'post_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'マンション101'
        ]);

        $response->assertRedirect("/purchase/{$item->id}");

        $checkoutResponse = $this->withSession($response->getSession()->all())
            ->get("/purchase/{$item->id}");

        $checkoutResponse->assertStatus(200);
        $checkoutResponse->assertSee('123-4567');
        $checkoutResponse->assertSee('東京都渋谷区');
        $checkoutResponse->assertSee('マンション101');
    }
}

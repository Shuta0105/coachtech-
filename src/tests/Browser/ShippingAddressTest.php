<?php

namespace Tests\Browser;

use App\Models\Item;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ShippingAddressTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_changed_address_reflects_on_purchase_page()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        $user_profile = UserProfile::factory()->create([
            'user_id' => $user->id
        ]);

        $this->browse(function (Browser $browser) use ($user, $item) {
            $newAddress = [
                'post_code' => '123-4567',
                'address'   => '東京都渋谷区1',
                'building'  => 'ビル101',
            ];

            $browser->loginAs($user)
                ->visit("/purchase/{$item->id}")
                ->pause(500)
                ->click('.purchase__address-change')
                ->assertPathIs("/purchase/address/{$item->id}")
                ->type('post_code', $newAddress['post_code'])
                ->type('address', $newAddress['address'])
                ->type('building', $newAddress['building'])
                ->press('更新する')
                ->assertPathIs("/purchase/{$item->id}")
                ->assertSee($newAddress['post_code'])
                ->assertSee($newAddress['address'])
                ->assertSee($newAddress['building']);
        });
    }

    public function test_shipping_address_stored_when_item_purchased()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        $user_profile = UserProfile::factory()->create([
            'user_id' => $user->id
        ]);

        $this->browse(function (Browser $browser) use ($user, $item) {
            $address = [
                'post_code' => '123-4567',
                'address'   => '東京都渋谷区',
                'building'  => 'ビル101',
            ];

            $browser->loginAs($user)
                ->visit("/purchase/{$item->id}")

                ->click('.purchase__address-change')
                ->type('post_code', $address['post_code'])
                ->type('address', $address['address'])
                ->type('building', $address['building'])
                ->press('更新する')
                ->assertPathIs("/purchase/{$item->id}")

                ->select('#paymethod', 2)
                ->click('#checkout-button')
                ->pause(5000);

            $this->assertDatabaseHas('orders', [
                'user_id' => $user->id,
                'item_id' => $item->id,
                'post_code' => $address['post_code'],
                'address' => $address['address'],
                'building' => $address['building'],
            ]);
        });
    }
}

<?php

namespace Tests\Browser;

use App\Models\Item;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PurchaseTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_user_can_complete_purchase()
    {
        $user = User::factory()->create();
        $user_profile = UserProfile::factory()->create([
            'user_id' => $user->id
        ]);
        $item = Item::factory()->create([
            'name' => 'テスト商品',
            'price' => 1000
        ]);

        $this->browse(function (Browser $browser) use ($user, $item) {
            $browser->loginAs($user)
                ->visit("/purchase/{$item->id}")
                ->assertSee($item->name)
                ->click('#checkout-button')
                ->pause(2000)

                // Stripe Checkout のテストカード入力画面は自動でスキップできないのでモック
                ->script([
                    "window.Stripe = () => ({redirectToCheckout: () => Promise.resolve({error: null})});"
                ]);

            // 購入完了ページを確認
            $browser->assertPathIs('/');
        });
    }
}

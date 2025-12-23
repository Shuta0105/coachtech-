<?php

namespace Tests\Browser;

use App\Models\Item;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PaymethodTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function test_selected_payment_method_is_reflected_in_summary()
    {
        $user = User::factory()->create();
        $user_profile = UserProfile::factory()->create([
            'user_id' => $user->id
        ]);
        $item = Item::factory()->create([
            'name' => 'テスト商品',
            'price' => 1000
        ]);

        $this->browse(function (Browser $browser) use ($user, $item, $user_profile) {
            $browser->loginAs($user)
                ->visit("/purchase/{$item->id}")

                ->select('#paymethod', '2')
                ->pause(500)
                ->assertSeeIn('@selected-paymethod', 'カード支払い')

                ->select('paymethod', '1')
                ->pause(500)
                ->assertSeeIn('@selected-paymethod', 'コンビニ払い');
        });
    }
}

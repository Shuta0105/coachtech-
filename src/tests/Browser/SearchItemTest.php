<?php

namespace Tests\Browser;

use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SearchItemTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_search_keyword_is_kept_when_navigating_to_mylist()
    {
        $user = User::factory()->create();
        /** @var \App\Models\User $user */
        $this->actingAs($user);

        $item = Item::factory()->create([
            'name' => 'iphone'
        ]);

        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/')
                ->type('@search-input', 'iphone')
                ->pause(500)

                ->assertQueryStringHas('keyword', 'iphone')
                ->assertSee('iphone')

                ->click('@tab-mylist')
                ->pause(500)

                ->assertQueryStringHas('keyword', 'iphone')
                ->assertQueryStringHas('tab', 'mylist')
                ->assertSee('iphone');
        });
    }
}

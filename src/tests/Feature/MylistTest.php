<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Like;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MylistTest extends TestCase
{
    use RefreshDatabase;

    public function test_liked_items_are_displayed_in_my_list()
    {
        $user = User::factory()->create();

        /** @var \App\Models\User $user */
        $this->actingAs($user);

        $item = Item::factory()->create();

        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id
        ]);

        $response = $this->get('/?tab=mylist');

        $response->assertSee($item->name);
    }

    public function test_sold_label_is_displayed_for_purchased_items()
    {
        $user = User::factory()->create();

        /** @var \App\Models\User $user */
        $this->actingAs($user);

        $item = Item::factory()->create();

        Order::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get('/?=mylist');

        $response->assertSee('Sold');
    }

    public function test_mylist_is_empty_for_guest()
    {
        $response = $this->get('/?=mylist');

        $response->assertSee('<div id="item-list" class="product-list__inner">', false)
                ->assertDontSee('<div class="product-list__item>');

        $response->assertStatus(200);
    }
}

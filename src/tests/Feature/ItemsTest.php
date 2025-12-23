<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemsTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_all_items()
    {
        $items = Item::factory()->count(10)->create();

        $response = $this->get('/');

        $response->assertStatus(200);

        foreach ($items as $item) {
            $response->assertSee($item->name);
        }
    }

    public function test_show_purchased_items_with_sold_label()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create();

        Order::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'paymethod' => 1,
            'post_code' => '123-4567',
            'address' => '東京都'
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);

        $response->assertSee($item->name);
        $response->assertSee('Sold');
    }

    public function test_my_items_are_not_listed()
    {
        $user = User::factory()->create();

        /** @var \App\Models\User $user */
        $this->actingAs($user);

        $item = Item::factory()->create([
            'user_id' => $user->id
        ]);

        $response = $this->get('/');

        $response->assertDontSee($item->name);
    }
}

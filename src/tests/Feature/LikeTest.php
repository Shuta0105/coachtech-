<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_like_item_and_like_count_increases()
    {
        $user = User::factory()->create();
        /** @var \App\Models\User $user */
        $this->actingAs($user);

        $item = Item::factory()->create();

        $this->assertDatabaseCount('likes', 0);

        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);

        $response = $this->postJson("/like/{$item->id}");

        $response->assertJson([
            'liked' => true,
            'likes_count' => 1,
        ]);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    public function test_like_icon_changes_class_when_liked()
    {
        $user = User::factory()->create();
        /** @var \App\Models\User $user */
        $this->actingAs($user);

        $item = Item::factory()->create();

        $response = $this->get("/item/{$item->id}");
        $response->assertSee('product-detail__icon--heart');
        $response->assertDontSee('product-detail__icon--heart active');

        $this->postJson("/like/{$item->id}");

        $item->refresh();

        $response = $this->get("/item/{$item->id}");
        $response->assertSee('product-detail__icon--heart active');
    }

    public function test_user_can_unlike_item_and_like_count_decreases()
    {
        $user = User::factory()->create();
        /** @var \App\Models\User $user */
        $this->actingAs($user);

        $item = Item::factory()->create();

        $like = Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get("/item/{$item->id}");
        $response->assertSee('product-detail__icon--heart active');
        $response->assertSee("1");

        $this->postJson("/like/{$item->id}");

        $item->refresh();

        $response = $this->get("/item/{$item->id}");
        $response->assertSee('product-detail__icon--heart');
        $response->assertDontSee('product-detail__icon--heart active');
        $response->assertSee('0');

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }
}

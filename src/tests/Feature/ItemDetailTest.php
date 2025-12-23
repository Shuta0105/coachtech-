<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Condition;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\Like;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_detail_page_displays_all_information()
    {
        $user = User::factory()->create();
        /** @var \App\Models\User $user */
        $this->actingAs($user);

        $condition = Condition::factory()->create([
            'content' => 'テスト状態'
        ]);

        $item = Item::factory()->create([
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'price' => 5000,
            'detail' => 'テスト商品説明',
            'condition_id' => $condition->id,
            'img' => 'test_image.jpeg'
        ]);

        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id
        ]);

        Comment::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'テストコメント'
        ]);

        $category = Category::factory()->create([
            'content' => 'テストカテゴリ'
        ]);

        ItemCategory::factory()->create([
            'item_id' => $item->id,
            'category_id' => $category->id
        ]);

        $response = $this->get("/item/{$item->id}");

        $response->assertSee('テスト商品')
            ->assertSee('テストブランド')
            ->assertSee(5000)
            ->assertSee('テスト商品説明')
            ->assertSee('テストカテゴリ')
            ->assertSee('テスト状態')
            ->assertSee('1')
            ->assertSee('test_image.jpeg')
            ->assertSee(1)                // いいね数
            ->assertSee(1)                //　コメント数
            ->assertSee(1)
            ->assertSee('テストコメント')
            ->assertSee($user->name);
    }

    public function test_all_selected_categories_displayed()
    {
        $category1 = Category::factory()->create([
            'content' => '家電'
        ]);
        $category2 = Category::factory()->create([
            'content' => 'キッチン'
        ]);

        $item = Item::factory()->create();

        ItemCategory::factory()->create([
            'item_id' => $item->id,
            'category_id' => $category1->id
        ]);
        ItemCategory::factory()->create([
            'item_id' => $item->id,
            'category_id' => $category2->id
        ]);

        $response = $this->get("/item/{$item->id}");

        $response->assertStatus(200);

        $response->assertSee('家電');
        $response->assertSee('キッチン');
    }
}

<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Condition;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SellTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_that_is_sold_stored()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        /** @var \App\Models\User $user */
        $this->actingAs($user);

        $condition = Condition::factory()->create([
            'content' => '新品'
        ]);

        $category1 = Category::factory()->create([
            'content' => '家電'
        ]);
        $category2 = Category::factory()->create([
            'content' => 'キッチン'
        ]);

        $postData = [
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'detail' => 'テスト商品の説明',
            'price' => 5000,
            'condition_id' => $condition->id,
            'category_ids' => [
                $category1->id,
                $category2->id,
            ],
            'img' => UploadedFile::fake()->create(
                'item.jpeg',
                100,
                'image/jpeg'
            ),
        ];

        $response = $this->post('/sell', $postData);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('items', [
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'detail' => 'テスト商品の説明',
            'price' => 5000,
            'condition_id' => $condition->id,
            'user_id' => $user->id,
        ]);

        $item = Item::first();

        $this->assertDatabaseHas('item_categories', [
            'item_id' => $item->id,
            'category_id' => $category1->id
        ]);

        $this->assertDatabaseHas('item_categories', [
            'item_id' => $item->id,
            'category_id' => $category2->id
        ]);
    }
}

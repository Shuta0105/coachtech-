<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_displays_necessary_information()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー'
        ]);
        /** @var \App\Models\User $user */
        $this->actingAs($user);

        $user_profile = UserProfile::factory()->create([
            'user_id' => $user->id,
            'avatar' => 'profile.jpeg'
        ]);

        $sellItem1 = Item::factory()->create([
            'name' => '出品商品A',
            'user_id' => $user->id,
            'img' => 'sell1.jpeg'
        ]);
        $sellItem2 = Item::factory()->create([
            'name' => '出品商品B',
            'user_id' => $user->id,
            'img' => 'sell2.jpeg'
        ]);

        $seller = User::factory()->create();
        $buyItem = Item::factory()->create([
            'name' => '購入商品C',
            'user_id' => $seller->id,
            'img' => 'buy.jpeg'
        ]);

        Order::factory()->create([
            'user_id' => $user->id,
            'item_id' => $buyItem->id,
        ]);

        $response = $this->get('/mypage?page=sell');

        $response->assertStatus(200)
            ->assertSee('テストユーザー')
            ->assertSee('profile.jpeg')
            ->assertSee('出品商品A')
            ->assertSee('出品商品B');

        $response = $this->get('/mypage?page=buy');

        $response->assertStatus(200)
            ->assertSee('テストユーザー')
            ->assertSee('profile.jpeg')
            ->assertSee('購入商品C');
    }

    public function test_profile_edit_page_displyas_correct_information()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー'
        ]);
        /** @var \App\Models\User $user */
        $this->actingAs($user);

        $user_profile = UserProfile::factory()->create([
            'user_id' => $user->id,
            'avatar' => 'profile.jpeg',
            'post_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テスト1-2-3'
        ]);

        $response = $this->get('/mypage/profile');

        $response->assertStatus(200)
            ->assertSee('profile.jpeg')
            ->assertSee('テストユーザー')
            ->assertSee('123-4567')
            ->assertSee('東京都渋谷区')
            ->assertSee('テスト1-2-3');
    }
}

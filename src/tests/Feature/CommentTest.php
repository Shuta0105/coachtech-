<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_post_comment_and_comment_count_increases()
    {
        $user = User::factory()->create();
        UserProfile::factory()->create([
            'user_id' => $user->id
        ]);
        /** @var \App\Models\User $user */
        $this->actingAs($user);

        $item = Item::factory()->create();

        $response = $this->get("/item/{$item->id}");
        $response->assertSee('コメント(0)');

        $this->post("/comment/{$item->id}", [
            'comment' => 'テストコメント'
        ]);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'テストコメント'
        ]);

        $response = $this->get("/item/{$item->id}");
        $response->assertSee('コメント(1)');
        $response->assertSee('テストコメント');
    }

    public function test_guest_cannot_post_comment()
    {
        $item = Item::factory()->create();

        $response = $this->post("/comment/{$item->id}", [
            'comment' => 'テストコメント'
        ]);

        $response->assertRedirect('/login');

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'content' => 'テストコメント'
        ]);
    }

    public function test_empty_comment_shows_validation_error()
    {
        $user = User::factory()->create();
        /** @var \App\Models\User $user */
        $this->actingAs($user);

        $item = Item::factory()->create();

        $this->get("/item/{$item->id}");

        $response = $this->post("/comment/{$item->id}", [
            'comment' => ''
        ]);

        $response->assertSessionHasErrors('comment');

        $response->assertRedirect("/item/{$item->id}");

        $followedResponse = $this->followingRedirects()->post("/comment/{$item->id}", [
            'comment' => ''
        ]);
        $followedResponse->assertSee('コメントを入力してください');
    }

    public function test_comment_exceeds_max_length_shows_validation_error()
    {
        $user = User::factory()->create();
        /** @var \App\Models\User $user */
        $this->actingAs($user);

        $item = Item::factory()->create();

        $longComment = str_repeat('あ', 256);

        $this->get("/item/{$item->id}");

        $response = $this->post("/comment/{$item->id}", [
            'comment' => $longComment
        ]);
        $response->assertSessionHasErrors('comment');
        $response->assertRedirect("/item/{$item->id}");

        $followedResponse = $this->followingRedirects()->post("/comment/{$item->id}", [
            'comment' => $longComment
        ]);
        $followedResponse->assertSee('コメントは255文字以内で入力してください');
    }
}

<?php

namespace Tests\Feature;

use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_displays_matching_items()
    {
        Item::factory()->create(['name' => 'テスト商品A']);
        Item::factory()->create(['name' => '別の商品B']);

        $response = $this->get('/?keyword=テスト');

        $response->assertSee('テスト商品A');

        $response->assertDontSee('別の商品B');
    }
}

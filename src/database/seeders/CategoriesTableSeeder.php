<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create(['content' => 'ファッション']);
        Category::create(['content' => '家電']);
        Category::create(['content' => 'インテリア']);
        Category::create(['content' => 'レディース']);
        Category::create(['content' => 'メンズ']);
        Category::create(['content' => 'コスメ']);
        Category::create(['content' => '本']);
        Category::create(['content' => 'ゲーム']);
        Category::create(['content' => 'スポーツ']);
        Category::create(['content' => 'キッチン']);
        Category::create(['content' => 'ハンドメイド']);
        Category::create(['content' => 'アクセサリー']);
        Category::create(['content' => 'おもちゃ']);
        Category::create(['content' => 'ベビー・キッズ']);
    }
}

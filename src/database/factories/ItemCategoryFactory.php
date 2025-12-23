<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Item;
use App\Models\ItemCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemCategoryFactory extends Factory
{
    protected $model = ItemCategory::class;

    public function definition()
    {
        return [
            'item_id' => Item::factory(),
            'category_id' => Category::factory(),
        ];
    }
}

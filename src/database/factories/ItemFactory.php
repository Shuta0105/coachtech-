<?php

namespace Database\Factories;

use App\Models\Condition;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'price' => $this->faker->numberBetween(0, 20000),
            'detail' => $this->faker->sentence(),
            'condition_id' => Condition::factory(),
            'img' => $this->faker->imageUrl(true)
        ];
    }
}

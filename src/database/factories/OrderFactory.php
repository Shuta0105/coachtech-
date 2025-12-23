<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'item_id' => Item::factory(),
            'paymethod' => $this->faker->numberBetween(1,2),
            'post_code' => $this->faker->postcode(),
            'address' => $this->faker->address(),
        ];
    }
}

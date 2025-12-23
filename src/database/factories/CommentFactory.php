<?php

namespace Database\Factories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(1,50),
            'item_id' => $this->faker->numberBetween(1,50),
            'content' => $this->faker->sentence()
        ];
    }
}

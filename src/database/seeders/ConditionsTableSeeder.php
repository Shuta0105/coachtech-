<?php

namespace Database\Seeders;

use App\Models\Condition;
use Illuminate\Database\Seeder;

class ConditionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Condition::create(['content' => '良好']);
        Condition::create(['content' => '目立った傷や汚れなし']);
        Condition::create(['content' => 'やや傷や汚れあり']);
        Condition::create(['content' => '状態が悪い']);
    }
}

<?php

namespace Database\Factories;

use App\Models\ChartOfAccount;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ChartOfAccountFactory extends Factory
{
    protected $model = ChartOfAccount::class;

    public function definition(): array
    {
        return [
            'code' => fake()->numberBetween(1000, 9999),
            'name' => $this->faker->words(2, true),
            'category_name' => $this->faker->word(),
            'user_id' => User::factory(),
        ];
    }
}

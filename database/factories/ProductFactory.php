<?php

namespace Database\Factories;

use App\Enums\ProductType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => ucfirst(fake()->unique()->word()),
            'description' => ucfirst(fake()->text),
            'type' => fake()->randomElement(['pizza', 'drink']),
            'price' => fake()->randomFloat(2, 1, 100),
        ];
    }
}

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
            'title' => fake()->unique()->word(),
            'description' => fake()->text,
            'type' => fake()->randomElement([ProductType::Pizza->value, ProductType::Drink->value]),
            'price' => fake()->randomFloat(2, 1, 100),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CartProduct>
 */
class CartProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'quantity' => fake()->numberBetween(1, 10)
        ];
    }
}

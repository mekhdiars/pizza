<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'status' => OrderStatus::PREPARING->value,
            'delivery_time' => now()->toTimeString('minute'),
            'delivery_address' => fake()->streetName() . ', ' . fake()->buildingNumber(),
        ];
    }
}

<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::factory(2)->create();
        $productsQuantity = 2;

        Order::factory()
            ->for(User::factory())
            ->hasAttached($products, ['quantity' => $productsQuantity])
            ->create([
                'amount' => ($products[0]->price + $products[1]->price) * $productsQuantity,
            ]);
    }
}

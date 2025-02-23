<?php

namespace Database\Seeders;

use App\Models\CartProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartProductSeeder extends Seeder
{
    public function run(): void
    {
        CartProduct::factory()
            ->for(User::factory())
            ->for(Product::factory())
            ->create();
    }
}

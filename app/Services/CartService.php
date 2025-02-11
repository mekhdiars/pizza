<?php

namespace App\Services;

use App\Enums\ProductType;
use App\Models\Product;
use App\Models\User;

class CartService
{
    public function replaceUserCart(User $user, array $cartProducts): void
    {
        \DB::transaction(function () use ($user, $cartProducts) {
            $user->cartProducts()->delete();
            $user->cartProducts()->createMany($cartProducts);
        });
    }

    public function getCountProductsByType(array $products): array
    {
        $productIds = collect($products)
            ->pluck('product_id')
            ->unique();
        $productTypes = Product::query()
            ->whereIn('id', $productIds)
            ->pluck('type', 'id');

        $productsCount = [ProductType::Pizza->value => 0, ProductType::Drink->value => 0];
        foreach ($products as $item) {
            $type = $productTypes[$item['product_id']];
            $productsCount[$type] += $item['quantity'];
        }

        return $productsCount;
    }
}

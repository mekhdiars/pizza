<?php

namespace App\Services\User;

use App\Models\Product;

class ProductService
{
    /**
     * @param array<array{product_id: int, quantity: int}> $products
     * @return array{pizza: int, drink: int}
     */
    public function getCountProductsByType(array $products): array
    {
        $productIds = collect($products)
            ->pluck('product_id')
            ->unique();
        $productTypes = Product::query()
            ->whereIn('id', $productIds)
            ->pluck('type', 'id');

        $productsCount = ['pizza' => 0, 'drink' => 0];
        foreach ($products as $item) {
            $type = $productTypes[$item['product_id']];
            $productsCount[$type] += $item['quantity'];
        }

        return $productsCount;
    }
}

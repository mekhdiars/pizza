<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
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

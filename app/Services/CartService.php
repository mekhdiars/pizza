<?php

namespace App\Services;

use App\Enums\LimitProductsInCart;
use App\Enums\ProductType;
use App\Models\CartProduct;
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

    public function addProduct(User $user, int $productId, int $quantity): CartProduct
    {
        $cartProduct = $user->cartProducts()
            ->where('product_id', $productId)
            ->first();

        if ($cartProduct) {
            $cartProduct->update([
                'quantity' => $cartProduct->quantity + $quantity
            ]);

            return $cartProduct;
        }

        return $user->cartProducts()->create([
            'product_id' => $productId,
            'quantity' => $quantity
        ]);
    }

    public function canAddProduct(User $user, int $productId, $quantity): bool
    {
        $type = Product::query()->find($productId)->type;
        $productsCount = $user->cartProducts()
            ->whereHas('product', function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->sum('quantity');

        $total = $productsCount + $quantity;

        return $type === 'pizza'
            ? $total <= LimitProductsInCart::Pizza->value
            : $total <= LimitProductsInCart::Drink->value;
    }

    public function howManyProductsCanAdd($user): array
    {
        $products = $user->cartProducts()->get();
        $productsCount = (new ProductService())
            ->getCountProductsByType($products->toArray());

        return [
            'pizza' => LimitProductsInCart::Pizza->value - $productsCount['pizza'],
            'drink' => LimitProductsInCart::Drink->value - $productsCount['drink']
        ];
    }
}

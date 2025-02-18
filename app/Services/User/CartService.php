<?php

namespace App\Services\User;

use App\Enums\LimitProductsInCart;
use App\Models\CartProduct;
use App\Models\Product;
use App\Models\User;

class CartService
{
    public function __construct(
        private readonly ProductService $productService
    ) {
    }

    public function addProduct(User $user, int $productId, int $quantity): CartProduct
    {
        $cartProduct = $user->cartProducts()
            ->where('product_id', $productId)
            ->first();

        if ($cartProduct) {
            $cartProduct->increment('quantity', $quantity);
            return $cartProduct->fresh();
        }

        return $user->cartProducts()->create([
            'product_id' => $productId,
            'quantity' => $quantity
        ]);
    }


    /**
     * @param array<array{product_id: int, quantity: int}> $cartProducts
     */
    public function replaceUserCart(User $user, array $cartProducts): void
    {
        \DB::transaction(function () use ($user, $cartProducts) {
            $user->cartProducts()->delete();
            $user->cartProducts()->createMany($cartProducts);
        });
    }

    public function canAddProduct(User $user, int $productId, int $quantity): bool
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

    /**
     * @return array{pizza: int, drink: int}
     */
    public function howManyProductsCanAdd(User $user): array
    {
        $products = $user->cartProducts()->get();
        $productsCount = $this->productService
            ->getCountProductsByType($products->toArray());

        return [
            'pizza' => LimitProductsInCart::Pizza->value - $productsCount['pizza'],
            'drink' => LimitProductsInCart::Drink->value - $productsCount['drink']
        ];
    }
}

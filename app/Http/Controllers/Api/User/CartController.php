<?php

namespace App\Http\Controllers\Api\User;

use App\Exceptions\CartLimitException;
use App\Http\Requests\User\Cart\AddProductRequest;
use App\Http\Requests\User\Cart\ReplaceCartRequest;
use App\Http\Resources\User\Cart\CartResource;
use App\Models\CartProduct;
use App\Services\User\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CartController extends UserBasedController
{
    public function __construct(
        private readonly CartService $cartService
    ) {
    }

    public function getCart(): JsonResponse
    {
        $user = $this->getUser();
        $cartProducts = $user->cartProducts;

        if ($cartProducts->isEmpty()) {
            return response()->json([
                'message' => 'Cart is empty'
            ]);
        }

        return response()->json(
            new CartResource($cartProducts)
        );
    }

    public function addProduct(AddProductRequest $request): JsonResponse
    {
        $user = $this->getUser();

        $hasRoom = $this->cartService->hasRoomForProductAddition($user, $request->product_id, $request->quantity);

        if ($hasRoom === false) {
            $productsCountCanAdd = $this->cartService->howManyProductsCanAdd($user);
            $message = "You can add {$productsCountCanAdd['pizza']} pizzas and {$productsCountCanAdd['drink']} drinks";
            throw new CartLimitException($message);
        }

        $this->cartService->addProduct($user, $request->product_id, $request->quantity);

        return response()->json([
            'message' => 'Product added to cart',
        ]);
    }

    public function replaceUserCart(ReplaceCartRequest $request): JsonResponse
    {
        $cartProducts = $request->cart_products;
        $user = $this->getUser();

        try {
            $this->cartService->replaceUserCart($user, $cartProducts);
        } catch (\Exception $e) {
            Log::error('Cart replace failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Cart replace failed'
            ], 500);
        }

        return response()->json(
            new CartResource($user->cartProducts)
        );
    }

    public function deleteProduct(CartProduct $cartProduct): JsonResponse
    {
        $cartProduct->delete();

        return response()->json([
            'message' => 'Product deleted from cart',
        ]);
    }
}

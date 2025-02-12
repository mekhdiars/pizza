<?php

namespace App\Http\Controllers\Api\User;

use App\Exceptions\ExceedingLimitCartProductsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Cart\AddProductRequest;
use App\Http\Requests\User\Cart\ReplaceCartRequest;
use App\Http\Resources\User\CartProductsResource;
use App\Models\CartProduct;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cartService
    ) {
    }

    public function getProducts(): JsonResponse
    {
        $user = auth('sanctum')->user();
        $cartProducts = $user->cartProducts;

        if (empty($cartProducts->toArray())) {
            return response()->json([
                'message' => 'Cart is empty'
            ]);
        }

        return response()->json([
            'cart_products' => CartProductsResource::collection($user->cartProducts),
        ]);
    }

    public function addProduct(AddProductRequest $request): JsonResponse
    {
        $user = auth('sanctum')->user();

        $canAdd = $this->cartService->canAddProduct($user, $request->product_id, $request->quantity);

        if ($canAdd === false) {
            $productsCountCanAdd = $this->cartService->howManyProductsCanAdd($user);
            $message = "You can add {$productsCountCanAdd['pizza']} pizzas and {$productsCountCanAdd['drink']} drinks";
            throw new ExceedingLimitCartProductsException($message);
        }

        $this->cartService->addProduct($user, $request->product_id, $request->quantity);

        return response()->json([
            'message' => 'Product added to cart',
        ]);
    }

    public function replaceUserCart(ReplaceCartRequest $request): JsonResponse
    {
        $cartProducts = $request->cart_products;
        $user = auth('sanctum')->user();

        try {
            $this->cartService->replaceUserCart($user, $cartProducts);
        } catch (\Exception $e) {
            Log::error('Cart replace failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Cart replace failed'
            ], 500);
        }

        return response()->json([
            'cart_products' => CartProductsResource::collection($user->cartProducts),
        ]);
    }

    public function deleteProduct(CartProduct $cartProduct): JsonResponse
    {
        $cartProduct->delete();

        return response()->json([
            'message' => 'Product deleted from cart',
        ]);
    }
}

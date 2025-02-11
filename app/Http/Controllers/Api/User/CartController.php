<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Cart\ReplaceCartRequest;
use App\Http\Resources\User\CartProductsResource;
use App\Models\CartProduct;
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

    public function getProducts()
    {
        return auth('sanctum')->user();
    }

    public function addProduct(Request $request)
    {
        //
    }

    public function deleteProduct(CartProduct $cartProduct)
    {
        //
    }
}

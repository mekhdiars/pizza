<?php

namespace App\Http\Controllers\Api\User;

use App\Enums\ProductType;
use App\Http\Resources\User\Product\MenuProductsResource;
use App\Http\Resources\User\Product\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends UserBasedController
{
    public function index(): JsonResponse
    {
        $pizzas = Product::query()
            ->where('type', ProductType::PIZZA->value)
            ->get();

        $drinks = Product::query()
            ->where('type', ProductType::DRINK->value)
            ->get();

        return response()->json([
            'pizzas' => MenuProductsResource::collection($pizzas),
            'drinks' => MenuProductsResource::collection($drinks)
        ]);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json(
            new ProductResource($product)
        );
    }
}

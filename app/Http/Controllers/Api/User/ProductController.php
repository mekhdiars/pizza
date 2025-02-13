<?php

namespace App\Http\Controllers\Api\User;

use App\Enums\ProductType;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\MenuProductsResource;
use App\Http\Resources\User\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        $pizzas = Product::query()
            ->where('type', 'pizza')
            ->get();

        $drinks = Product::query()
            ->where('type', 'drink')
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

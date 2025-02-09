<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\MenuProductsResource;
use App\Http\Resources\User\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        $products = Product::all();

        return response()->json([
            'products' => MenuProductsResource::collection($products)
        ]);
    }

    public function show(Product $product): ProductResource
    {
        return new ProductResource($product);
    }
}

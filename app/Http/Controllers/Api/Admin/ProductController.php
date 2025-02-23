<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Requests\Admin\Product\StoreProductRequest;
use App\Http\Requests\Admin\Product\UpdateProductRequest;
use App\Http\Resources\User\Product\MenuProductsResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AdminBasedController
{
    public function index(): JsonResponse
    {
        $pizzas = Product::query()
            ->where('type', 'pizza')
            ->withTrashed()
            ->paginate();
        $drinks = Product::query()
            ->where('type', 'drink')
            ->withTrashed()
            ->paginate();

        return response()->json([
            'pizzas' => MenuProductsResource::collection($pizzas),
            'drinks' => MenuProductsResource::collection($drinks),
        ]);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = Product::query()
            ->create($request->validated());

        return response()->json($product, Response::HTTP_CREATED);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json($product);
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $product->update($request->validated());

        return response()->json([
            'message' => 'Product updated'
        ]);
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json([
            'message' => 'Product deleted'
        ]);
    }
}

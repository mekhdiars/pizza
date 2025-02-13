<?php

namespace App\Http\Resources\User;

use App\Models\CartProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CartProductCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->transform(function (CartProduct $cartProduct) {
            return [
                'product' => new MinifiedProductResource($cartProduct->product),
                'quantity' => $cartProduct->quantity,
                'price' => round($cartProduct->product->price * $cartProduct->quantity, 2)
            ];
        })->toArray();
    }
}

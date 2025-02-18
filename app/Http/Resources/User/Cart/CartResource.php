<?php

namespace App\Http\Resources\User\Cart;

use App\Models\CartProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'cart_products' => new CartProductCollection($this),
            'total_price' => $request->user()->calculateCartTotal(),
        ];
    }
}

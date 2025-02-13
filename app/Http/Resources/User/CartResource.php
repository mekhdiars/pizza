<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $totalPrice = $this->reduce(function ($total, $cartProduct) {
            return $total + ($cartProduct->product->price * $cartProduct->quantity);
        }, 0);

        return [
            'cart_products' => new CartProductCollection($this),
            'total_price' => round($totalPrice, 2)
        ];
    }
}

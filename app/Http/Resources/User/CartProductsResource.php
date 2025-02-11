<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartProductsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'product' => new ProductResource($this->product),
            'quantity' => $this->quantity,
        ];
    }
}

<?php

namespace App\Http\Resources\User\Order;

use App\Http\Resources\User\Product\MinifiedProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'product' => new MinifiedProductResource($this),
            'quantity' => $this->pivot->quantity,
        ];
    }
}

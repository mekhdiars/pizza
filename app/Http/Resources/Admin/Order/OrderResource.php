<?php

namespace App\Http\Resources\Admin\Order;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->user),
            'amount' => $this->amount,
            'status' => $this->status,
            'delivery_address' => $this->delivery_address,
            'delivery_time' => $this->delivery_time,
            'products' => OrderProductResource::collection($this->products),
            'created_at' => $this->created_at,
        ];
    }
}

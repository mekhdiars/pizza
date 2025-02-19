<?php

namespace App\Http\Resources\User\Order;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->transform(function (Order $order) {
            return [
                'id' => $order->id,
                'amount' => $order->amount,
                'status' => $order->status,
                'delivery_address' => $order->delivery_address,
                'delivery_time' => $order->delivery_time,
                'products' => OrderProductResource::collection($order->products),
            ];
        })->toArray();
    }
}

<?php

namespace App\Http\Resources\Admin\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->transform(function (OrderResource $order) {
            return [
                'id' => $order->id,
                'user_name' => $order->user->name,
                'amount' => $order->amount,
                'status' => $order->status,
                'delivery_address' => $order->delivery_address,
                'delivery_time' => $order->delivery_time,
                'created_at' => $order->created_at,
            ];
        })->toArray();
    }
}

<?php

namespace App\Services\User;

use App\Enums\OrderStatus;
use App\Models\User;

class OrderService
{
    public function placeOrder(User $user, array $data): void
    {
        \DB::transaction(function () use ($user, $data) {
            $cartProducts = $user->cartProducts;
            $order = $user->orders()->create([
                'amount' => $user->getCartTotal(),
                'status' => OrderStatus::Preparing->value,
                'delivery_address' => $data['delivery_address'],
                'delivery_time' => $data['delivery_time'],
            ]);

            foreach ($cartProducts as $cartProduct) {
                $order->products()->attach(
                    $cartProduct->product_id,
                    ['quantity' => $cartProduct->quantity]
                );
            }

            $user->cartProducts()->delete();
        });
    }
}

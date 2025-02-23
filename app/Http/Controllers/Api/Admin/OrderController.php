<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Requests\Admin\Order\UpdateStatusRequest;
use App\Http\Resources\Admin\Order\OrderCollection;
use App\Http\Resources\Admin\Order\OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;

class OrderController extends AdminBasedController
{
    public function getActiveOrders(): JsonResponse
    {
        $activeOrders = Order::query()
            ->with('user')
            ->active()
            ->paginate();

        return response()->json(
            new OrderCollection($activeOrders)
        );
    }

    public function show(Order $order): JsonResponse
    {
        $order->load(['user', 'products']);

        return response()->json(
            new OrderResource($order)
        );
    }

    public function updateStatus(UpdateStatusRequest $request, Order $order): JsonResponse
    {
        $order->update(['status' => $request->status]);

        return response()->json(
            new OrderResource($order)
        );
    }

    public function getOrderHistory(): JsonResponse
    {
        $orders = Order::query()
            ->with('user')
            ->history()
            ->paginate();

        return response()->json(
            new OrderCollection($orders)
        );
    }
}

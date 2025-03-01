<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Requests\User\Order\StoreOrderRequest;
use App\Http\Resources\User\Order\OrderCollection;
use App\Services\User\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends UserBasedController
{
    public function __construct(
        private readonly OrderService $orderService
    ) {
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $user = $this->getUser();
        $data = $request->validated();

        try {
            $this->orderService->placeOrder($user, $data);
        } catch (\Exception $e) {
            Log::error('Order creation failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Order creation failed'
            ], 500);
        }

        return response()->json([
            'message' => 'Order created'
        ], Response::HTTP_CREATED);
    }

    public function getActiveOrders(): JsonResponse
    {
        $user = $this->getUser();
        $activeOrders = $user->orders()->active()->paginate();

        if ($activeOrders->isEmpty()) {
            return response()->json([
                'message' => 'No active orders found'
            ]);
        }

        return response()->json(
            new OrderCollection($activeOrders)
        );
    }

    public function getOrderHistory(): JsonResponse
    {
        $user = auth('sanctum')->user();
        $orders = $user->orders()
            ->history()
            ->with([
                'products' => function ($query) {
                    $query->withTrashed();
                }
            ])
            ->paginate();

        if ($orders->isEmpty()) {
            return response()->json([
                'message' => 'No order history found'
            ]);
        }

        return response()->json(
            new OrderCollection($orders)
        );
    }
}

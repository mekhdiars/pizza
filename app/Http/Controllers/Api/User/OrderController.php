<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Order\StoreOrderRequest;
use App\Models\Order;
use App\Services\User\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService
    ) {
    }

    public function index()
    {
        //
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $user = $request->user();
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

    public function show(Order $order)
    {
        //
    }
}

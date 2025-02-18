<?php

namespace Tests\Feature\User\Order;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PlaceOrderTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    public function test_cannot_place_order_if_cart_empty(): void
    {
        $data = [
            'delivery_address' => $this->user->address,
            'delivery_time' => '20:00'
        ];

        $response = $this->postJson(route('user.orders.store'), $data);

        $response->assertBadRequest();
    }

    public function test_place_order_success(): void
    {
        $pizza = Product::factory()->create(['type' => 'pizza']);
        $drink = Product::factory()->create(['type' => 'drink']);
        $cartProducts = $this->user->cartProducts()->createMany([
            ['product_id' => $pizza->id, 'quantity' => 2],
            ['product_id' => $drink->id, 'quantity' => 2]
        ]);

        $deliveryTime = now()->setTime(20, 0);
        $data = [
            'delivery_address' => $this->user->address,
            'delivery_time' => $deliveryTime->toTimeString('minute'),
        ];

        $response = $this->postJson(route('user.orders.store'), $data);

        $response->assertCreated();

        $this->assertDatabaseHas(Order::class, [
            'user_id' => $this->user->id,
            'amount' => $this->user->calculateCartTotal(),
            'status' => OrderStatus::Preparing->value,
            'delivery_address' => $data['delivery_address'],
            'delivery_time' => $data['delivery_time'],
        ]);

        $order = $this->user->orders()->first();

        $this->assertDatabaseHas('order_product', [
            'order_id' => $order->id,
            'product_id' => $pizza->id,
            'quantity' => $cartProducts[0]->quantity,
        ]);

        $this->assertDatabaseHas('order_product', [
            'order_id' => $order->id,
            'product_id' => $drink->id,
            'quantity' => $cartProducts[1]->quantity,
        ]);

        $this->user->refresh();
        $this->assertEmpty($this->user->cartProducts);
    }
}

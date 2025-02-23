<?php

namespace Tests\Feature\User\Order;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetActiveOrdersTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    public function test_no_active_orders(): void
    {
        $this->assertDatabaseMissing(Order::class, [
            'user_id' => $this->user->id,
            'status' => OrderStatus::Preparing->value
        ]);
        $this->assertDatabaseMissing(Order::class, [
            'user_id' => $this->user->id,
            'status' => OrderStatus::Delivering->value
        ]);

        $response = $this->getJson(route('user.orders.active'));

        $response
            ->assertOk()
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_get_active_order_success(): void
    {
        $products = Product::factory(2)->create();

        $productsCount = 2;
        Order::factory()
            ->for($this->user)
            ->hasAttached($products, ['quantity' => $productsCount])
            ->create([
                'amount' => ($products[0]->price + $products[1]->price) * $productsCount,
            ]);

        $response = $this->getJson(route('user.orders.active'));

        $response
            ->assertOk()
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'amount',
                    'status',
                    'delivery_address',
                    'delivery_time',
                    'products',
                ]
            ]);

        $this->assertCount(1, $response->json());
    }
}

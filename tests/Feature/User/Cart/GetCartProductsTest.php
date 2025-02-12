<?php

namespace Tests\Feature\User\Cart;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetCartProductsTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_get_cart(): void
    {
        $response = $this->getJson(route('user.cart.getProducts'));

        $response->assertUnauthorized();
    }

    public function test_get_empty_cart(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->getJson(route('user.cart.getProducts'));

        $response
            ->assertOk()
            ->assertJsonStructure(['message']);
    }

    public function test_get_cart_success(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $pizza = Product::factory()->create(['type' => 'pizza']);
        $drink = Product::factory()->create(['type' => 'drink']);
        $user->cartProducts()->createMany([
            [
                'product_id' => $pizza->id,
                'quantity' => 10
            ],
            [
                'product_id' => $drink->id,
                'quantity' => 5
            ]
        ]);

        $response = $this->getJson(route('user.cart.getProducts'));

        $response
            ->assertOk()
            ->assertJsonStructure([
                'cart_products' => [
                    '*' => [
                        'product' => [
                            'id',
                            'title',
                            'type',
                            'price'
                        ],
                        'quantity'
                    ],
                ]
            ]);
    }
}

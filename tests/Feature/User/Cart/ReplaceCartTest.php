<?php

namespace Feature\User\Cart;

use App\Enums\LimitProductsInCart;
use App\Models\CartProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ReplaceCartTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_replace_cart(): void
    {
        $cart = [
            'cart_products' => [
                CartProduct::factory()
                    ->for(User::factory())
                    ->for(Product::factory())
                    ->make()
            ]
        ];

        $response = $this->putJson(route('user.cart.replace'), $cart);
        $response->assertUnauthorized();
    }

    public function test_replace_validation_error(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->putJson(route('user.cart.replace'), []);
        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['cart_products']);
    }

    public function test_limit_products_count(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $cart = [
            'cart_products' => [
                CartProduct::factory()
                    ->for(User::factory())
                    ->for(Product::factory())
                    ->make([
                        'quantity' => LimitProductsInCart::Drink->value + 10
                    ])
            ]
        ];

        $response = $this->putJson(route('user.cart.replace'), $cart);
        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['cart_products']);
    }

    public function test_replace_cart_success(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $cart = [
            'cart_products' => [
                CartProduct::factory()
                    ->for(User::factory())
                    ->for(Product::factory())
                    ->make()
            ],
            [
                CartProduct::factory()
                    ->for(User::factory())
                    ->for(Product::factory())
                    ->make()
            ],
        ];

        $response = $this->putJson(route('user.cart.replace'), $cart);
        $response
            ->assertOk()
            ->assertJsonStructure([
                'cart_products' => [
                    '*' => [
                        'product' => [
                            'id',
                            'title',
                            'description',
                            'type',
                            'price'
                        ],
                        'quantity'],
                ]
            ]);
    }
}

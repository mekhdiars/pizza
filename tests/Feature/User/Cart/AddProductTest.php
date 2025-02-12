<?php

namespace Tests\Feature\User\Cart;

use App\Models\CartProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AddProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_add_product(): void
    {
        $product = Product::factory()->create();
        $response = $this->postJson(route('user.cart.addProduct'), [
            'product_id' => $product->id,
            'quantity' => 1
        ]);

        $response->assertUnauthorized();
    }

    public function test_validation(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson(route('user.cart.addProduct'), []);

        $response->assertUnprocessable();
    }

    public function test_limit_products_in_cart(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $user->cartProducts()->create([
            'product_id' => Product::factory()->create(['type' => 'pizza'])->id,
            'quantity' => 10
        ]);

        $response = $this->postJson(route('user.cart.addProduct'), [
            'product_id' => Product::factory()->create(['type' => 'pizza'])->id,
            'quantity' => 1
        ]);

        $response->assertBadRequest();
    }

    public function test_add_product_success(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $product = Product::factory()->create();

        $response = $this->postJson(route('user.cart.addProduct'), [
            'product_id' => $product->id,
            'quantity' => 10
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('cart_products', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 10
        ]);
    }
}

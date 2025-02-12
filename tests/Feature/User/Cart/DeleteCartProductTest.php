<?php

namespace Tests\Feature\User\Cart;

use App\Models\CartProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteCartProductTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    public function test_cannot_delete_product_from_alien_cart(): void
    {
        $alienCartProduct = CartProduct::factory()
            ->for(User::factory())
            ->for(Product::factory())
            ->create();

        $response = $this->deleteJson(route('user.cart.deleteProduct', $alienCartProduct));

        $response->assertForbidden();
    }

    public function test_get_cart_success(): void
    {
        $ownCartProduct = CartProduct::factory()
            ->for($this->user)
            ->for(Product::factory())
            ->create();

        $response = $this->deleteJson(route('user.cart.deleteProduct', $ownCartProduct));

        $response->assertOk();
    }
}

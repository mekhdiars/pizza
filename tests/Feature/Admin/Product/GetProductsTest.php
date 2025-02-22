<?php

namespace Tests\Feature\Admin\Product;

use App\Models\Admin;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetProductsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $admin = Admin::factory()->create();
        Sanctum::actingAs($admin);
        Product::factory(2)->create();
    }

    public function test_soft_deleted_product_visible(): void
    {
        $product = Product::factory()->create();
        $product->delete();

        $response = $this->get(route('admin.products.index'));

        $response
            ->assertOk()
            ->assertJsonFragment([
                [
                    'id' => $product->id,
                    'title' => $product->title,
                    'type' => $product->type,
                    'price' => $product->price
                ]
            ]);
    }
}

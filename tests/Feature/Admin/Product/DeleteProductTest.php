<?php

namespace Tests\Feature\Admin\Product;

use App\Models\Admin;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $admin = Admin::factory()->create();
        Sanctum::actingAs($admin);
        Product::factory(2)->create();
    }

    public function test_soft_deleting(): void
    {
        $product = Product::query()->first();
        $response = $this->deleteJson(route('admin.products.destroy', $product));

        $response->assertOk();
        $this->assertDatabaseHas(Product::class, ['id' => $product->id]);
        $this->assertSoftDeleted(Product::class, ['id' => $product->id]);
    }
}

<?php

namespace Tests\Feature\User\Product;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->getJson(route('user.products.show', $product));

        $response
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $product->id,
                    'title' => $product->title,
                    'description' => $product->description,
                    'type' => $product->type,
                    'price' => $product->price
                ]
            ]);
    }
}

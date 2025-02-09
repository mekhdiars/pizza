<?php

namespace Tests\Feature\User\Product;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetMenuProductsTest extends TestCase
{
    use RefreshDatabase;

    public function test_menu_products(): void
    {
        Product::factory(10)->create();

        $response = $this->getJson(route('user.products.index'));

        $response
            ->assertOk()
            ->assertJsonStructure([
                'pizzas' => [
                    '*' => [
                        'id',
                        'title',
                        'type',
                        'price',
                    ]
                ],
                'drinks' => [
                    '*' => [
                        'id',
                        'title',
                        'type',
                        'price',
                    ]
                ],
            ]);
    }
}

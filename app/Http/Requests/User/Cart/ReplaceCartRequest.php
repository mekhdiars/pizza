<?php

namespace App\Http\Requests\User\Cart;

use App\Enums\LimitProductsInCart;
use App\Enums\ProductType;
use App\Services\User\ProductService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ReplaceCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cart_products' => ['required', 'array'],
            'cart_products.*.product_id' => [
                'required',
                Rule::exists('products', 'id')->whereNull('deleted_at'),
            ],
            'cart_products.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if (!is_array($this->cart_products)) {
                    return;
                }

                $productsCount = (new ProductService())
                    ->getCountProductsByType($this->cart_products);

                if ($productsCount['pizza'] > LimitProductsInCart::PIZZA->value
                    || $productsCount['drink'] > LimitProductsInCart::DRINK->value) {
                    $validator->errors()->add(
                        'cart_products',
                        "Exceeding limit cart products: "
                        . LimitProductsInCart::PIZZA->value . " pizzas and "
                        . LimitProductsInCart::DRINK->value . " drinks"
                    );
                }
            }
        ];
    }
}

<?php

namespace App\Http\Requests\User\Cart;

use App\Enums\LimitProductsInCart;
use App\Enums\ProductType;
use App\Services\CartService;
use Illuminate\Foundation\Http\FormRequest;
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
            'cart_products.*.product_id' => ['required', 'exists:products,id'],
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

                $productsCount = (new CartService())
                    ->getCountProductsByType($this->cart_products);

                if ($productsCount[ProductType::Pizza->value] > LimitProductsInCart::Pizza->value) {
                    $validator->errors()->add(
                        'cart_products',
                        'The limit of pizzas in the basket has been exceeded (maximum {LimitOnProductsInCart::Pizza->value})'
                    );
                }
                if ($productsCount[ProductType::Drink->value] > LimitProductsInCart::Drink->value) {
                    $validator->errors()->add(
                        'cart_products',
                        "The limit of drinks in the basket has been exceeded (maximum {LimitOnProductsInCart::Drink->value})"
                    );
                }
            }
        ];
    }
}

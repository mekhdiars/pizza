<?php

namespace App\Http\Requests\User\Cart;

use App\Enums\LimitProductsInCart;
use App\Enums\ProductType;
use App\Services\ProductService;
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

                $productsCount = (new ProductService())
                    ->getCountProductsByType($this->cart_products);

                if ($productsCount['pizza'] > LimitProductsInCart::Pizza->value
                    || $productsCount['drink'] > LimitProductsInCart::Drink->value) {
                    $validator->errors()->add(
                        'cart_products',
                        "Exceeding limit cart products: "
                        . LimitProductsInCart::Pizza->value . " pizzas and "
                        . LimitProductsInCart::Drink->value . " drinks"
                    );
                }
            }
        ];
    }
}

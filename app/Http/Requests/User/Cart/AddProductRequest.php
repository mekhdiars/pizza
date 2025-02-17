<?php

namespace App\Http\Requests\User\Cart;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => [
                'required',
                Rule::exists('products', 'id')->whereNull('deleted_at'),
            ],
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}

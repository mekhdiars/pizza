<?php

namespace App\Http\Requests\Admin\Product;

use App\Enums\ProductType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255', 'unique:products'],
            'description' => ['nullable', 'string', 'max:65535'],
            'type' => ['required', 'string', Rule::enum(ProductType::class)],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }
}

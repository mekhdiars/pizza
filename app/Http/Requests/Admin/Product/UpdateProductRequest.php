<?php

namespace App\Http\Requests\Admin\Product;

use App\Enums\ProductType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255', Rule::unique('products')->ignore($this->product)],
            'description' => ['nullable', 'string', 'max:65535'],
            'type' => ['nullable', 'string', Rule::enum(ProductType::class)],
            'price' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}

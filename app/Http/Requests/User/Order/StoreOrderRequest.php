<?php

namespace App\Http\Requests\User\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'delivery_address' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-zА-Яа-яЁё\s-]+,\s*\d+[A-Za-zА-Яа-яЁё]*$/'
            ],
            'delivery_time' => ['required', 'date_format:H:i'],
        ];
    }
}

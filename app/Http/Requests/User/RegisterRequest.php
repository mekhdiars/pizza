<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'unique:users', 'regex:/^\+7\d{10}$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'address' => ['nullable', 'string', 'max:255', 'regex:/^[A-Za-zА-Яа-яЁё\s-]+,\s*\d+[A-Za-zА-Яа-яЁё]*$/'],
            'password' => ['required', 'confirmed', 'min:8', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone_number.regex' => 'The phone number must start with +7 and contain exactly 10 digits.',
        ];
    }
}

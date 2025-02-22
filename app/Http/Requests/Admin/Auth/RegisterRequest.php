<?php

namespace App\Http\Requests\Admin\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'],
            'phone_number' => ['required', 'string', 'unique:admins', 'regex:/^\+7\d{10}$/'],
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

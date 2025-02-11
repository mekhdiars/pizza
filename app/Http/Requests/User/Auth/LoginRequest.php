<?php

namespace App\Http\Requests\User\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'login' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL) && !preg_match('/^\+7\d{10}$/', $value)) {
                        $fail('The login must be a valid email or phone number.');
                    }
                }
            ],
            'password' => ['required', 'string']
        ];
    }
}

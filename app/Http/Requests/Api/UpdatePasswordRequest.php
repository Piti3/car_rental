<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('api')->check();
    }

    public function rules(): array
    {
        return [
            'current_password' => 'required|string',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers(),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'Aktualne hasło jest wymagane.',
            'password.required' => 'Nowe hasło jest wymagane.',
            'password.confirmed' => 'Potwierdzenie hasła nie zgadza się.',
            'password.min' => 'Hasło musi mieć co najmniej 8 znaków.',
            'password.mixed_case' => 'Hasło musi zawierać zarówno duże jak i małe litery.',
            'password.numbers' => 'Hasło musi zawierać co najmniej jedną cyfrę.',
        ];
    }
}

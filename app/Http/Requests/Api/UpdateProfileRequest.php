<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('api')->check();
    }

    public function rules(): array
    {
        $userId = auth('api')->id();

        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'phone' => 'nullable|string|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Imię jest wymagane.',
            'email.required' => 'Email jest wymagany.',
            'email.unique' => 'Ten email jest już w użyciu.',
            'phone.max' => 'Numer telefonu nie może być dłuższy niż 20 znaków.',
        ];
    }
}

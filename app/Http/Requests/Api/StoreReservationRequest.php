<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('api')->check();
    }

    public function rules(): array
    {
        return [
            'car_id' => 'required|integer|exists:cars,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'notes' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'car_id.required' => 'ID samochodu jest wymagane.',
            'car_id.exists' => 'Wybrany samochód nie istnieje.',
            'start_date.required' => 'Data rozpoczęcia jest wymagana.',
            'start_date.after_or_equal' => 'Data rozpoczęcia nie może być w przeszłości.',
            'end_date.required' => 'Data zakończenia jest wymagana.',
            'end_date.after' => 'Data zakończenia musi być po dacie rozpoczęcia.',
        ];
    }
}

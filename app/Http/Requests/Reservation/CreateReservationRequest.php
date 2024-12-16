<?php

namespace App\Http\Requests\Reservation;

use Illuminate\Foundation\Http\FormRequest;

class CreateReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo('create-reservation');
    }

    public function rules(): array
    {
        return [
            'fecha' => ['required', 'date', 'after_or_equal:today'],
            'hora' => ['required', 'date_format:H:i'],
            'numero_personas' => ['required', 'integer', 'min:1'],
            'mesa_id' => ['required', 'exists:mesas,id'],
            'notas' => ['nullable', 'string', 'max:255'],
        ];
    }
}

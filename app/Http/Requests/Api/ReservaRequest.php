<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ReservaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
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

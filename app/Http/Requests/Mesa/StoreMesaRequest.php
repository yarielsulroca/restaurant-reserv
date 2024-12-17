<?php

namespace App\Http\Requests\Mesa;

use Illuminate\Foundation\Http\FormRequest;

class StoreMesaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'numero' => ['required', 'integer', 'unique:mesas,numero'],
            'capacidad' => ['required', 'integer', 'min:1', 'max:20'],
            'zona' => ['required', 'string', 'in:Interior,Exterior,Terraza'],
            'ubicacion' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:available,occupied,reserved'],
        ];
    }
}

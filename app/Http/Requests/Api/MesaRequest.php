<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class MesaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'numero' => ['required', 'integer', 'unique:mesas,numero,' . $this->mesa?->id],
            'capacidad' => ['required', 'integer', 'min:1'],
            'zona' => ['required', 'string'],
            'ubicacion' => ['required', 'string'],
            'status' => ['sometimes', 'string', 'in:available,occupied,reserved'],
            'estado' => ['sometimes', 'string', 'in:disponible,ocupado,reservado'],
        ];
    }
}

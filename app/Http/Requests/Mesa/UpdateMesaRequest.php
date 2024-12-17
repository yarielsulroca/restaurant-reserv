<?php

namespace App\Http\Requests\Mesa;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMesaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'numero' => ['sometimes', 'integer', Rule::unique('mesas')->ignore($this->mesa)],
            'capacidad' => ['sometimes', 'integer', 'min:1', 'max:20'],
            'zona' => ['sometimes', 'string', 'in:Interior,Exterior,Terraza'],
            'ubicacion' => ['nullable', 'string', 'max:255'],
            'status' => ['sometimes', 'string', 'in:available,occupied,reserved'],
        ];
    }
}

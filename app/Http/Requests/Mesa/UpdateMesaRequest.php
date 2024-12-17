<?php

namespace App\Http\Requests\Mesa;

use App\Models\Mesa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateMesaRequest",
 *     title="Update Mesa Request",
 *     @OA\Property(property="numero", type="integer", example=1),
 *     @OA\Property(property="capacidad", type="integer", minimum=1, maximum=20, example=4),
 *     @OA\Property(property="zona", type="string", enum={"A", "B", "C", "D"}, example="A"),
 *     @OA\Property(property="status", type="string", enum={"available", "occupied", "reserved", "maintenance"}, example="available"),
 *     @OA\Property(property="estado", type="string", enum={"disponible", "ocupada", "reservada", "mantenimiento"}, example="disponible")
 * )
 */
class UpdateMesaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'numero' => ['sometimes', 'integer', 'min:1', Rule::unique('mesas')->ignore($this->mesa)],
            'capacidad' => ['sometimes', 'integer', 'min:1', 'max:20'],
            'zona' => ['sometimes', 'string', 'in:A,B,C,D'],
            'status' => ['sometimes', 'string', 'in:' . implode(',', [
                Mesa::STATUS_AVAILABLE,
                Mesa::STATUS_OCCUPIED,
                Mesa::STATUS_RESERVED,
                Mesa::STATUS_MAINTENANCE,
            ])],
            'estado' => ['sometimes', 'string', 'in:' . implode(',', [
                Mesa::ESTADO_DISPONIBLE,
                Mesa::ESTADO_OCUPADA,
                Mesa::ESTADO_RESERVADA,
                Mesa::ESTADO_MANTENIMIENTO,
            ])],
        ];
    }

    public function messages(): array
    {
        return [
            'numero.integer' => 'El número de mesa debe ser un número entero',
            'numero.min' => 'El número de mesa debe ser mayor a 0',
            'numero.unique' => 'Este número de mesa ya está en uso',
            'capacidad.integer' => 'La capacidad debe ser un número entero',
            'capacidad.min' => 'La capacidad mínima es 1 persona',
            'capacidad.max' => 'La capacidad máxima es 20 personas',
            'zona.in' => 'La zona debe ser A, B, C o D',
            'status.in' => 'El estado no es válido',
            'estado.in' => 'El estado en español no es válido',
        ];
    }
}
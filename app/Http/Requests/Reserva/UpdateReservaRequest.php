<?php

namespace App\Http\Requests\Reserva;

use App\Services\Reserva\ReservaService;
use Illuminate\Foundation\Http\FormRequest;

class UpdateReservaRequest extends FormRequest
{
    public function __construct(
        private readonly ReservaService $reservaService
    ) {
        parent::__construct();
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mesa_id' => ['sometimes', 'exists:mesas,id'],
            'fecha' => ['sometimes', 'date', 'after_or_equal:today'],
            'hora' => ['sometimes', 'date_format:H:i'],
            'numero_personas' => ['sometimes', 'integer', 'min:1'],
            'status' => ['sometimes', 'string', 'in:pending,confirmed,cancelled'],
            'notas' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (
                ($this->has('fecha') || $this->has('hora')) &&
                !$this->reservaService->validateReservationTime($this->fecha ?? $this->reserva->fecha, $this->hora ?? $this->reserva->hora)
            ) {
                $validator->errors()->add('hora', 'La hora de reserva no es válida. Las reservas solo se pueden hacer entre las 12:00 y las 23:00.');
            }

            if (
                ($this->has('mesa_id') || $this->has('fecha') || $this->has('hora')) &&
                !$this->reservaService->isTableAvailable(
                    $this->mesa_id ?? $this->reserva->mesa_id,
                    $this->fecha ?? $this->reserva->fecha,
                    $this->hora ?? $this->reserva->hora
                )
            ) {
                $validator->errors()->add('mesa_id', 'La mesa no está disponible para la fecha y hora seleccionadas.');
            }
        });
    }
}

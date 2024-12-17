<?php

namespace App\Http\Requests\Reserva;

use App\Models\Mesa;
use App\Models\Reserva;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class ReservationStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'mesa_ids' => ['required', 'array', 'max:3'],
            'mesa_ids.*' => ['exists:mesas,id'],
            'fecha' => ['required', 'date', 'after_or_equal:today'],
            'hora' => ['required', 'date_format:H:i'],
            'numero_personas' => ['required', 'integer', 'min:1'],
            'duracion' => ['required', 'integer', 'min:30', 'max:180', 'multiple_of:30'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->validateReservationTime($validator);
            $this->validateTableAvailability($validator);
        });
    }

    protected function validateReservationTime($validator)
    {
        $dateTime = Carbon::parse($this->fecha . ' ' . $this->hora);
        $dayOfWeek = $dateTime->dayOfWeek;

        $isValidTime = match($dayOfWeek) {
            // Lunes a Viernes (1-5)
            1, 2, 3, 4, 5 => $dateTime->between(
                $dateTime->copy()->setTime(10, 0),
                $dateTime->copy()->setTime(24, 0)
            ),
            // Sábado (6)
            6 => $dateTime->between(
                $dateTime->copy()->setTime(22, 0),
                $dateTime->copy()->addDay()->setTime(2, 0)
            ),
            // Domingo (0)
            0 => $dateTime->between(
                $dateTime->copy()->setTime(12, 0),
                $dateTime->copy()->setTime(16, 0)
            ),
            default => false
        };

        if (!$isValidTime) {
            $validator->errors()->add('hora', 'El horario seleccionado no está disponible para este día.');
        }
    }

    protected function validateTableAvailability($validator)
    {
        $startTime = Carbon::parse($this->fecha . ' ' . $this->hora);
        $endTime = $startTime->copy()->addMinutes($this->duracion);

        // Verificar si las mesas están disponibles
        $conflictingReservations = Reserva::query()
            ->whereHas('mesas', function ($query) {
                $query->whereIn('mesa_id', $this->mesa_ids);
            })
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('hora_inicio', [$startTime, $endTime])
                    ->orWhereBetween('hora_fin', [$startTime, $endTime]);
            })
            ->exists();

        if ($conflictingReservations) {
            $validator->errors()->add(
                'mesa_ids',
                'Una o más mesas no están disponibles en el horario seleccionado.'
            );
        }

        // Verificar capacidad total de las mesas
        $totalCapacity = Mesa::whereIn('id', $this->mesa_ids)
            ->sum('capacidad');

        if ($this->numero_personas > $totalCapacity) {
            $validator->errors()->add(
                'numero_personas',
                'La cantidad de personas excede la capacidad total de las mesas seleccionadas.'
            );
        }
    }
}
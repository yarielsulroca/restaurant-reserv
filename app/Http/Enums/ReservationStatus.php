<?php

namespace App\Http\Enums;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ReservationStatus",
 *     type="string",
 *     enum={"pendiente", "confirmada", "cancelada", "completada", "expirada"},
 *     description="Estados posibles de una reserva"
 * )
 */
enum ReservationStatus: string
{
    case PENDIENTE = 'pendiente';
    case CONFIRMADA = 'confirmada';
    case CANCELADA = 'cancelada';
    case COMPLETADA = 'completada';
    case EXPIRADA = 'expirada';

    public function label(): string
    {
        return match($this) {
            self::PENDIENTE => 'Pendiente',
            self::CONFIRMADA => 'Confirmada',
            self::CANCELADA => 'Cancelada',
            self::COMPLETADA => 'Completada',
            self::EXPIRADA => 'Expirada',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDIENTE => 'warning',
            self::CONFIRMADA => 'success',
            self::CANCELADA => 'danger',
            self::COMPLETADA => 'info',
            self::EXPIRADA => 'secondary',
        };
    }
}
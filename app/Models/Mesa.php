<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Mesa",
 *     title="Mesa",
 *     description="Modelo de mesa del restaurante",
 *     @OA\Property(property="id", type="integer", format="int64", example=1, description="ID único de la mesa"),
 *     @OA\Property(property="numero", type="integer", example=1, description="Número identificador de la mesa"),
 *     @OA\Property(property="capacidad", type="integer", example=4, description="Capacidad de personas en la mesa"),
 *     @OA\Property(property="zona", type="string", enum={"A", "B", "C", "D"}, example="A", description="Zona donde está ubicada la mesa"),
 *     @OA\Property(property="ubicacion", type="string", example="Exterior", description="Descripción de la ubicación"),
 *     @OA\Property(property="status", type="string", enum={"available", "occupied", "reserved", "maintenance"}, example="available", description="Estado en inglés de la mesa"),
 *     @OA\Property(property="estado", type="string", enum={"disponible", "ocupada", "reservada", "mantenimiento"}, example="disponible", description="Estado en español de la mesa"),
 *     @OA\Property(property="status_text", type="string", example="Disponible", description="Texto formateado del estado"),
 *     @OA\Property(property="status_class", type="string", example="bg-success", description="Clase CSS para el estado"),
 *     @OA\Property(property="formatted_ubicacion", type="string", example="Zona A - Exterior", description="Ubicación formateada"),
 *     @OA\Property(property="created_at", type="string", format="datetime", description="Fecha de creación"),
 *     @OA\Property(property="updated_at", type="string", format="datetime", description="Fecha de última actualización"),
 *     @OA\Property(
 *         property="reservas",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Reserva"),
 *         description="Reservas asociadas a la mesa"
 *     )
 * )
 */
class Mesa extends Model
{
    use HasFactory;

    public const STATUS_AVAILABLE = 'available';
    public const STATUS_OCCUPIED = 'occupied';
    public const STATUS_RESERVED = 'reserved';
    public const STATUS_MAINTENANCE = 'maintenance';

    public const ESTADO_DISPONIBLE = 'disponible';
    public const ESTADO_OCUPADA = 'ocupada';
    public const ESTADO_RESERVADA = 'reservada';
    public const ESTADO_MANTENIMIENTO = 'mantenimiento';

    protected $fillable = [
        'numero',
        'capacidad',
        'zona',
        'ubicacion',
        'status',
        'estado'
    ];

    protected $attributes = [
        'status' => self::STATUS_AVAILABLE,
        'estado' => self::ESTADO_DISPONIBLE
    ];

    protected $casts = [
        'capacidad' => 'integer',
    ];

    protected $appends = ['status_text', 'status_class', 'formatted_ubicacion'];

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }

    public function getStatusTextAttribute(): string
    {
        if ($this->estado === self::ESTADO_OCUPADA) {
            return 'Ocupada';
        }

        return match($this->status) {
            self::STATUS_AVAILABLE => 'Disponible',
            self::STATUS_OCCUPIED => 'Ocupada',
            self::STATUS_RESERVED => 'Reservada',
            self::STATUS_MAINTENANCE => 'Mantenimiento',
            default => 'Desconocido'
        };
    }

    public function getStatusClassAttribute(): string
    {
        return match($this->status) {
            self::STATUS_AVAILABLE => 'bg-success',
            self::STATUS_OCCUPIED => 'bg-danger',
            self::STATUS_RESERVED => 'bg-warning',
            self::STATUS_MAINTENANCE => 'bg-secondary',
            default => 'bg-light'
        };
    }

    public function hasReservations(): bool
    {
        return $this->reservas()->exists();
    }

    public function getFormattedUbicacionAttribute(): string
    {
        return match($this->zona) {
            'A' => 'Zona A - Exterior',
            'B' => 'Zona B - Interior',
            'C' => 'Zona C - Terraza',
            'D' => 'Zona D - VIP',
            default => $this->ubicacion ?? 'Sin ubicación'
        };
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($mesa) {
            $mesa->ubicacion = match($mesa->zona) {
                'A' => 'Exterior',
                'B' => 'Interior',
                'C' => 'Terraza',
                'D' => 'VIP',
                default => 'Interior'
            };
        });
    }
}
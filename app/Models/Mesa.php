<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Mesa",
 *     required={"numero", "capacidad", "zona", "status"},
 *     @OA\Property(property="id", type="integer", format="int64", description="ID único de la mesa"),
 *     @OA\Property(property="numero", type="integer", description="Número identificador de la mesa"),
 *     @OA\Property(property="capacidad", type="integer", description="Capacidad de personas en la mesa"),
 *     @OA\Property(property="zona", type="string", enum={"A", "B", "C"}, description="Zona donde está ubicada la mesa"),
 *     @OA\Property(property="ubicacion", type="string", description="Descripción de la ubicación"),
 *     @OA\Property(property="status", type="string", enum={"available", "occupied", "reserved", "maintenance"}, description="Estado de la mesa en inglés"),
 *     @OA\Property(property="estado", type="string", enum={"disponible", "ocupada", "reservada", "mantenimiento"}, description="Estado de la mesa en español"),
 *     @OA\Property(property="created_at", type="string", format="datetime", description="Fecha de creación"),
 *     @OA\Property(property="updated_at", type="string", format="datetime", description="Fecha de última actualización")
 * )
 */
class Mesa extends Model
{
    use HasFactory;

    protected $table = 'mesas';

    protected $fillable = [
        'numero',
        'capacidad',
        'zona',
        'ubicacion',
        'status',
    ];

    protected $attributes = [
        'status' => 'available',
    ];

    protected $appends = [
        'estado',
        'status_text',
        'status_class',
        'formatted_ubicacion',
    ];

    protected $casts = [
        'numero' => 'integer',
        'capacidad' => 'integer',
    ];

    /**
     * Obtiene las reservas asociadas a la mesa.
     */
    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }

    /**
     * Obtiene el texto del estado en español.
     */
    public function getEstadoAttribute(): string
    {
        return match ($this->status) {
            'available' => 'disponible',
            'occupied' => 'ocupada',
            'reserved' => 'reservada',
            'maintenance' => 'mantenimiento',
            default => $this->status,
        };
    }

    /**
     * Obtiene el texto formateado del estado.
     */
    public function getStatusTextAttribute(): string
    {
        return ucfirst($this->estado);
    }

    /**
     * Obtiene la clase CSS para el estado.
     */
    public function getStatusClassAttribute(): string
    {
        return match ($this->status) {
            'available' => 'bg-success',
            'occupied' => 'bg-danger',
            'reserved' => 'bg-warning',
            'maintenance' => 'bg-secondary',
            default => 'bg-info',
        };
    }

    /**
     * Verifica si la mesa tiene reservas.
     */
    public function hasReservations(): bool
    {
        return $this->reservas()->count() > 0;
    }

    /**
     * Obtiene la ubicación formateada.
     */
    public function getFormattedUbicacionAttribute(): string
    {
        return "Zona {$this->zona}" . ($this->ubicacion ? " - {$this->ubicacion}" : '');
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function (Mesa $mesa) {
            if (!$mesa->status) {
                $mesa->status = 'available';
            }
        });
    }
}
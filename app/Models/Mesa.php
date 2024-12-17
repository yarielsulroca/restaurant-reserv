<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mesa extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'capacidad',
        'zona',
        'ubicacion',
        'status',
        'estado'
    ];

    protected $attributes = [
        'status' => 'available',
        'estado' => 'disponible'
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
        if ($this->estado === 'ocupada') {
            return 'Ocupada';
        }

        return match($this->status) {
            'available' => 'Disponible',
            'occupied' => 'Ocupada',
            'reserved' => 'Reservada',
            'maintenance' => 'Mantenimiento',
            default => 'Desconocido'
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
            // Asignar ubicación basada en la zona
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
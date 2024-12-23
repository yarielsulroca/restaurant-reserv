<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

/**
 * @OA\Schema(
 *     schema="Reserva",
 *     required={"user_id", "fecha", "hora", "hora_fin", "numero_personas", "status"},
 *     @OA\Property(property="id", type="integer", format="int64", description="ID de la reserva"),
 *     @OA\Property(property="user_id", type="integer", format="int64", description="ID del usuario"),
 *     @OA\Property(property="mesa_id", type="integer", format="int64", description="ID de la mesa"),
 *     @OA\Property(property="fecha", type="string", format="date", description="Fecha de la reserva"),
 *     @OA\Property(property="hora", type="string", format="time", description="Hora de inicio de la reserva"),
 *     @OA\Property(property="hora_fin", type="string", format="time", description="Hora de fin de la reserva"),
 *     @OA\Property(property="numero_personas", type="integer", description="Número de personas"),
 *     @OA\Property(property="status", type="string", enum={"pending", "confirmed", "cancelled", "completed", "expired"}, description="Estado de la reserva"),
 *     @OA\Property(property="notas", type="string", description="Notas adicionales"),
 *     @OA\Property(property="expired", type="boolean", description="Indica si la reserva ha expirado"),
 *     @OA\Property(property="created_at", type="string", format="datetime", description="Fecha de creación"),
 *     @OA\Property(property="updated_at", type="string", format="datetime", description="Fecha de última actualización")
 * )
 */
class Reserva extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $table = 'reservas';

    protected $fillable = [
        'fecha',
        'hora',
        'hora_fin',
        'numero_personas',
        'mesa_id',
        'user_id',
        'status',
        'notas',
        'expired',
    ];

    protected $attributes = [
        'status' => 'pending',
        'expired' => false,
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora' => 'time',
        'hora_fin' => 'time',
        'numero_personas' => 'integer',
        'expired' => 'boolean',
    ];

    /**
     * Relación con la tabla Mesa.
     */
    public function mesa(): BelongsTo
    {
        return $this->belongsTo(Mesa::class);
    }

    /**
     * Relación con la tabla User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

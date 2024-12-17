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
 *     required={"user_id", "hora_inicio", "hora_fin", "numero_personas", "estado"},
 *     @OA\Property(property="id", type="integer", format="int64", description="ID de la reserva"),
 *     @OA\Property(property="user_id", type="integer", format="int64", description="ID del usuario"),
 *     @OA\Property(property="hora_inicio", type="string", format="datetime", description="Hora de inicio de la reserva"),
 *     @OA\Property(property="hora_fin", type="string", format="datetime", description="Hora de fin de la reserva"),
 *     @OA\Property(property="numero_personas", type="integer", description="Número de personas"),
 *     @OA\Property(property="estado", type="string", enum={"pendiente", "confirmada", "cancelada", "completada", "expirada"}, description="Estado de la reserva"),
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
        'hora' => 'datetime',
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

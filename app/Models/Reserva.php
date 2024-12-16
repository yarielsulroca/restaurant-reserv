<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

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

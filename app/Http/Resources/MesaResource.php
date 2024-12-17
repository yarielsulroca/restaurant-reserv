<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MesaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'numero' => $this->numero,
            'capacidad' => $this->capacidad,
            'zona' => $this->zona,
            'ubicacion' => $this->ubicacion,
            'status' => $this->status,
            'has_reservations' => $this->whenLoaded('reservations', fn() => $this->hasReservations()),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

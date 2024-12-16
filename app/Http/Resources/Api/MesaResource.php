<?php

namespace App\Http\Resources\Api;

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
            'estado' => $this->estado,
            'reservas' => ReservaResource::collection($this->whenLoaded('reservas')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

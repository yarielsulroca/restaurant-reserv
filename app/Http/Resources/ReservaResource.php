<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'mesa' => new MesaResource($this->whenLoaded('mesa')),
            'user' => new UserResource($this->whenLoaded('user')),
            'fecha' => $this->fecha,
            'hora' => $this->hora,
            'numero_personas' => $this->numero_personas,
            'status' => $this->status,
            'notas' => $this->notas,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
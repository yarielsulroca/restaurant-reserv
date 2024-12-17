<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="MesaResource",
 *     title="Mesa Resource",
 *     description="Recurso para la transformación de mesas"
 * )
 */
class MesaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
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
            'status_text' => $this->status_text,
            'status_class' => $this->status_class,
            'formatted_ubicacion' => $this->formatted_ubicacion,
            'reservas' => ReservaResource::collection($this->whenLoaded('reservas')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        return [
            'success' => true,
            'message' => 'Mesa recuperada exitosamente'
        ];
    }
}
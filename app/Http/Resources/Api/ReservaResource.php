<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ReservaResource",
 *     title="Reserva Resource",
 *     description="Recurso para la transformación de reservas"
 * )
 */
class ReservaResource extends JsonResource
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
            'fecha' => $this->fecha?->format('Y-m-d'),
            'hora' => $this->hora?->format('H:i'),
            'numero_personas' => $this->numero_personas,
            'estado' => $this->estado,
            'status_text' => $this->status_text,
            'status_class' => $this->status_class,
            'observaciones' => $this->observaciones,
            'cliente' => new UserResource($this->whenLoaded('cliente')),
            'mesa' => new MesaResource($this->whenLoaded('mesa')),
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
            'message' => 'Reserva recuperada exitosamente'
        ];
    }
}
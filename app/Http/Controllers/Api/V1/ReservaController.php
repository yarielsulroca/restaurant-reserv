<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reserva\StoreReservaRequest;
use App\Http\Requests\Reserva\UpdateReservaRequest;
use App\Http\Resources\Api\ReservaCollection;
use App\Http\Resources\Api\ReservaResource;
use App\Models\Reserva;
use App\Services\Reserva\ReservaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;


/**
 * @OA\Tag(
 *     name="Reservas",
 *     description="API Endpoints de reservas del restaurante"
 * )
 */
class ReservaController extends Controller
{
    public function __construct(
        private readonly ReservaService $reservaService
    ) {
        $this->middleware('permission:view reservations')->only(['index', 'show']);
        $this->middleware('permission:create reservations')->only(['store']);
        $this->middleware('permission:edit reservations')->only(['update']);
        $this->middleware('permission:delete reservations')->only(['destroy']);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/reservas",
     *     summary="Lista todas las reservas",
     *     tags={"Reservas"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(name="fecha", in="query", @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="estado", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="mesa_id", in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Lista de reservas",
     *         @OA\JsonContent(ref="#/components/schemas/ReservaCollection")
     *     ),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function index(Request $request): ReservaCollection
    {
        $reservas = $this->reservaService->list(
            filters: $request->only(['fecha', 'estado', 'mesa_id']),
            perPage: $request->input('per_page', 10)
        );

        return new ReservaCollection($reservas);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/reservas",
     *     summary="Crea una nueva reserva",
     *     tags={"Reservas"},
     *     security={{ "sanctum": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreReservaRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Reserva creada exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/ReservaResource")
     *     ),
     *     @OA\Response(response=422, description="Error de validación")
     * )
     */
    public function store(StoreReservaRequest $request): ReservaResource
    {
        $reserva = $this->reservaService->create($request->validated());
        return new ReservaResource($reserva);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/reservas/{reserva}",
     *     summary="Muestra una reserva específica",
     *     tags={"Reservas"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="reserva",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reserva encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/ReservaResource")
     *     ),
     *     @OA\Response(response=404, description="Reserva no encontrada")
     * )
     */
    public function show(Reserva $reserva): ReservaResource
    {
        return new ReservaResource($reserva);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/reservas/{reserva}",
     *     summary="Actualiza una reserva existente",
     *     tags={"Reservas"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="reserva",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateReservaRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reserva actualizada",
     *         @OA\JsonContent(ref="#/components/schemas/ReservaResource")
     *     ),
     *     @OA\Response(response=404, description="Reserva no encontrada"),
     *     @OA\Response(response=422, description="Error de validación")
     * )
     */
    public function update(UpdateReservaRequest $request, Reserva $reserva): ReservaResource
    {
        $reserva = $this->reservaService->update($reserva, $request->validated());
        return new ReservaResource($reserva);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/reservas/{reserva}",
     *     summary="Elimina una reserva",
     *     tags={"Reservas"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="reserva",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Reserva eliminada"),
     *     @OA\Response(response=404, description="Reserva no encontrada")
     * )
     */
    public function destroy(Reserva $reserva): JsonResponse
    {
        $this->reservaService->delete($reserva);
        return response()->json(null, 204);
    }
}

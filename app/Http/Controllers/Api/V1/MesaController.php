<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mesa\StoreMesaRequest;
use App\Http\Requests\Mesa\UpdateMesaRequest;
use App\Http\Resources\Api\MesaCollection;
use App\Http\Resources\Api\MesaResource;
use App\Models\Mesa;
use App\Services\Mesa\MesaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Mesas",
 *     description="API Endpoints de mesas del restaurante"
 * )
 */
class MesaController extends Controller
{
    public function __construct(
        private readonly MesaService $mesaService
    ) {
        $this->middleware('permission:view tables')->only(['index', 'show']);
        $this->middleware('permission:create tables')->only(['store']);
        $this->middleware('permission:edit tables')->only(['update']);
        $this->middleware('permission:delete tables')->only(['destroy']);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/mesas",
     *     summary="Lista todas las mesas",
     *     tags={"Mesas"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(name="zona", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="status", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="capacidad", in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Lista de mesas",
     *         @OA\JsonContent(ref="#/components/schemas/MesaCollection")
     *     ),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function index(Request $request): MesaCollection
    {
        $mesas = $this->mesaService->list(
            filters: $request->only(['zona', 'status', 'capacidad']),
            perPage: $request->input('per_page', 10)
        );

        return new MesaCollection($mesas);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/mesas",
     *     summary="Crea una nueva mesa",
     *     tags={"Mesas"},
     *     security={{ "sanctum": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreMesaRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Mesa creada exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/MesaResource")
     *     ),
     *     @OA\Response(response=422, description="Error de validación")
     * )
     */
    public function store(StoreMesaRequest $request): MesaResource
    {
        $mesa = $this->mesaService->create($request->validated());
        return new MesaResource($mesa);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/mesas/{mesa}",
     *     summary="Muestra una mesa específica",
     *     tags={"Mesas"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="mesa",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Mesa encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/MesaResource")
     *     ),
     *     @OA\Response(response=404, description="Mesa no encontrada")
     * )
     */
    public function show(Mesa $mesa): MesaResource
    {
        return new MesaResource($mesa);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/mesas/{mesa}",
     *     summary="Actualiza una mesa existente",
     *     tags={"Mesas"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="mesa",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateMesaRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Mesa actualizada",
     *         @OA\JsonContent(ref="#/components/schemas/MesaResource")
     *     ),
     *     @OA\Response(response=404, description="Mesa no encontrada"),
     *     @OA\Response(response=422, description="Error de validación")
     * )
     */
    public function update(UpdateMesaRequest $request, Mesa $mesa): MesaResource
    {
        $mesa = $this->mesaService->update($mesa, $request->validated());
        return new MesaResource($mesa);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/mesas/{mesa}",
     *     summary="Elimina una mesa",
     *     tags={"Mesas"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="mesa",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Mesa eliminada"),
     *     @OA\Response(response=404, description="Mesa no encontrada")
     * )
     */
    public function destroy(Mesa $mesa): JsonResponse
    {
        $this->mesaService->delete($mesa);
        return response()->json(null, 204);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/mesas/available",
     *     summary="Lista las mesas disponibles",
     *     tags={"Mesas"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(name="date", in="query", required=true, @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="time", in="query", required=true, @OA\Schema(type="string", format="time")),
     *     @OA\Parameter(name="guests", in="query", required=true, @OA\Schema(type="integer", minimum=1)),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de mesas disponibles",
     *         @OA\JsonContent(ref="#/components/schemas/MesaCollection")
     *     ),
     *     @OA\Response(response=422, description="Error de validación")
     * )
     */
    public function available(Request $request): MesaCollection
    {
        $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time' => ['required', 'date_format:H:i'],
            'guests' => ['required', 'integer', 'min:1'],
        ]);

        $mesas = $this->mesaService->getAvailableTables(
            date: $request->input('date'),
            time: $request->input('time'),
            guests: $request->input('guests')
        );

        return new MesaCollection($mesas);
    }
}
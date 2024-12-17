<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mesa\StoreMesaRequest;
use App\Http\Requests\Mesa\UpdateMesaRequest;
use App\Http\Resources\MesaResource;
use App\Models\Mesa;
use App\Services\Mesa\MesaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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

    public function index(Request $request): AnonymousResourceCollection
    {
        $mesas = $this->mesaService->list(
            filters: $request->only(['zona', 'status', 'capacidad']),
            perPage: $request->input('per_page', 10)
        );

        return MesaResource::collection($mesas);
    }

    public function store(StoreMesaRequest $request): MesaResource
    {
        $mesa = $this->mesaService->create($request->validated());
        return new MesaResource($mesa);
    }

    public function show(Mesa $mesa): MesaResource
    {
        return new MesaResource($mesa);
    }

    public function update(UpdateMesaRequest $request, Mesa $mesa): MesaResource
    {
        $mesa = $this->mesaService->update($mesa, $request->validated());
        return new MesaResource($mesa);
    }

    public function destroy(Mesa $mesa): JsonResponse
    {
        $this->mesaService->delete($mesa);
        return response()->json(null, 204);
    }

    public function available(Request $request): AnonymousResourceCollection
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

        return MesaResource::collection($mesas);
    }
}

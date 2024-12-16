<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MesaRequest;
use App\Http\Resources\Api\MesaResource;
use App\Models\Mesa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MesaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('role:admin')->except(['index', 'show', 'available']);
    }

    public function index(): AnonymousResourceCollection
    {
        $mesas = Mesa::with('reservas')->get();
        return MesaResource::collection($mesas);
    }

    public function show(Mesa $mesa): MesaResource
    {
        $mesa->load('reservas');
        return new MesaResource($mesa);
    }

    public function store(MesaRequest $request): MesaResource
    {
        $mesa = Mesa::create($request->validated());
        return new MesaResource($mesa);
    }

    public function update(MesaRequest $request, Mesa $mesa): MesaResource
    {
        $mesa->update($request->validated());
        return new MesaResource($mesa);
    }

    public function destroy(Mesa $mesa): JsonResponse
    {
        $mesa->delete();
        return response()->json(['message' => 'Mesa deleted successfully']);
    }

    public function available(): AnonymousResourceCollection
    {
        $mesas = Mesa::where('status', 'available')
            ->where('estado', 'disponible')
            ->get();
        
        return MesaResource::collection($mesas);
    }
}

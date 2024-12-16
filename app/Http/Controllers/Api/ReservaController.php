<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReservaRequest;
use App\Http\Resources\Api\ReservaResource;
use App\Models\Reserva;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class ReservaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('role:user')->only(['store']);
    }

    public function index(): AnonymousResourceCollection
    {
        $reservas = Auth::user()->hasRole('admin') 
            ? Reserva::with(['user', 'mesa'])->get()
            : Reserva::where('user_id', Auth::id())->with(['mesa'])->get();

        return ReservaResource::collection($reservas);
    }

    public function show(Reserva $reserva): ReservaResource|JsonResponse
    {
        if (!Auth::user()->hasRole('admin') && $reserva->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $reserva->load(['user', 'mesa']);
        return new ReservaResource($reserva);
    }

    public function store(ReservaRequest $request): ReservaResource
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending';
        
        $reserva = Reserva::create($data);
        $reserva->load(['user', 'mesa']);
        
        return new ReservaResource($reserva);
    }

    public function update(ReservaRequest $request, Reserva $reserva): ReservaResource|JsonResponse
    {
        if (Auth::user()->hasRole('admin')) {
            $reserva->update($request->validated());
        } elseif ($reserva->user_id === Auth::id() && $reserva->status === 'pending') {
            $reserva->update($request->validated());
        } else {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $reserva->load(['user', 'mesa']);
        return new ReservaResource($reserva);
    }

    public function destroy(Reserva $reserva): JsonResponse
    {
        if (!Auth::user()->hasRole('admin') && $reserva->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $reserva->delete();
        return response()->json(['message' => 'Reservation cancelled successfully']);
    }

    public function updateStatus(Reserva $reserva, string $status): ReservaResource|JsonResponse
    {
        if (!Auth::user()->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (!in_array($status, ['pending', 'confirmed', 'cancelled'])) {
            return response()->json(['message' => 'Invalid status'], 422);
        }

        $reserva->update(['status' => $status]);
        $reserva->load(['user', 'mesa']);
        
        return new ReservaResource($reserva);
    }
}

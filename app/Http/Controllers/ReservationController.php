<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reservation\CreateReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Reserva;
use App\Services\ReservationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ReservationController extends Controller
{
    protected $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
        $this->middleware(['auth', 'role:user']);
        $this->middleware('can:create-reservation')->only(['store']);
        $this->middleware('can:delete-own-reservation')->only(['destroy']);
    }

    public function index(): AnonymousResourceCollection
    {
        $reservations = $this->reservationService->getUserReservations(auth()->id());
        return ReservationResource::collection($reservations);
    }

    public function store(CreateReservationRequest $request): ReservationResource
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        
        $reservation = $this->reservationService->create($data);
        return new ReservationResource($reservation);
    }

    public function destroy(Reserva $reserva): JsonResponse
    {
        // Verify if the reservation belongs to the user
        if ($reserva->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $this->reservationService->delete($reserva);
        return response()->json(['message' => 'Reservation deleted successfully']);
    }
}

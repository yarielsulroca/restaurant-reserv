<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReservationResource;
use App\Models\Reserva;
use App\Services\ReservationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReservationManagementController extends Controller
{
    protected $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
        $this->middleware(['auth', 'role:admin']);
        $this->middleware('can:update-reservations');
    }

    public function update(Request $request, Reserva $reserva): ReservationResource
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,confirmed,cancelled'],
            'notas' => ['nullable', 'string', 'max:255'],
        ]);

        $reservation = $this->reservationService->update($reserva, $validated);
        return new ReservationResource($reservation);
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reserva\ReservationStoreRequest;
use App\Models\Reserva;
use App\Models\Mesa;
use App\Notifications\ReservationStatusChanged;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API de Reservas",
 *     description="API para gestionar reservas de restaurante"
 * )
 */
class ReservaController extends Controller
{
   /**
    * @OA\Get(
    *     path="/api/v1/reservas",
    *     summary="Get a list of reservas",
    *     tags={"Reservas"},
    *     @OA\Response(
    *         response=200,
    *         description="Successful operation",
    *         @OA\JsonContent(
    *             type="array",
    *             @OA\Items(ref="#/components/schemas/Reserva")
    *         )
    *     ),
    *     @OA\Response(
    *         response=401,
    *         description="Unauthorized"
    *     ),
    *     @OA\Response(
    *         response=403,
    *         description="Forbidden"
    *     ),
    *     @OA\Response(
    *         response=404,
    *         description="Not Found"
    *     )
    * )
    */
    public function index(): JsonResponse
    {
        $reservas = auth()->user()->hasRole('admin') 
            ? Reserva::with(['user', 'mesas'])->get()
            : Reserva::with('mesas')->where('user_id', auth()->id())->get();

        return response()->json([
            'reservas' => $reservas
        ]);
    }

   /**
 * @OA\Post(
 *     path="/api/v1/reservas",
 *     summary="Crear una nueva reserva",
 *     tags={"Reservas"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"hora_inicio", "hora_fin", "numero_personas"},
 *             @OA\Property(property="hora_inicio", type="string", format="datetime", example="2024-12-17 10:00:00"),
 *             @OA\Property(property="hora_fin", type="string", format="datetime", example="2024-12-17 11:00:00"),
 *             @OA\Property(property="numero_personas", type="integer", example=4)
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Reserva creada exitosamente",
 *         @OA\JsonContent(ref="#/components/schemas/Reserva")
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="No autorizado"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Error de validación"
 *     )
 * )
 */
    public function store(ReservationStoreRequest $request): JsonResponse
    {
        $validated = $request->validated();
        
        // Verificar disponibilidad de mesas
        $mesas = Mesa::whereIn('id', $validated['mesa_ids'])->get();
        
        // Calcular capacidad total
        $capacidadTotal = $mesas->sum('capacidad');
        if ($validated['numero_personas'] > $capacidadTotal) {
            return response()->json([
                'message' => 'La cantidad de personas excede la capacidad de las mesas'
            ], 422);
        }

        // Crear la reserva
        $reserva = Reserva::create([
            'user_id' => auth()->id(),
            'hora_inicio' => Carbon::parse($validated['fecha'])->setTimeFromTimeString($validated['hora']),
            'hora_fin' => Carbon::parse($validated['fecha'])
                ->setTimeFromTimeString($validated['hora'])
                ->addMinutes($validated['duracion']),
            'numero_personas' => $validated['numero_personas'],
            'estado' => 'pendiente',
        ]);

        // Asociar mesas
        $reserva->mesas()->attach($validated['mesa_ids']);

        // Notificar al usuario
        $reserva->user->notify(new ReservationStatusChanged($reserva, 'nuevo'));

        return response()->json([
            'message' => 'Reserva creada exitosamente',
            'reserva' => $reserva->load('mesas')
        ], 201);
    }

   /**
    * @OA\Get(
    *     path="/api/v1/reservas/{id}",
    *     summary="Ver detalle de reserva",
    *     tags={"Reservas"},
    *     @OA\Parameter(
    *         name="id",
    *         in="path",
    *         required=true,
    *         description="ID de la reserva",
    *         @OA\Schema(type="integer")
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Detalle de la reserva",
    *         @OA\JsonContent(ref="#/components/schemas/Reserva")
    *     ),
    *     @OA\Response(
    *         response=401,
    *         description="No autorizado"
    *     ),
    *     @OA\Response(
    *         response=403,
    *         description="Prohibido - No tiene permisos para ver esta reserva"
    *     ),
    *     @OA\Response(
    *         response=404,
    *         description="Reserva no encontrada"
    *     )
    * )
    */
    public function show(Reserva $reserva): JsonResponse
    {
        if (!auth()->user()->hasRole('admin') && $reserva->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'No tienes permisos para ver esta reserva'
            ], 403);
        }

        return response()->json([
            'reserva' => $reserva->load(['user', 'mesas'])
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/reservas/{id}",
     *     summary="Actualizar una reserva existente",
     *     tags={"Reservas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la reserva",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="hora_inicio", type="string", format="datetime", example="2024-12-17 10:00:00"),
     *             @OA\Property(property="hora_fin", type="string", format="datetime", example="2024-12-17 11:00:00"),
     *             @OA\Property(property="numero_personas", type="integer", example=4),
     *             @OA\Property(property="estado", type="string", enum={"pendiente", "confirmada", "cancelada", "completada", "expirada"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reserva actualizada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Reserva actualizada exitosamente"),
     *             @OA\Property(property="reserva", ref="#/components/schemas/Reserva")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Prohibido - No tiene permisos para actualizar esta reserva"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reserva no encontrada"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function update(ReservationStoreRequest $request, Reserva $reserva): JsonResponse
    {
        // Verificar si la reserva puede ser modificada (1 hora antes)
        if (Carbon::parse($reserva->hora_inicio)->subHour()->isPast()) {
            return response()->json([
                'message' => 'La reserva solo puede modificarse hasta 1 hora antes de su inicio'
            ], 422);
        }

        $validated = $request->validated();
        
        // Verificar disponibilidad de mesas
        $mesas = Mesa::whereIn('id', $validated['mesa_ids'])->get();
        
        // Calcular capacidad total
        $capacidadTotal = $mesas->sum('capacidad');
        if ($validated['numero_personas'] > $capacidadTotal) {
            return response()->json([
                'message' => 'La cantidad de personas excede la capacidad de las mesas'
            ], 422);
        }

        $estadoAnterior = $reserva->estado;

        // Actualizar la reserva
        $reserva->update([
            'hora_inicio' => Carbon::parse($validated['fecha'])->setTimeFromTimeString($validated['hora']),
            'hora_fin' => Carbon::parse($validated['fecha'])
                ->setTimeFromTimeString($validated['hora'])
                ->addMinutes($validated['duracion']),
            'numero_personas' => $validated['numero_personas'],
        ]);

        // Actualizar mesas
        $reserva->mesas()->sync($validated['mesa_ids']);

        // Notificar cambios
        $reserva->user->notify(new ReservationStatusChanged($reserva, $estadoAnterior));

        return response()->json([
            'message' => 'Reserva actualizada exitosamente',
            'reserva' => $reserva->load('mesas')
        ]);
    }
/**
 * @OA\Put(
 *     path="/api/v1/reservas/{id}/cancel",
 *     summary="Cancelar una reserva",
 *     tags={"Reservas"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de la reserva",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Reserva cancelada exitosamente",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Reserva cancelada exitosamente"),
 *             @OA\Property(property="reserva", ref="#/components/schemas/Reserva")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="No autorizado"
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Prohibido - No tiene permisos para cancelar esta reserva"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Reserva no encontrada"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="No se puede cancelar la reserva en su estado actual"
 *     )
 * )
 */
    public function cancel(Reserva $reserva): JsonResponse
    {
        // Verificar si la reserva puede ser cancelada (1 hora antes)
        if (Carbon::parse($reserva->hora_inicio)->subHour()->isPast()) {
            return response()->json([
                'message' => 'La reserva solo puede cancelarse hasta 1 hora antes de su inicio'
            ], 422);
        }

        $estadoAnterior = $reserva->estado;
        $reserva->update(['estado' => 'cancelada']);
        
        // Liberar las mesas
        $reserva->mesas()->update(['estado' => 'disponible']);

        // Notificar cambio
        $reserva->user->notify(new ReservationStatusChanged($reserva, $estadoAnterior));

        return response()->json([
            'message' => 'Reserva cancelada exitosamente'
        ]);
    }
/**
 * @OA\Patch(
 *     path="/api/v1/reservas/{id}/confirm",
 *     summary="Confirmar una reserva (solo admin)",
 *     tags={"Reservas"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de la reserva",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Reserva confirmada exitosamente",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Reserva confirmada exitosamente"),
 *             @OA\Property(property="reserva", ref="#/components/schemas/Reserva")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="No autorizado"
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Prohibido - Solo los administradores pueden confirmar reservas"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Reserva no encontrada"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="No se puede confirmar la reserva en su estado actual"
 *     )
 * )
 */

    public function confirm(Reserva $reserva): JsonResponse
    {
        if (!auth()->user()->hasRole('admin')) {
            return response()->json([
                'message' => 'No tienes permisos para confirmar reservas'
            ], 403);
        }

        $estadoAnterior = $reserva->estado;
        $reserva->update(['estado' => 'confirmada']);

        // Notificar cambio
        $reserva->user->notify(new ReservationStatusChanged($reserva, $estadoAnterior));

        return response()->json([
            'message' => 'Reserva confirmada exitosamente'
        ]);
    }

/**
 * @OA\Patch(
 *     path="/api/v1/reservas/{id}/complete",
 *     summary="Completar una reserva (solo admin)",
 *     tags={"Reservas"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de la reserva",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Reserva completada exitosamente",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Reserva completada exitosamente"),
 *             @OA\Property(property="reserva", ref="#/components/schemas/Reserva")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="No autorizado"
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Prohibido - Solo los administradores pueden completar reservas"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Reserva no encontrada"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="No se puede completar la reserva en su estado actual"
 *     )
 * )
 */
    public function complete(Reserva $reserva): JsonResponse
    {
        if (!auth()->user()->hasRole('admin')) {
            return response()->json([
                'message' => 'No tienes permisos para completar reservas'
            ], 403);
        }

        $estadoAnterior = $reserva->estado;
        $reserva->update(['estado' => 'completada']);
        
        // Liberar las mesas
        $reserva->mesas()->update(['estado' => 'disponible']);

        // Notificar cambio
        $reserva->user->notify(new ReservationStatusChanged($reserva, $estadoAnterior));

        return response()->json([
            'message' => 'Reserva marcada como completada'
        ]);
    }
}
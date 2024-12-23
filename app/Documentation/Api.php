<?php

namespace App\Documentation;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API Restaurante",
 *     description="API para la gestión de restaurantes",
 *     @OA\Contact(
 *         email="soporte@restaurante.com",
 *         name="Soporte API"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 * 
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer"
 * )
 * 
 * @OA\Schema(
 *     schema="Usuario",
 *     required={"nombre", "email"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="nombre", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="telefono", type="string", example="+1234567890")
 * )
 * 
 * @OA\Schema(
 *     schema="Mesa",
 *     required={"numero", "capacidad", "estado"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="numero", type="integer", example=1),
 *     @OA\Property(property="capacidad", type="integer", example=4),
 *     @OA\Property(property="estado", type="string", enum={"disponible", "ocupada", "reservada"}, example="disponible"),
 *     @OA\Property(property="ubicacion", type="string", enum={"A", "B", "C"}, example="A"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="HorarioDisponibilidad",
 *     @OA\Property(
 *         property="dias_semana",
 *         type="object",
 *         @OA\Property(property="lunes", type="string", example="10:00-24:00"),
 *         @OA\Property(property="martes", type="string", example="10:00-24:00"),
 *         @OA\Property(property="miercoles", type="string", example="10:00-24:00"),
 *         @OA\Property(property="jueves", type="string", example="10:00-24:00"),
 *         @OA\Property(property="viernes", type="string", example="10:00-24:00"),
 *         @OA\Property(property="sabado", type="string", example="22:00-02:00"),
 *         @OA\Property(property="domingo", type="string", example="12:00-16:00")
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="Reserva",
 *     required={"usuario_id", "fecha", "hora", "numero_personas", "duracion"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="usuario_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="fecha", type="string", format="date", example="2024-12-25"),
 *     @OA\Property(property="hora", type="string", format="time", example="20:00:00"),
 *     @OA\Property(
 *         property="duracion",
 *         type="integer",
 *         example=105,
 *         description="Duración en minutos (por defecto 105 = 1:45 horas)"
 *     ),
 *     @OA\Property(property="numero_personas", type="integer", example=4),
 *     @OA\Property(
 *         property="mesas",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="mesa_id", type="integer"),
 *             @OA\Property(property="ubicacion", type="string", enum={"A", "B", "C"})
 *         )
 *     ),
 *     @OA\Property(
 *         property="estado",
 *         type="string",
 *         enum={"pendiente", "confirmada", "cancelada", "completada"},
 *         example="pendiente"
 *     ),
 *     @OA\Property(property="notas", type="string", example="Celebración de cumpleaños"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(
 *         property="usuario",
 *         ref="#/components/schemas/Usuario"
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="DisponibilidadMesa",
 *     required={"fecha", "hora", "numero_personas"},
 *     @OA\Property(property="fecha", type="string", format="date", example="2024-12-25"),
 *     @OA\Property(property="hora", type="string", format="time", example="20:00:00"),
 *     @OA\Property(property="numero_personas", type="integer", example=4),
 *     @OA\Property(
 *         property="mesas_disponibles",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="ubicacion", type="string", enum={"A", "B", "C"}, example="A"),
 *             @OA\Property(property="mesas", type="array", @OA\Items(ref="#/components/schemas/Mesa")),
 *             @OA\Property(property="combinaciones_posibles", type="array", 
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="mesas_ids", type="array", @OA\Items(type="integer")),
 *                     @OA\Property(property="capacidad_total", type="integer", example=8)
 *                 )
 *             )
 *         )
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="ValidacionReserva",
 *     required={"fecha", "hora", "numero_personas", "duracion"},
 *     @OA\Property(property="fecha", type="string", format="date", example="2024-12-25"),
 *     @OA\Property(property="hora", type="string", format="time", example="20:00:00"),
 *     @OA\Property(property="numero_personas", type="integer", example=4),
 *     @OA\Property(
 *         property="duracion",
 *         type="integer",
 *         example=105,
 *         description="Duración en minutos (por defecto 105 = 1:45 horas)"
 *     ),
 *     @OA\Property(
 *         property="tiempo_preparacion",
 *         type="integer",
 *         example=15,
 *         description="Tiempo adicional para preparación en minutos"
 *     ),
 *     @OA\Property(
 *         property="validaciones",
 *         type="object",
 *         @OA\Property(property="horario_valido", type="boolean"),
 *         @OA\Property(property="tiempo_anticipacion_valido", type="boolean"),
 *         @OA\Property(property="disponibilidad_mesas", type="boolean"),
 *         @OA\Property(property="mensaje", type="string")
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="Job",
 *     required={"name", "queue", "payload", "attempts"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="queue", type="string", example="default"),
 *     @OA\Property(property="payload", type="string"),
 *     @OA\Property(property="attempts", type="integer", example=0),
 *     @OA\Property(property="reserved_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="available_at", type="string", format="date-time"),
 *     @OA\Property(property="created_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="Notification",
 *     required={"type", "notifiable_type", "notifiable_id", "data"},
 *     @OA\Property(property="id", type="string", format="uuid"),
 *     @OA\Property(property="type", type="string", example="App\\Notifications\\ReservationConfirmed"),
 *     @OA\Property(property="notifiable_type", type="string", example="App\\Models\\User"),
 *     @OA\Property(property="notifiable_id", type="integer"),
 *     @OA\Property(property="data", type="object"),
 *     @OA\Property(property="read_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="Provider",
 *     required={"name", "type", "credentials"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="name", type="string", example="EmailService"),
 *     @OA\Property(property="type", type="string", example="email"),
 *     @OA\Property(property="credentials", type="object"),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="ListadoReservas",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Reserva")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         @OA\Property(property="total", type="integer"),
 *         @OA\Property(property="por_pagina", type="integer"),
 *         @OA\Property(property="pagina_actual", type="integer"),
 *         @OA\Property(property="ultima_pagina", type="integer")
 *     )
 * )
 */
class Api
{
}
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
 *     schema="Mesa",
 *     required={"numero", "capacidad", "estado"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="numero", type="integer", example=1),
 *     @OA\Property(property="capacidad", type="integer", example=4),
 *     @OA\Property(property="estado", type="string", enum={"disponible", "ocupada", "reservada"}, example="disponible"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="MesaResource",
 *     @OA\Property(property="data", ref="#/components/schemas/Mesa")
 * )
 * 
 * @OA\Schema(
 *     schema="StoreMesaRequest",
 *     required={"numero", "capacidad"},
 *     @OA\Property(property="numero", type="integer", example=1),
 *     @OA\Property(property="capacidad", type="integer", example=4),
 *     @OA\Property(property="estado", type="string", enum={"disponible", "ocupada", "reservada"}, example="disponible")
 * )
 * 
 * @OA\Schema(
 *     schema="UpdateMesaRequest",
 *     @OA\Property(property="numero", type="integer", example=1),
 *     @OA\Property(property="capacidad", type="integer", example=4),
 *     @OA\Property(property="estado", type="string", enum={"disponible", "ocupada", "reservada"}, example="disponible")
 * )
 * 
 * @OA\Schema(
 *     schema="MesaCollection",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Mesa")
 *     ),
 *     @OA\Property(
 *         property="links",
 *         type="object",
 *         @OA\Property(property="first", type="string"),
 *         @OA\Property(property="last", type="string"),
 *         @OA\Property(property="prev", type="string", nullable=true),
 *         @OA\Property(property="next", type="string", nullable=true)
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         @OA\Property(property="current_page", type="integer"),
 *         @OA\Property(property="from", type="integer"),
 *         @OA\Property(property="last_page", type="integer"),
 *         @OA\Property(property="path", type="string"),
 *         @OA\Property(property="per_page", type="integer"),
 *         @OA\Property(property="to", type="integer"),
 *         @OA\Property(property="total", type="integer")
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="Reserva",
 *     required={"mesa_id", "fecha", "hora", "cliente_nombre", "cliente_email", "numero_personas"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="mesa_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="fecha", type="string", format="date", example="2024-12-25"),
 *     @OA\Property(property="hora", type="string", format="time", example="20:00:00"),
 *     @OA\Property(property="cliente_nombre", type="string", example="John Doe"),
 *     @OA\Property(property="cliente_email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="cliente_telefono", type="string", example="+1234567890"),
 *     @OA\Property(property="numero_personas", type="integer", example=4),
 *     @OA\Property(property="estado", type="string", enum={"pendiente", "confirmada", "cancelada", "completada"}, example="pendiente"),
 *     @OA\Property(property="notas", type="string", example="Celebración de cumpleaños"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(
 *         property="mesa",
 *         ref="#/components/schemas/Mesa"
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="ReservaResource",
 *     @OA\Property(property="data", ref="#/components/schemas/Reserva")
 * )
 * 
 * @OA\Schema(
 *     schema="StoreReservaRequest",
 *     required={"mesa_id", "fecha", "hora", "cliente_nombre", "cliente_email", "numero_personas"},
 *     @OA\Property(property="mesa_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="fecha", type="string", format="date", example="2024-12-25"),
 *     @OA\Property(property="hora", type="string", format="time", example="20:00:00"),
 *     @OA\Property(property="cliente_nombre", type="string", example="John Doe"),
 *     @OA\Property(property="cliente_email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="cliente_telefono", type="string", example="+1234567890"),
 *     @OA\Property(property="numero_personas", type="integer", example=4),
 *     @OA\Property(property="notas", type="string", example="Celebración de cumpleaños")
 * )
 * 
 * @OA\Schema(
 *     schema="UpdateReservaRequest",
 *     @OA\Property(property="mesa_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="fecha", type="string", format="date", example="2024-12-25"),
 *     @OA\Property(property="hora", type="string", format="time", example="20:00:00"),
 *     @OA\Property(property="cliente_nombre", type="string", example="John Doe"),
 *     @OA\Property(property="cliente_email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="cliente_telefono", type="string", example="+1234567890"),
 *     @OA\Property(property="numero_personas", type="integer", example=4),
 *     @OA\Property(property="estado", type="string", enum={"pendiente", "confirmada", "cancelada", "completada"}, example="confirmada"),
 *     @OA\Property(property="notas", type="string", example="Celebración de cumpleaños")
 * )
 * 
 * @OA\Schema(
 *     schema="ReservaCollection",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Reserva")
 *     ),
 *     @OA\Property(
 *         property="links",
 *         type="object",
 *         @OA\Property(property="first", type="string"),
 *         @OA\Property(property="last", type="string"),
 *         @OA\Property(property="prev", type="string", nullable=true),
 *         @OA\Property(property="next", type="string", nullable=true)
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         @OA\Property(property="current_page", type="integer"),
 *         @OA\Property(property="from", type="integer"),
 *         @OA\Property(property="last_page", type="integer"),
 *         @OA\Property(property="path", type="string"),
 *         @OA\Property(property="per_page", type="integer"),
 *         @OA\Property(property="to", type="integer"),
 *         @OA\Property(property="total", type="integer")
 *     )
 * )
 */
class Api
{
}
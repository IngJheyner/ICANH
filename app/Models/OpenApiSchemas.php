<?php

namespace App\Models;

/**
 * @OA\Schema(
 *     schema="Person",
 *     type="object",
 *     title="Persona",
 *     description="Modelo de persona/propietario",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="identification_number", type="string", example="1234567890"),
 *     @OA\Property(property="name", type="string", example="Juan Pérez"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="Vehicle",
 *     type="object",
 *     title="Vehículo",
 *     description="Modelo de vehículo",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="model", type="string", example="Corolla 2024"),
 *     @OA\Property(property="vehicle_brand_id", type="integer", example=1),
 *     @OA\Property(property="number_of_doors", type="integer", example=4),
 *     @OA\Property(property="color", type="string", example="Blanco"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="vehicle_brand", ref="#/components/schemas/VehicleBrand"),
 *     @OA\Property(
 *         property="owners",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Person")
 *     )
 * )
 */
class OpenApiSchemas
{
    // Esta clase solo contiene anotaciones OpenAPI, no tiene implementación
}


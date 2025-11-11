<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API REST de Gestión de Vehículos",
 *     description="API RESTful para gestión de vehículos, marcas y propietarios desarrollada con Laravel",
 *     @OA\Contact(
 *         email="contacto@vehiculos-api.com",
 *         name="Equipo de Desarrollo"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Servidor de Desarrollo Local"
 * )
 *
 * @OA\Tag(
 *     name="Marcas de Vehículos",
 *     description="Operaciones CRUD para marcas de vehículos"
 * )
 *
 * @OA\Tag(
 *     name="Personas",
 *     description="Operaciones CRUD para personas y sus vehículos"
 * )
 *
 * @OA\Tag(
 *     name="Vehículos",
 *     description="Operaciones CRUD para vehículos y asignación de propietarios"
 * )
 *
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     type="object",
 *     @OA\Property(property="success", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Error en la operación"),
 *     @OA\Property(property="errors", type="object")
 * )
 *
 * @OA\Schema(
 *     schema="ValidationErrorResponse",
 *     type="object",
 *     @OA\Property(property="success", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Error de validación"),
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         @OA\Property(
 *             property="field_name",
 *             type="array",
 *             @OA\Items(type="string", example="El campo es obligatorio.")
 *         )
 *     )
 * )
 */
abstract class Controller
{
    //
}

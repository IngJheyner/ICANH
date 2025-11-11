<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehicleRequest;
use App\Http\Requests\AddOwnerRequest;
use App\Http\Resources\VehicleResource;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    use ApiResponseTrait;

    /**
     * Listar todos los vehículos.
     *
     * @OA\Get(
     *     path="/api/vehiculos",
     *     tags={"Vehículos"},
     *     summary="Obtener lista de todos los vehículos",
     *     description="Retorna una lista completa de todos los vehículos con sus marcas y propietarios",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de vehículos obtenida exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Vehículos obtenidos exitosamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Vehicle")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $vehicles = Vehicle::with(['vehicleBrand', 'people'])->get();
        
        return $this->successResponse(
            VehicleResource::collection($vehicles),
            'Vehículos obtenidos exitosamente'
        );
    }

    /**
     * Crear un nuevo vehículo.
     *
     * @OA\Post(
     *     path="/api/vehiculos",
     *     tags={"Vehículos"},
     *     summary="Crear un nuevo vehículo",
     *     description="Registra un nuevo vehículo en el sistema",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"model", "vehicle_brand_id", "number_of_doors", "color"},
     *             @OA\Property(property="model", type="string", example="Corolla 2024", description="Modelo del vehículo"),
     *             @OA\Property(property="vehicle_brand_id", type="integer", example=1, description="ID de la marca del vehículo"),
     *             @OA\Property(property="number_of_doors", type="integer", example=4, description="Número de puertas (mínimo 2, máximo 6)"),
     *             @OA\Property(property="color", type="string", example="Blanco", description="Color del vehículo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Vehículo creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Vehículo creado exitosamente"),
     *             @OA\Property(property="data", ref="#/components/schemas/Vehicle")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *     )
     * )
     */
    public function store(VehicleRequest $request)
    {
        $vehicle = Vehicle::create($request->validated());
        $vehicle->load(['vehicleBrand', 'people']);

        return $this->createdResponse(
            new VehicleResource($vehicle),
            'Vehículo creado exitosamente'
        );
    }

    /**
     * Mostrar un vehículo específico.
     *
     * @OA\Get(
     *     path="/api/vehiculos/{id}",
     *     tags={"Vehículos"},
     *     summary="Obtener un vehículo específico",
     *     description="Retorna los detalles de un vehículo por su ID, incluyendo marca y propietarios",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del vehículo",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vehículo encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Vehículo obtenido exitosamente"),
     *             @OA\Property(property="data", ref="#/components/schemas/Vehicle")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vehículo no encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function show(string $id)
    {
        $vehicle = Vehicle::with(['vehicleBrand', 'people'])->find($id);

        if (!$vehicle) {
            return $this->notFoundResponse('Vehículo no encontrado');
        }

        return $this->successResponse(
            new VehicleResource($vehicle),
            'Vehículo obtenido exitosamente'
        );
    }

    /**
     * Actualizar un vehículo.
     *
     * @OA\Put(
     *     path="/api/vehiculos/{id}",
     *     tags={"Vehículos"},
     *     summary="Actualizar un vehículo existente",
     *     description="Actualiza los datos de un vehículo",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del vehículo",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"model", "vehicle_brand_id", "number_of_doors", "color"},
     *             @OA\Property(property="model", type="string", example="Camry 2025"),
     *             @OA\Property(property="vehicle_brand_id", type="integer", example=1),
     *             @OA\Property(property="number_of_doors", type="integer", example=4),
     *             @OA\Property(property="color", type="string", example="Negro")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vehículo actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Vehículo actualizado exitosamente"),
     *             @OA\Property(property="data", ref="#/components/schemas/Vehicle")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vehículo no encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *     )
     * )
     */
    public function update(VehicleRequest $request, string $id)
    {
        $vehicle = Vehicle::find($id);

        if (!$vehicle) {
            return $this->notFoundResponse('Vehículo no encontrado');
        }

        $vehicle->update($request->validated());
        $vehicle->load(['vehicleBrand', 'people']);

        return $this->successResponse(
            new VehicleResource($vehicle),
            'Vehículo actualizado exitosamente'
        );
    }

    /**
     * Eliminar un vehículo.
     *
     * @OA\Delete(
     *     path="/api/vehiculos/{id}",
     *     tags={"Vehículos"},
     *     summary="Eliminar un vehículo",
     *     description="Elimina un vehículo del sistema (también elimina sus asociaciones con propietarios)",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del vehículo",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vehículo eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Vehículo eliminado exitosamente"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vehículo no encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $vehicle = Vehicle::find($id);

        if (!$vehicle) {
            return $this->notFoundResponse('Vehículo no encontrado');
        }

        $vehicle->delete();

        return $this->successResponse(
            null,
            'Vehículo eliminado exitosamente'
        );
    }

    /**
     * Asignar un propietario a un vehículo.
     *
     * @OA\Post(
     *     path="/api/vehiculos/{id}/propietarios",
     *     tags={"Vehículos"},
     *     summary="Asignar un propietario a un vehículo",
     *     description="Asocia una persona como propietaria de un vehículo (relación many-to-many)",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del vehículo",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"person_id"},
     *             @OA\Property(property="person_id", type="integer", example=1, description="ID de la persona a asignar como propietaria")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Propietario asignado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Propietario asignado exitosamente al vehículo"),
     *             @OA\Property(property="data", ref="#/components/schemas/Vehicle")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="La persona ya es propietaria del vehículo",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(response=404, description="Vehículo no encontrado"),
     *     @OA\Response(response=422, description="Error de validación")
     * )
     */
    public function addOwner(AddOwnerRequest $request, string $id)
    {
        $vehicle = Vehicle::find($id);

        if (!$vehicle) {
            return $this->notFoundResponse('Vehículo no encontrado');
        }

        $personId = $request->validated()['person_id'];

        // Verificar si la persona ya es propietaria
        if ($vehicle->people()->where('person_id', $personId)->exists()) {
            return $this->errorResponse(
                'Esta persona ya es propietaria del vehículo',
                400
            );
        }

        // Asignar propietario
        $vehicle->people()->attach($personId);
        $vehicle->load(['vehicleBrand', 'people']);

        return $this->successResponse(
            new VehicleResource($vehicle),
            'Propietario asignado exitosamente al vehículo'
        );
    }
}

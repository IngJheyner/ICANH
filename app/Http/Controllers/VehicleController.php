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

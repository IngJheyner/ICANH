<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehicleBrandRequest;
use App\Http\Resources\VehicleBrandResource;
use App\Http\Traits\ApiResponseTrait;
use App\Models\VehicleBrand;

class VehicleBrandController extends Controller
{
    use ApiResponseTrait;

    /**
     * Listar todas las marcas de vehículos.
     */
    public function index()
    {
        $brands = VehicleBrand::all();
        
        return $this->successResponse(
            VehicleBrandResource::collection($brands),
            'Marcas obtenidas exitosamente'
        );
    }

    /**
     * Crear una nueva marca de vehículo.
     */
    public function store(VehicleBrandRequest $request)
    {
       
        $brand = VehicleBrand::create($request->validated());

        return $this->createdResponse(
            new VehicleBrandResource($brand),
            'Marca de vehículo creada exitosamente'
        );
        
    }

    /**
     * Mostrar una marca de vehículo específica.
     */
    public function show(string $id)
    {
        $brand = VehicleBrand::find($id);

        if (!$brand) {
            return $this->notFoundResponse('Marca de vehículo no encontrada');
        }

        return $this->successResponse(
            new VehicleBrandResource($brand),
            'Marca obtenida exitosamente'
        );
    }

    /**
     * Actualizar una marca de vehículo.
     */
    public function update(VehicleBrandRequest $request, string $id)
    {
        $brand = VehicleBrand::find($id);

        if (!$brand) {
            return $this->notFoundResponse('Marca de vehículo no encontrada');
        }

        $brand->update($request->validated());

        return $this->successResponse(
            new VehicleBrandResource($brand),
            'Marca de vehículo actualizada exitosamente'
        );
    }

    /**
     * Eliminar una marca de vehículo.
     */
    public function destroy(string $id)
    {
        $brand = VehicleBrand::find($id);

        if (!$brand) {
            return $this->notFoundResponse('Marca de vehículo no encontrada');
        }

        $brand->delete();

        return $this->successResponse(
            null,
            'Marca de vehículo eliminada exitosamente'
        );
    }
}

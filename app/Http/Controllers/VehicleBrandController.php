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
     *
     * @OA\Get(
     *     path="/api/marcas-vehiculo",
     *     tags={"Marcas de Vehículos"},
     *     summary="Obtener lista de todas las marcas de vehículos",
     *     description="Retorna una lista completa de todas las marcas de vehículos registradas",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de marcas obtenida exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Marcas obtenidas exitosamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/VehicleBrand")
     *             )
     *         )
     *     )
     * )
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
     *
     * @OA\Post(
     *     path="/api/marcas-vehiculo",
     *     tags={"Marcas de Vehículos"},
     *     summary="Crear una nueva marca de vehículo",
     *     description="Registra una nueva marca de vehículo en el sistema",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"brand_name", "country"},
     *             @OA\Property(property="brand_name", type="string", example="Tesla", description="Nombre de la marca"),
     *             @OA\Property(property="country", type="string", example="Estados Unidos", description="País de origen")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Marca creada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Marca de vehículo creada exitosamente"),
     *             @OA\Property(property="data", ref="#/components/schemas/VehicleBrand")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *     )
     * )
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
     *
     * @OA\Get(
     *     path="/api/marcas-vehiculo/{id}",
     *     tags={"Marcas de Vehículos"},
     *     summary="Obtener una marca específica",
     *     description="Retorna los detalles de una marca de vehículo por su ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la marca",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Marca encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Marca obtenida exitosamente"),
     *             @OA\Property(property="data", ref="#/components/schemas/VehicleBrand")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Marca no encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
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
     *
     * @OA\Put(
     *     path="/api/marcas-vehiculo/{id}",
     *     tags={"Marcas de Vehículos"},
     *     summary="Actualizar una marca existente",
     *     description="Actualiza los datos de una marca de vehículo",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la marca",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"brand_name", "country"},
     *             @OA\Property(property="brand_name", type="string", example="Tesla Motors"),
     *             @OA\Property(property="country", type="string", example="Estados Unidos")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Marca actualizada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Marca de vehículo actualizada exitosamente"),
     *             @OA\Property(property="data", ref="#/components/schemas/VehicleBrand")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Marca no encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *     )
     * )
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
     *
     * @OA\Delete(
     *     path="/api/marcas-vehiculo/{id}",
     *     tags={"Marcas de Vehículos"},
     *     summary="Eliminar una marca",
     *     description="Elimina una marca de vehículo del sistema (también elimina vehículos asociados en cascada)",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la marca",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Marca eliminada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Marca de vehículo eliminada exitosamente"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Marca no encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
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

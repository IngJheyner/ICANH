<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonRequest;
use App\Http\Resources\PersonResource;
use App\Http\Resources\VehicleResource;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Person;

class PersonController extends Controller
{
    use ApiResponseTrait;

    /**
     * Listar todas las personas.
     *
     * @OA\Get(
     *     path="/api/personas",
     *     tags={"Personas"},
     *     summary="Obtener lista de todas las personas",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de personas obtenida exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Person"))
     *         )
     *     )
     * )
     */
    public function index()
    {
        $people = Person::all();
        
        return $this->successResponse(
            PersonResource::collection($people),
            'Personas obtenidas exitosamente'
        );
    }

    /**
     * Crear una nueva persona.
     *
     * @OA\Post(
     *     path="/api/personas",
     *     tags={"Personas"},
     *     summary="Crear una nueva persona",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "identification_number"},
     *             @OA\Property(property="name", type="string", example="María García"),
     *             @OA\Property(property="identification_number", type="string", example="987654321")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Persona creada exitosamente"),
     *     @OA\Response(response=422, description="Error de validación")
     * )
     */
    public function store(PersonRequest $request)
    {
        $person = Person::create($request->validated());

        return $this->createdResponse(
            new PersonResource($person),
            'Persona creada exitosamente'
        );
    }

    /**
     * Mostrar una persona específica.
     *
     * @OA\Get(
     *     path="/api/personas/{id}",
     *     tags={"Personas"},
     *     summary="Obtener una persona específica",
     *     description="Retorna los detalles de una persona por su ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la persona",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Persona encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Persona obtenida exitosamente"),
     *             @OA\Property(property="data", ref="#/components/schemas/Person")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Persona no encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function show(string $id)
    {
        $person = Person::find($id);

        if (!$person) {
            return $this->notFoundResponse('Persona no encontrada');
        }

        return $this->successResponse(
            new PersonResource($person),
            'Persona obtenida exitosamente'
        );
    }

    /**
     * Actualizar una persona.
     *
     * @OA\Put(
     *     path="/api/personas/{id}",
     *     tags={"Personas"},
     *     summary="Actualizar una persona existente",
     *     description="Actualiza los datos de una persona",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la persona",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "identification_number"},
     *             @OA\Property(property="name", type="string", example="Carlos Rodríguez"),
     *             @OA\Property(property="identification_number", type="string", example="111222333")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Persona actualizada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Persona actualizada exitosamente"),
     *             @OA\Property(property="data", ref="#/components/schemas/Person")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Persona no encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *     )
     * )
     */
    public function update(PersonRequest $request, string $id)
    {
        $person = Person::find($id);

        if (!$person) {
            return $this->notFoundResponse('Persona no encontrada');
        }

        $person->update($request->validated());

        return $this->successResponse(
            new PersonResource($person),
            'Persona actualizada exitosamente'
        );
    }

    /**
     * Eliminar una persona.
     *
     * @OA\Delete(
     *     path="/api/personas/{id}",
     *     tags={"Personas"},
     *     summary="Eliminar una persona",
     *     description="Elimina una persona del sistema (también elimina sus asociaciones con vehículos)",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la persona",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Persona eliminada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Persona eliminada exitosamente"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Persona no encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $person = Person::find($id);

        if (!$person) {
            return $this->notFoundResponse('Persona no encontrada');
        }

        $person->delete();

        return $this->successResponse(
            null,
            'Persona eliminada exitosamente'
        );
    }

    /**
     * Obtener los vehículos de una persona.
     *
     * @OA\Get(
     *     path="/api/personas/{id}/vehiculos",
     *     tags={"Personas"},
     *     summary="Obtener vehículos de una persona",
     *     description="Retorna la lista de vehículos que pertenecen a una persona específica",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la persona",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de vehículos de la persona",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Vehicle"))
     *         )
     *     ),
     *     @OA\Response(response=404, description="Persona no encontrada")
     * )
     */
    public function vehicles(string $id)
    {
        $person = Person::with('vehicles')->find($id);

        if (!$person) {
            return $this->notFoundResponse('Persona no encontrada');
        }

        return $this->successResponse(
            VehicleResource::collection($person->vehicles),
            'Vehículos de la persona obtenidos exitosamente'
        );
    }
}

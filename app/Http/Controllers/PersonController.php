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

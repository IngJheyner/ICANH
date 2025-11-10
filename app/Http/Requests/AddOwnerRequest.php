<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddOwnerRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta petición.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Obtiene las reglas de validación que aplican a la petición.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'person_id' => [
                'required',
                'integer',
                'exists:people,id',
            ],
        ];
    }

    /**
     * Mensajes personalizados de validación.
     */
    public function messages(): array
    {
        return [
            'person_id.required' => 'El ID de la persona es obligatorio.',
            'person_id.integer' => 'El ID de la persona debe ser un número entero.',
            'person_id.exists' => 'La persona seleccionada no existe.',
        ];
    }

    /**
     * Maneja una validación fallida.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}

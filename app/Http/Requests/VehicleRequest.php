<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VehicleRequest extends FormRequest
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
            'model' => [
                'required',
                'string',
                'max:255',
            ],
            'vehicle_brand_id' => [
                'required',
                'integer',
                'exists:vehicle_brands,id',
            ],
            'number_of_doors' => [
                'required',
                'integer',
                'min:2',
                'max:6',
            ],
            'color' => [
                'required',
                'string',
                'max:50',
            ],
        ];
    }

    /**
     * Mensajes personalizados de validación.
     */
    public function messages(): array
    {
        return [
            'model.required' => 'El modelo es obligatorio.',
            'model.max' => 'El modelo no puede exceder 255 caracteres.',
            'vehicle_brand_id.required' => 'La marca del vehículo es obligatoria.',
            'vehicle_brand_id.exists' => 'La marca seleccionada no existe.',
            'number_of_doors.required' => 'El número de puertas es obligatorio.',
            'number_of_doors.min' => 'El número de puertas debe ser al menos 2.',
            'number_of_doors.max' => 'El número de puertas no puede ser mayor a 6.',
            'color.required' => 'El color es obligatorio.',
            'color.max' => 'El color no puede exceder 50 caracteres.',
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

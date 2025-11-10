<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VehicleBrandRequest extends FormRequest
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
        // Obtener el ID del registro si estamos actualizando
        $brandId = $this->route('id');

        return [
            'brand_name' => [
                'required',
                'string',
                'max:255',
                // Ignorar el registro actual al validar unicidad en actualizaciones
                Rule::unique('vehicle_brands', 'brand_name')->ignore($brandId),
            ],
            'country' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    /**
     * Mensajes personalizados de validación.
     */
    public function messages(): array
    {
        return [
            'brand_name.required' => 'El nombre de la marca es obligatorio.',
            'brand_name.unique' => 'Esta marca ya existe.',
            'brand_name.max' => 'El nombre de la marca no puede exceder 255 caracteres.',
            'country.required' => 'El país es obligatorio.',
            'country.max' => 'El país no puede exceder 255 caracteres.',
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

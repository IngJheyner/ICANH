<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VehicleBrand>
 */
class VehicleBrandFactory extends Factory
{
    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $brands = [
            ['brand_name' => 'Toyota', 'country' => 'Japón'],
            ['brand_name' => 'Honda', 'country' => 'Japón'],
            ['brand_name' => 'Ford', 'country' => 'Estados Unidos'],
            ['brand_name' => 'Chevrolet', 'country' => 'Estados Unidos'],
            ['brand_name' => 'Volkswagen', 'country' => 'Alemania'],
            ['brand_name' => 'BMW', 'country' => 'Alemania'],
            ['brand_name' => 'Mercedes-Benz', 'country' => 'Alemania'],
            ['brand_name' => 'Nissan', 'country' => 'Japón'],
            ['brand_name' => 'Hyundai', 'country' => 'Corea del Sur'],
            ['brand_name' => 'Kia', 'country' => 'Corea del Sur'],
            ['brand_name' => 'Mazda', 'country' => 'Japón'],
            ['brand_name' => 'Renault', 'country' => 'Francia'],
        ];

        // Seleccionar una marca aleatoria y añadir un sufijo único
        $brand = $this->faker->randomElement($brands);
        $uniqueSuffix = $this->faker->unique()->numberBetween(1, 100000);

        return [
            'brand_name' => $brand['brand_name'] . ' ' . $uniqueSuffix,
            'country' => $brand['country'],
        ];
    }
}

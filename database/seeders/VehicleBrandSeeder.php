<?php

namespace Database\Seeders;

use App\Models\VehicleBrand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleBrandSeeder extends Seeder
{
    /**
     * Ejecuta el seeder de marcas de vehículos.
     */
    public function run(): void
    {
        // Marcas específicas de vehículos
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
        ];

        foreach ($brands as $brand) {
            VehicleBrand::create($brand);
        }
    }
}

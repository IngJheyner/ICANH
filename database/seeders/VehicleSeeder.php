<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use App\Models\Person;
use App\Models\VehicleBrand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Ejecuta el seeder de vehículos.
     */
    public function run(): void
    {
        // Obtener todas las marcas y personas
        $brands = VehicleBrand::all();
        $people = Person::all();

        if ($brands->isEmpty() || $people->isEmpty()) {
            $this->command->warn('No hay marcas o personas en la base de datos. Ejecuta los seeders correspondientes primero.');
            return;
        }

        // Crear 25 vehículos
        Vehicle::factory()->count(25)->create()->each(function ($vehicle) use ($people) {
            // Asignar entre 1 y 3 propietarios aleatorios a cada vehículo
            $owners = $people->random(rand(1, 3));
            $vehicle->people()->attach($owners);
        });
    }
}

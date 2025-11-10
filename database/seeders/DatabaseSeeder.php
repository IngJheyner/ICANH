<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Ejecuta los seeders de la base de datos.
     */
    public function run(): void
    {
        $this->command->info('🌱 Iniciando seeding de la base de datos...');

        // Ejecutar seeders en orden (importante para las relaciones)
        $this->call([
            VehicleBrandSeeder::class,
            PersonSeeder::class,
            VehicleSeeder::class,
        ]);

        $this->command->info('✅ Seeding completado exitosamente!');
        $this->command->newLine();
        $this->command->info('📊 Datos creados:');
        $this->command->info('   - 10 Marcas de Vehículos');
        $this->command->info('   - 15 Personas');
        $this->command->info('   - 25 Vehículos (con 1-3 propietarios cada uno)');
    }
}

<?php

namespace Database\Seeders;

use App\Models\Person;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PersonSeeder extends Seeder
{
    /**
     * Ejecuta el seeder de personas.
     */
    public function run(): void
    {
        // Crear 15 personas usando el factory
        Person::factory()->count(15)->create();
    }
}

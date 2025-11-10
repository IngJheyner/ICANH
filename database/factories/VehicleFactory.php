<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $models = [
            'Corolla', 'Camry', 'RAV4', 'Civic', 'Accord', 'CR-V',
            'F-150', 'Mustang', 'Explorer', 'Silverado', 'Malibu', 'Equinox',
            'Golf', 'Jetta', 'Tiguan', 'Serie 3', 'Serie 5', 'X5',
            'Clase C', 'Clase E', 'GLA', 'Sentra', 'Altima', 'Rogue',
            'Elantra', 'Sonata', 'Tucson', 'Rio', 'Sportage', 'Sorento',
            'CX-5', 'Mazda3', 'Mazda6', 'Clio', 'Megane', 'Captur'
        ];

        $colors = [
            'Blanco', 'Negro', 'Gris', 'Plata', 'Rojo', 'Azul',
            'Verde', 'Amarillo', 'Naranja', 'Café', 'Dorado', 'Bronce'
        ];

        // Usar una marca aleatoria de las existentes, o crear una si no hay
        $brandId = \App\Models\VehicleBrand::inRandomOrder()->first()?->id 
                   ?? \App\Models\VehicleBrand::factory()->create()->id;

        return [
            'model' => $this->faker->randomElement($models) . ' ' . $this->faker->year(),
            'vehicle_brand_id' => $brandId,
            'number_of_doors' => $this->faker->randomElement([2, 4, 5]),
            'color' => $this->faker->randomElement($colors),
        ];
    }
}

<?php

namespace Tests\Feature;

use App\Models\Person;
use App\Models\Vehicle;
use App\Models\VehicleBrand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Listar todas las personas.
     */
    public function test_puede_listar_todas_las_personas(): void
    {
        Person::factory()->count(3)->create();

        $response = $this->getJson('/api/personas');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'identification_number',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ])
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonCount(3, 'data');
    }

    /**
     * Test: Obtener una persona específica.
     */
    public function test_puede_obtener_persona_especifica(): void
    {
        $person = Person::factory()->create([
            'name' => 'Juan Pérez',
            'identification_number' => '123456789',
        ]);

        $response = $this->getJson("/api/personas/{$person->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $person->id,
                    'name' => 'Juan Pérez',
                    'identification_number' => '123456789',
                ]
            ]);
    }

    /**
     * Test: Crear una nueva persona.
     */
    public function test_puede_crear_nueva_persona(): void
    {
        $data = [
            'name' => 'María García',
            'identification_number' => '987654321',
        ];

        $response = $this->postJson('/api/personas', $data);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Persona creada exitosamente',
                'data' => [
                    'name' => 'María García',
                    'identification_number' => '987654321',
                ]
            ]);

        $this->assertDatabaseHas('people', $data);
    }

    /**
     * Test: Actualizar una persona existente.
     */
    public function test_puede_actualizar_persona_existente(): void
    {
        $person = Person::factory()->create();

        $updateData = [
            'name' => 'Carlos Rodríguez',
            'identification_number' => '111222333',
        ];

        $response = $this->putJson("/api/personas/{$person->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Persona actualizada exitosamente',
                'data' => [
                    'id' => $person->id,
                    'name' => 'Carlos Rodríguez',
                    'identification_number' => '111222333',
                ]
            ]);

        $this->assertDatabaseHas('people', $updateData);
    }

    /**
     * Test: Eliminar una persona.
     */
    public function test_puede_eliminar_persona(): void
    {
        $person = Person::factory()->create();

        $response = $this->deleteJson("/api/personas/{$person->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Persona eliminada exitosamente',
            ]);

        $this->assertDatabaseMissing('people', ['id' => $person->id]);
    }

    /**
     * Test: Obtener vehículos de una persona.
     */
    public function test_puede_obtener_vehiculos_de_persona(): void
    {
        $person = Person::factory()->create();
        $brand = VehicleBrand::factory()->create();
        $vehicles = Vehicle::factory()->count(2)->create([
            'vehicle_brand_id' => $brand->id,
        ]);

        // Asociar vehículos a la persona
        $person->vehicles()->attach($vehicles->pluck('id'));

        $response = $this->getJson("/api/personas/{$person->id}/vehiculos");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'model',
                        'vehicle_brand_id',
                        'number_of_doors',
                        'color',
                    ]
                ]
            ]);
    }

    /**
     * Test: Obtener vehículos de persona sin vehículos (lista vacía).
     */
    public function test_puede_obtener_lista_vacia_de_vehiculos_de_persona(): void
    {
        $person = Person::factory()->create();

        $response = $this->getJson("/api/personas/{$person->id}/vehiculos");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [],
            ]);
    }

    /**
     * Test: Error de validación al crear persona sin nombre.
     */
    public function test_error_validacion_al_crear_sin_nombre(): void
    {
        $data = [
            'identification_number' => '444555666',
        ];

        $response = $this->postJson('/api/personas', $data);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Error de validación',
            ])
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Test: Error de validación al crear persona sin número de identificación.
     */
    public function test_error_validacion_al_crear_sin_identificacion(): void
    {
        $data = [
            'name' => 'Pedro López',
        ];

        $response = $this->postJson('/api/personas', $data);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Error de validación',
            ])
            ->assertJsonValidationErrors(['identification_number']);
    }

    /**
     * Test: Error de validación al crear persona con identificación duplicada.
     */
    public function test_error_validacion_al_crear_identificacion_duplicada(): void
    {
        Person::factory()->create(['identification_number' => '777888999']);

        $data = [
            'name' => 'Ana Martínez',
            'identification_number' => '777888999',
        ];

        $response = $this->postJson('/api/personas', $data);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Error de validación',
            ])
            ->assertJsonValidationErrors(['identification_number']);
    }

    /**
     * Test: Error 404 al obtener persona inexistente.
     */
    public function test_error_404_al_obtener_persona_inexistente(): void
    {
        $response = $this->getJson('/api/personas/999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Persona no encontrada',
            ]);
    }

    /**
     * Test: Error 404 al obtener vehículos de persona inexistente.
     */
    public function test_error_404_al_obtener_vehiculos_de_persona_inexistente(): void
    {
        $response = $this->getJson('/api/personas/999/vehiculos');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
            ]);
    }

    /**
     * Test: Puede actualizar persona manteniendo mismo número de identificación.
     */
    public function test_puede_actualizar_persona_manteniendo_mismo_numero(): void
    {
        $person = Person::factory()->create([
            'name' => 'Luis Hernández',
            'identification_number' => '101010101',
        ]);

        $updateData = [
            'name' => 'Luis Hernández Actualizado',
            'identification_number' => '101010101', // Mismo número
        ];

        $response = $this->putJson("/api/personas/{$person->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);
    }
}

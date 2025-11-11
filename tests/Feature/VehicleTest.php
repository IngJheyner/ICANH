<?php

namespace Tests\Feature;

use App\Models\Person;
use App\Models\Vehicle;
use App\Models\VehicleBrand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Listar todos los vehículos.
     */
    public function test_puede_listar_todos_los_vehiculos(): void
    {
        $brand = VehicleBrand::factory()->create();
        Vehicle::factory()->count(4)->create([
            'vehicle_brand_id' => $brand->id,
        ]);

        $response = $this->getJson('/api/vehiculos');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'model',
                        'vehicle_brand_id',
                        'number_of_doors',
                        'color',
                        'created_at',
                        'updated_at',
                        'vehicle_brand',
                        'owners',
                    ]
                ]
            ])
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonCount(4, 'data');
    }

    /**
     * Test: Obtener un vehículo específico con sus relaciones.
     */
    public function test_puede_obtener_vehiculo_especifico(): void
    {
        $brand = VehicleBrand::factory()->create(['brand_name' => 'Honda']);
        $vehicle = Vehicle::factory()->create([
            'model' => 'Civic 2023',
            'vehicle_brand_id' => $brand->id,
            'number_of_doors' => 4,
            'color' => 'Azul',
        ]);

        $response = $this->getJson("/api/vehiculos/{$vehicle->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $vehicle->id,
                    'model' => 'Civic 2023',
                    'number_of_doors' => 4,
                    'color' => 'Azul',
                    'vehicle_brand' => [
                        'brand_name' => 'Honda',
                    ]
                ]
            ]);
    }

    /**
     * Test: Crear un nuevo vehículo.
     */
    public function test_puede_crear_nuevo_vehiculo(): void
    {
        $brand = VehicleBrand::factory()->create();

        $data = [
            'model' => 'Corolla 2024',
            'vehicle_brand_id' => $brand->id,
            'number_of_doors' => 4,
            'color' => 'Blanco',
        ];

        $response = $this->postJson('/api/vehiculos', $data);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Vehículo creado exitosamente',
                'data' => [
                    'model' => 'Corolla 2024',
                    'number_of_doors' => 4,
                    'color' => 'Blanco',
                ]
            ]);

        $this->assertDatabaseHas('vehicles', $data);
    }

    /**
     * Test: Actualizar un vehículo existente.
     */
    public function test_puede_actualizar_vehiculo_existente(): void
    {
        $brand = VehicleBrand::factory()->create();
        $vehicle = Vehicle::factory()->create([
            'vehicle_brand_id' => $brand->id,
        ]);

        $updateData = [
            'model' => 'Camry 2025',
            'vehicle_brand_id' => $brand->id,
            'number_of_doors' => 4,
            'color' => 'Negro',
        ];

        $response = $this->putJson("/api/vehiculos/{$vehicle->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Vehículo actualizado exitosamente',
                'data' => [
                    'id' => $vehicle->id,
                    'model' => 'Camry 2025',
                    'color' => 'Negro',
                ]
            ]);

        $this->assertDatabaseHas('vehicles', [
            'id' => $vehicle->id,
            'model' => 'Camry 2025',
        ]);
    }

    /**
     * Test: Eliminar un vehículo.
     */
    public function test_puede_eliminar_vehiculo(): void
    {
        $brand = VehicleBrand::factory()->create();
        $vehicle = Vehicle::factory()->create([
            'vehicle_brand_id' => $brand->id,
        ]);

        $response = $this->deleteJson("/api/vehiculos/{$vehicle->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Vehículo eliminado exitosamente',
            ]);

        $this->assertDatabaseMissing('vehicles', ['id' => $vehicle->id]);
    }

    /**
     * Test: Asignar propietario a un vehículo.
     */
    public function test_puede_asignar_propietario_a_vehiculo(): void
    {
        $brand = VehicleBrand::factory()->create();
        $vehicle = Vehicle::factory()->create([
            'vehicle_brand_id' => $brand->id,
        ]);
        $person = Person::factory()->create();

        $response = $this->postJson("/api/vehiculos/{$vehicle->id}/propietarios", [
            'person_id' => $person->id,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Propietario asignado exitosamente al vehículo',
            ]);

        // Verificar que la relación se creó en la tabla pivote
        $this->assertDatabaseHas('person_vehicle', [
            'person_id' => $person->id,
            'vehicle_id' => $vehicle->id,
        ]);
    }

    /**
     * Test: Asignar el mismo propietario dos veces (no duplicar).
     */
    public function test_no_puede_asignar_mismo_propietario_dos_veces(): void
    {
        $brand = VehicleBrand::factory()->create();
        $vehicle = Vehicle::factory()->create([
            'vehicle_brand_id' => $brand->id,
        ]);
        $person = Person::factory()->create();

        // Primera asignación
        $vehicle->people()->attach($person->id);

        // Intentar segunda asignación
        $response = $this->postJson("/api/vehiculos/{$vehicle->id}/propietarios", [
            'person_id' => $person->id,
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Esta persona ya es propietaria del vehículo',
            ]);
    }

    /**
     * Test: Error de validación al crear vehículo sin modelo.
     */
    public function test_error_validacion_al_crear_sin_modelo(): void
    {
        $brand = VehicleBrand::factory()->create();

        $data = [
            'vehicle_brand_id' => $brand->id,
            'number_of_doors' => 4,
            'color' => 'Rojo',
        ];

        $response = $this->postJson('/api/vehiculos', $data);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Error de validación',
            ])
            ->assertJsonValidationErrors(['model']);
    }

    /**
     * Test: Error de validación al crear vehículo con marca inexistente.
     */
    public function test_error_validacion_al_crear_con_marca_inexistente(): void
    {
        $data = [
            'model' => 'Test Model',
            'vehicle_brand_id' => 999,
            'number_of_doors' => 4,
            'color' => 'Verde',
        ];

        $response = $this->postJson('/api/vehiculos', $data);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Error de validación',
            ])
            ->assertJsonValidationErrors(['vehicle_brand_id']);
    }

    /**
     * Test: Error de validación al asignar propietario inexistente.
     */
    public function test_error_validacion_al_asignar_propietario_inexistente(): void
    {
        $brand = VehicleBrand::factory()->create();
        $vehicle = Vehicle::factory()->create([
            'vehicle_brand_id' => $brand->id,
        ]);

        $response = $this->postJson("/api/vehiculos/{$vehicle->id}/propietarios", [
            'person_id' => 999,
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Error de validación',
            ])
            ->assertJsonValidationErrors(['person_id']);
    }

    /**
     * Test: Error 404 al obtener vehículo inexistente.
     */
    public function test_error_404_al_obtener_vehiculo_inexistente(): void
    {
        $response = $this->getJson('/api/vehiculos/999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Vehículo no encontrado',
            ]);
    }

    /**
     * Test: Error 404 al asignar propietario a vehículo inexistente.
     */
    public function test_error_404_al_asignar_propietario_a_vehiculo_inexistente(): void
    {
        $person = Person::factory()->create();

        $response = $this->postJson('/api/vehiculos/999/propietarios', [
            'person_id' => $person->id,
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
            ]);
    }

    /**
     * Test: Error de validación con número de puertas inválido (menor a 2).
     */
    public function test_error_validacion_con_numero_puertas_invalido(): void
    {
        $brand = VehicleBrand::factory()->create();

        $data = [
            'model' => 'Test Model',
            'vehicle_brand_id' => $brand->id,
            'number_of_doors' => 1, // Menor al mínimo permitido (2)
            'color' => 'Amarillo',
        ];

        $response = $this->postJson('/api/vehiculos', $data);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Error de validación',
            ])
            ->assertJsonValidationErrors(['number_of_doors']);
    }

    /**
     * Test: Vehículo cargado con propietarios cuando los tiene.
     */
    public function test_vehiculo_cargado_con_propietarios(): void
    {
        $brand = VehicleBrand::factory()->create();
        $vehicle = Vehicle::factory()->create([
            'vehicle_brand_id' => $brand->id,
        ]);
        $owners = Person::factory()->count(2)->create();
        $vehicle->people()->attach($owners->pluck('id'));

        $response = $this->getJson("/api/vehiculos/{$vehicle->id}");

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data.owners')
            ->assertJsonStructure([
                'data' => [
                    'owners' => [
                        '*' => [
                            'id',
                            'name',
                            'identification_number',
                        ]
                    ]
                ]
            ]);
    }
}

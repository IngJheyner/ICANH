<?php

namespace Tests\Feature;

use App\Models\VehicleBrand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleBrandTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Listar todas las marcas de vehículos.
     */
    public function test_puede_listar_todas_las_marcas(): void
    {
        // Crear datos de prueba
        VehicleBrand::factory()->count(5)->create();

        // Hacer petición
        $response = $this->getJson('/api/marcas-vehiculo');

        // Verificar respuesta
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'brand_name',
                        'country',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ])
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonCount(5, 'data');
    }

    /**
     * Test: Obtener una marca específica.
     */
    public function test_puede_obtener_marca_especifica(): void
    {
        $brand = VehicleBrand::factory()->create([
            'brand_name' => 'Toyota',
            'country' => 'Japón',
        ]);

        $response = $this->getJson("/api/marcas-vehiculo/{$brand->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $brand->id,
                    'brand_name' => 'Toyota',
                    'country' => 'Japón',
                ]
            ]);
    }

    /**
     * Test: Crear una nueva marca de vehículo.
     */
    public function test_puede_crear_nueva_marca(): void
    {
        $data = [
            'brand_name' => 'Tesla',
            'country' => 'Estados Unidos',
        ];

        $response = $this->postJson('/api/marcas-vehiculo', $data);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Marca de vehículo creada exitosamente',
                'data' => [
                    'brand_name' => 'Tesla',
                    'country' => 'Estados Unidos',
                ]
            ]);

        // Verificar que se guardó en la base de datos
        $this->assertDatabaseHas('vehicle_brands', $data);
    }

    /**
     * Test: Actualizar una marca existente.
     */
    public function test_puede_actualizar_marca_existente(): void
    {
        $brand = VehicleBrand::factory()->create();

        $updateData = [
            'brand_name' => 'Mazda Updated',
            'country' => 'Japón',
        ];

        $response = $this->putJson("/api/marcas-vehiculo/{$brand->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Marca de vehículo actualizada exitosamente',
                'data' => [
                    'id' => $brand->id,
                    'brand_name' => 'Mazda Updated',
                    'country' => 'Japón',
                ]
            ]);

        $this->assertDatabaseHas('vehicle_brands', $updateData);
    }

    /**
     * Test: Eliminar una marca.
     */
    public function test_puede_eliminar_marca(): void
    {
        $brand = VehicleBrand::factory()->create();

        $response = $this->deleteJson("/api/marcas-vehiculo/{$brand->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Marca de vehículo eliminada exitosamente',
            ]);

        $this->assertDatabaseMissing('vehicle_brands', ['id' => $brand->id]);
    }

    /**
     * Test: Error de validación al crear marca sin nombre.
     */
    public function test_error_validacion_al_crear_sin_nombre(): void
    {
        $data = [
            'country' => 'México',
        ];

        $response = $this->postJson('/api/marcas-vehiculo', $data);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Error de validación',
            ])
            ->assertJsonValidationErrors(['brand_name']);
    }

    /**
     * Test: Error de validación al crear marca sin país.
     */
    public function test_error_validacion_al_crear_sin_pais(): void
    {
        $data = [
            'brand_name' => 'Ford',
        ];

        $response = $this->postJson('/api/marcas-vehiculo', $data);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Error de validación',
            ])
            ->assertJsonValidationErrors(['country']);
    }

    /**
     * Test: Error de validación al crear marca duplicada.
     */
    public function test_error_validacion_al_crear_marca_duplicada(): void
    {
        VehicleBrand::factory()->create(['brand_name' => 'Honda']);

        $data = [
            'brand_name' => 'Honda',
            'country' => 'Japón',
        ];

        $response = $this->postJson('/api/marcas-vehiculo', $data);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Error de validación',
            ])
            ->assertJsonValidationErrors(['brand_name']);
    }

    /**
     * Test: Error 404 al obtener marca inexistente.
     */
    public function test_error_404_al_obtener_marca_inexistente(): void
    {
        $response = $this->getJson('/api/marcas-vehiculo/999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Marca de vehículo no encontrada',
            ]);
    }

    /**
     * Test: Error 404 al actualizar marca inexistente.
     */
    public function test_error_404_al_actualizar_marca_inexistente(): void
    {
        $data = [
            'brand_name' => 'Test',
            'country' => 'Test',
        ];

        $response = $this->putJson('/api/marcas-vehiculo/999', $data);

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
            ]);
    }

    /**
     * Test: Error 404 al eliminar marca inexistente.
     */
    public function test_error_404_al_eliminar_marca_inexistente(): void
    {
        $response = $this->deleteJson('/api/marcas-vehiculo/999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
            ]);
    }

    /**
     * Test: Puede actualizar marca sin cambiar el nombre (mismo nombre, sin error de unique).
     */
    public function test_puede_actualizar_marca_manteniendo_mismo_nombre(): void
    {
        $brand = VehicleBrand::factory()->create([
            'brand_name' => 'Nissan',
            'country' => 'Japón',
        ]);

        $updateData = [
            'brand_name' => 'Nissan', // Mismo nombre
            'country' => 'Japón Actualizado',
        ];

        $response = $this->putJson("/api/marcas-vehiculo/{$brand->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);
    }
}

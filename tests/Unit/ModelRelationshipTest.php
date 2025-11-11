<?php

namespace Tests\Unit;

use App\Models\Person;
use App\Models\Vehicle;
use App\Models\VehicleBrand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelRelationshipTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: VehicleBrand tiene relación hasMany con Vehicle.
     */
    public function test_vehicle_brand_tiene_relacion_has_many_con_vehicles(): void
    {
        $brand = VehicleBrand::factory()->create();
        $vehicles = Vehicle::factory()->count(3)->create([
            'vehicle_brand_id' => $brand->id,
        ]);

        // Verificar que la relación existe y devuelve los vehículos correctos
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $brand->vehicles);
        $this->assertCount(3, $brand->vehicles);
        $this->assertEquals($vehicles->pluck('id')->sort()->values(), $brand->vehicles->pluck('id')->sort()->values());
    }

    /**
     * Test: Vehicle pertenece a VehicleBrand (belongsTo).
     */
    public function test_vehicle_pertenece_a_vehicle_brand(): void
    {
        $brand = VehicleBrand::factory()->create(['brand_name' => 'BMW']);
        $vehicle = Vehicle::factory()->create([
            'vehicle_brand_id' => $brand->id,
        ]);

        // Verificar la relación belongsTo
        $this->assertInstanceOf(VehicleBrand::class, $vehicle->vehicleBrand);
        $this->assertEquals('BMW', $vehicle->vehicleBrand->brand_name);
        $this->assertEquals($brand->id, $vehicle->vehicleBrand->id);
    }

    /**
     * Test: Person tiene relación belongsToMany con Vehicle.
     */
    public function test_person_tiene_relacion_many_to_many_con_vehicles(): void
    {
        $person = Person::factory()->create();
        $brand = VehicleBrand::factory()->create();
        $vehicles = Vehicle::factory()->count(2)->create([
            'vehicle_brand_id' => $brand->id,
        ]);

        // Asociar vehículos a la persona
        $person->vehicles()->attach($vehicles->pluck('id'));

        // Verificar la relación
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $person->vehicles);
        $this->assertCount(2, $person->vehicles);
        $this->assertEquals($vehicles->pluck('id')->sort()->values(), $person->vehicles->pluck('id')->sort()->values());
    }

    /**
     * Test: Vehicle tiene relación belongsToMany con Person (propietarios).
     */
    public function test_vehicle_tiene_relacion_many_to_many_con_people(): void
    {
        $brand = VehicleBrand::factory()->create();
        $vehicle = Vehicle::factory()->create([
            'vehicle_brand_id' => $brand->id,
        ]);
        $owners = Person::factory()->count(3)->create();

        // Asociar propietarios al vehículo
        $vehicle->people()->attach($owners->pluck('id'));

        // Verificar la relación
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $vehicle->people);
        $this->assertCount(3, $vehicle->people);
        $this->assertEquals($owners->pluck('id')->sort()->values(), $vehicle->people->pluck('id')->sort()->values());
    }

    /**
     * Test: Eliminación en cascada cuando se elimina VehicleBrand.
     */
    public function test_eliminar_brand_elimina_vehicles_en_cascada(): void
    {
        $brand = VehicleBrand::factory()->create();
        $vehicle = Vehicle::factory()->create([
            'vehicle_brand_id' => $brand->id,
        ]);

        $vehicleId = $vehicle->id;

        // Eliminar la marca
        $brand->delete();

        // Verificar que el vehículo también fue eliminado (cascade)
        $this->assertDatabaseMissing('vehicles', ['id' => $vehicleId]);
    }

    /**
     * Test: Eliminación de Vehicle también elimina registros en tabla pivote.
     */
    public function test_eliminar_vehicle_elimina_registros_pivote(): void
    {
        $brand = VehicleBrand::factory()->create();
        $vehicle = Vehicle::factory()->create([
            'vehicle_brand_id' => $brand->id,
        ]);
        $person = Person::factory()->create();

        // Asociar persona al vehículo
        $vehicle->people()->attach($person->id);

        // Verificar que existe en tabla pivote
        $this->assertDatabaseHas('person_vehicle', [
            'vehicle_id' => $vehicle->id,
            'person_id' => $person->id,
        ]);

        // Eliminar vehículo
        $vehicle->delete();

        // Verificar que el registro en tabla pivote también se eliminó
        $this->assertDatabaseMissing('person_vehicle', [
            'vehicle_id' => $vehicle->id,
            'person_id' => $person->id,
        ]);
    }

    /**
     * Test: Eliminación de Person también elimina registros en tabla pivote.
     */
    public function test_eliminar_person_elimina_registros_pivote(): void
    {
        $brand = VehicleBrand::factory()->create();
        $vehicle = Vehicle::factory()->create([
            'vehicle_brand_id' => $brand->id,
        ]);
        $person = Person::factory()->create();

        // Asociar vehículo a la persona
        $person->vehicles()->attach($vehicle->id);

        // Verificar que existe en tabla pivote
        $this->assertDatabaseHas('person_vehicle', [
            'vehicle_id' => $vehicle->id,
            'person_id' => $person->id,
        ]);

        // Eliminar persona
        $person->delete();

        // Verificar que el registro en tabla pivote también se eliminó
        $this->assertDatabaseMissing('person_vehicle', [
            'vehicle_id' => $vehicle->id,
            'person_id' => $person->id,
        ]);
    }

    /**
     * Test: Relación many-to-many incluye timestamps.
     */
    public function test_relacion_many_to_many_incluye_timestamps(): void
    {
        $brand = VehicleBrand::factory()->create();
        $vehicle = Vehicle::factory()->create([
            'vehicle_brand_id' => $brand->id,
        ]);
        $person = Person::factory()->create();

        // Asociar
        $person->vehicles()->attach($vehicle->id);

        // Obtener el registro de la tabla pivote
        $pivotRecord = \DB::table('person_vehicle')
            ->where('person_id', $person->id)
            ->where('vehicle_id', $vehicle->id)
            ->first();

        // Verificar que tiene timestamps
        $this->assertNotNull($pivotRecord->created_at);
        $this->assertNotNull($pivotRecord->updated_at);
    }
}

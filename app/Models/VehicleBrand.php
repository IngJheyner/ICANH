<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="VehicleBrand",
 *     type="object",
 *     title="Marca de Vehículo",
 *     description="Modelo de marca de vehículo",
 *     @OA\Property(property="id", type="integer", example=1, description="ID de la marca"),
 *     @OA\Property(property="brand_name", type="string", example="Toyota", description="Nombre de la marca"),
 *     @OA\Property(property="country", type="string", example="Japón", description="País de origen"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-11-10 10:30:00"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-11-10 10:30:00")
 * )
 */
class VehicleBrand extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'vehicle_brands';

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'brand_name',
        'country',
    ];

    /**
     * Obtener los vehículos de la marca.
     */
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'vehicle_brand_id');
    }
}

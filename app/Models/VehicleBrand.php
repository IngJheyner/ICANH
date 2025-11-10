<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

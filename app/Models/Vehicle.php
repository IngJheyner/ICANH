<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'vehicles';

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'model',
        'vehicle_brand_id',
        'number_of_doors',
        'color',
    ];

    /**
     * Obtener la marca del vehículo.
     */
    public function vehicleBrand()
    {
        return $this->belongsTo(VehicleBrand::class, 'vehicle_brand_id');
    }

    /**
     * Obtener los propietarios del vehículo.
     */
    public function people()
    {
        return $this->belongsToMany(Person::class, 'person_vehicle')
                    ->withTimestamps();
    }
}

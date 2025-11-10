<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'people';

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'identification_number',
        'name',
    ];

    /**
     * Obtener los vehículos de la persona.
     */
    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class, 'person_vehicle')
                    ->withTimestamps();
    }
}

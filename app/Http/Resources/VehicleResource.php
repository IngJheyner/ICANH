<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    /**
     * Transforma el recurso en un array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'model' => $this->model,
            'vehicle_brand_id' => $this->vehicle_brand_id,
            'number_of_doors' => $this->number_of_doors,
            'color' => $this->color,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            // Incluir relaciones solo si están cargadas (evita N+1)
            'vehicle_brand' => new VehicleBrandResource($this->whenLoaded('vehicleBrand')),
            'owners' => PersonResource::collection($this->whenLoaded('people')),
        ];
    }
}

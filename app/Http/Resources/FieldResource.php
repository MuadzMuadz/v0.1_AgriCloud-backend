<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FieldResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'location' => [
                'warehouse_id' => $this->farmer_warehouse_id,
                'url' => $this->farmerwarehouse->location_url,
                'latitude' => $this->latitude,        // titik pin utama
                'longitude' => $this->longitude,      // titik pin utama
                'custom_polygon' => $this->custom_polygon ?? [],  // polygon area, default kosong kalau null
            ],
            'area' => $this->area,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

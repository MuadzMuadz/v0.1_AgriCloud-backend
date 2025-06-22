<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\CropTemplate;

class CropTemplateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Mapping jenis number to category
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'jenis' => $this->jenis,
            'thumbnail' => $this->thumbnail ? asset('storage/' . $this->thumbnail) : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

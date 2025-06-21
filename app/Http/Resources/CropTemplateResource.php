<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
        $jenisKategori = [
            '1' => 'Tanaman Pangan',
            '2' => 'Hortikultura',
            '3' => 'Palawija',
        ];

        $kategori = isset($jenisKategori[$this->jenis]) ? $jenisKategori[$this->jenis] : null;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'kategori' => $kategori,
            'thumbnail' => $this->image_url, // ini datang dari accessor di model  
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

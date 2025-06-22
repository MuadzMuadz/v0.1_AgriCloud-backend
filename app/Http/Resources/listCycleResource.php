<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class listCycleResource extends JsonResource
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
            'name' => optional($this->cropTemplate)->name,
            'description' => optional($this->cropTemplate)->description,
            'location' => $this->field->name,
            'status' => $this->status,
            'progress' => $this->progress,
            'thumbnail' => $this->cropTemplate->thumbnail,
            'start_date' => $this->start_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

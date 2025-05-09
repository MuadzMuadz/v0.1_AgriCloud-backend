<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GrowStageResource extends JsonResource
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
            'crop_template_id' => $this->crop_template_id,
            'stage_name' => $this->stage_name,
            'day_offset' => $this->day_offset,
            'expected_action' => $this->expected_action,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'crop_template' => [
                'id' => $this->cropTemplate->id,
                'name' => $this->cropTemplate->name,
                'description' => $this->cropTemplate->description,
                'created_at' => $this->cropTemplate->created_at,
                'updated_at' => $this->cropTemplate->updated_at,
            ],
        ];
    }
}

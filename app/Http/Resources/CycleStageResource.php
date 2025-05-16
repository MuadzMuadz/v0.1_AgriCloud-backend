<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CycleStageResource extends JsonResource
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
            'cycle' => [
                'cycle_id' => $this->cycle->id,
                'cycle_name' => $this->cycle->name,
                'field' => new FieldResource($this->cycle->field), // Assuming 'field' is a relationship and FieldResource exists
            ],
            'stage_name' => $this->stage_name,
            'day_offset' => $this->day_offset,
            'expected_action' => $this->expected_action,
            'description' => $this->description,
            'started_at' => $this->started_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'cycle' => new CycleResource($this->whenLoaded('cycle')), // Include related cycle if loaded
        ];
    }
}

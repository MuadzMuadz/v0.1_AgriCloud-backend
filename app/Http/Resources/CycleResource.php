<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CycleResource extends JsonResource
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
            'name' => $this->croptemplate->name,
            'description' => $this->croptemplate->description,
            'location' => $this->location,
            'current_stage' => $this->cyclestage ? [
                'id' => $this->cyclestage->id,
                'name' => $this->cyclestage->stage,
                'description' => $this->cyclestage->description,
                'start_date' => $this->cyclestage->pivot->start_date,
                'end_date' => $this->cyclestage->pivot->end_date,
            ] : null,
            'stages' => $this->stages->map(function ($stage) {
                return [
                    'id' => $stage->id,
                    'name' => $stage->name,
                    'description' => $stage->description,
                    'start_date' => $stage->pivot->start_date,
                    'end_date' => $stage->pivot->end_date,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

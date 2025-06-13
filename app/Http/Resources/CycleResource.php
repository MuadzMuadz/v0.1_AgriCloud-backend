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
        $currentStage = null;
        $today = now();

        foreach ($this->cycleStage->sortBy('day_offset')->values() as $index => $stage) {
            $prevEnd = $index > 0 ? $this->cycleStage[$index - 1]->end_date : null;

            if (
                ($prevEnd === null || $prevEnd < $today) &&
                $today <= $stage->end_date
            ) {
                $currentStage = $stage;
                break;
            }
        }


        return [
            'id' => $this->id,
            'name' => optional($this->cropTemplate)->name,
            'description' => optional($this->cropTemplate)->description,
            'location' => $this->field->location,
            'current_stage' => $currentStage ? [
                'id' => $currentStage->id,
                'name' => $currentStage->stage,
                'description' => $currentStage->description,
                'start_date' => $currentStage->start_date,
                'end_date' => $currentStage->end_date,
            ] : null,
            'stages' => $this->cycleStage->map(function ($stage) {
                return [
                    'id' => $stage->id,
                    'name' => $stage->stage,
                    'description' => $stage->description,
                    'start_date' => $stage->start_date,
                    'end_date' => $stage->end_date,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

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
        // Pastikan status selalu di-refresh sebelum mengembalikan data
        $this->refreshStatusIfNeeded();
        
        // Cek apakah ada stage yang sudah lewat
        
        $today = now();
        $startDate = $this->start_date;
        $endDate = optional($this->cycleStage->sortByDesc('day_offset')->first())->start_at;

        // $status = 'pending';
        $currentStage = null;

        if ($startDate && $today->lt($startDate)) {
            $status = 'pending';
        } elseif ($startDate && $today->gte($startDate)) {
            // Sudah mulai, cari current stage
            $status = 'active';

            $sortedStages = $this->cycleStage->sortBy('day_offset')->values();

            foreach ($sortedStages as $index => $stage) {
                $startAt = $stage->start_at;
                $nextStart = optional($sortedStages->get($index + 1))->start_at;

                if ($startAt && $today->gte($startAt) && (!$nextStart || $today->lt($nextStart))) {
                    $currentStage = $stage;
                    break;
                }
            }

            // Optional: completed
            if ($endDate && $today->gt($endDate)) {
                $status = 'completed';
                $currentStage = null;
            }
        }

        return [
            'id' => $this->id,
            'name' => optional($this->cropTemplate)->name,
            'description' => optional($this->cropTemplate)->description,
            'location' => $this->field->name,
            'status' => $status,
            'start_date' => $this->start_date,
            'current_stage' => $currentStage ? [
                'name' => $currentStage->stage_name,
                'description' => $currentStage->description,
                'start_date' => $currentStage->start_at,
                'end_date' => optional($this->cycleStage->sortBy('day_offset')->get($this->cycleStage->search($currentStage) + 1))->start_at, // = next stage's start_at
            ] : null,
            'stages' => $this->cycleStage->map(function ($stage) {
                return [
                    'id' => $stage->id,
                    'name' => $stage->stage_name,
                    'description' => $stage->description,
                    'start_date' => $stage->start_at,
                    // 'end_date' => $stage->end_date,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

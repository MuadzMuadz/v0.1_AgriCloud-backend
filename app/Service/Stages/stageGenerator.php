<?php

namespace App\Services\Cycle;

use App\Models\Cycle;
use App\Models\CycleStages;
use App\Models\GrowStages;
use Illuminate\Support\Carbon;

class CycleStageGenerator
{
    public function generateFromTemplate(Cycle $cycle, string $startDate): void
    {
        $growStages = GrowStages::where('crop_template_id', $cycle->crop_template_id)
            ->orderBy('id')
            ->get();

        foreach ($growStages as $stage) {
            CycleStages::create([
                'cycle_id'         => $cycle->id,
                'stage_name'       => $stage->stage_name,
                'expected_action'  => $stage->expected_action,
                'description'      => $stage->description,
                'day_offset'       => $stage->day_offset,
                'start_at'         => Carbon::parse($startDate)->addDays($stage->day_offset),
            ]);
        }
    }
}

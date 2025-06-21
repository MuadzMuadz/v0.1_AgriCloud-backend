<?php

namespace Database\Factories;

use App\Models\Cycle;
use App\Models\CycleStages;
use App\Models\GrowStages;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CycleStages>
 */
class CycleStageFactory extends Factory
{
    protected $model = CycleStages::class;

    public function definition(): array
    {
        return [
            'cycle_id' => Cycle::factory(),
            'grow_stage_id' => GrowStages::factory(),
            'start_at' => $this->faker->dateTime,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\CropTemplate;
use App\Models\GrowStages;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GrowStages>
 */
class GrowStagesFactory extends Factory
{
    protected $model = GrowStages::class;

    public function definition(): array
    {
        return [
            'crop_template_id' => CropTemplate::factory(),
            'stage_name' => $this->faker->word,
            'day_offset' => $this->faker->numberBetween(1, 30),
            'expected_action' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
        ];
    }
}

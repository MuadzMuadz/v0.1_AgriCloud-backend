<?php

namespace Database\Factories;

use App\Models\CropTemplate;
use App\Models\Field;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cycle>
 */
class CycleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'field_id' => Field::factory(),
            'crop_template_id' => CropTemplate::factory(),
            'start_date' => $this->faker->date(),
            'status' => $this->faker->randomElement(['pending', 'active', 'completed']),
        ];
    }
}

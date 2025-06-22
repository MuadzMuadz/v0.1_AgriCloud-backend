<?php

namespace Database\Factories;

use App\Models\CropTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CropTemplate>
 */
class CropTemplateFactory extends Factory
{
    protected $model = CropTemplate::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word,
            'description' => $this->faker->sentence,
            'jenis' => $this->faker->randomElement(['Tanaman Pangan', 'Hortikultura', 'Palawija']),
        ];
    }
}

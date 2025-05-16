<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Database\Factories\UserFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Field>
 */
class FieldFactory extends Factory
{
    protected $model = FieldFactory::class;

    public function definition(): array
    {
        return [
            'user_id' => UserFactory::factory(),
            'name' => $this->faker->word,
            'location' => $this->faker->address,
            'area' => $this->faker->randomFloat(2, 0.5, 10),
        ];
    }
}

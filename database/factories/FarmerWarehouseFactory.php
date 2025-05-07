<?php

namespace Database\Factories;

use App\Models\FarmerWarehouse;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FarmerWarehouse>
 */
class WarehouseFactory extends Factory
{
    protected $model = FarmerWarehouse::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->word,
            'location' => $this->faker->url,
        ];
    }
}

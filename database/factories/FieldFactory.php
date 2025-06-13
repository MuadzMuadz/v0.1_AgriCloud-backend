<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Field;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Field>
 */
class FieldFactory extends Factory
{
    protected $model = Field::class;

    public function definition(): array
    {
        $latitude = $this->faker->latitude( -6.5, -5.5 );
        $longitude = $this->faker->longitude(106, 107);

        // // Generate a custom polygon with 4 points
        // $customPolygon = [
        //     ['lat' => $latitude, 'lng' => $longitude],
        //     ['lat' => $latitude + 0.001, 'lng' => $longitude],
        //     ['lat' => $latitude, 'lng' => $longitude + 0.001],
        //     ['lat' => $latitude, 'lng' => $longitude],
        // ];

        return [
            'user_id' => User::factory(),
            'name' => $this->faker->unique()->word(),
            'latitude' => $latitude,
            'longitude' => $longitude,
            // 'custom_polygon' => $customPolygon,
            'area' => $this->faker->randomFloat(2, 0.5, 10),
        ];
    }
}

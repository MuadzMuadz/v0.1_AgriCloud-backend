<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CropTemplate;
use App\Models\GrowStages;

class CropTemplateSeeder extends Seeder
{
    public function run(): void
    {
        CropTemplate::factory()
        ->has(
            GrowStages::factory()->count(5)->sequence(
                ['day_offset' => 0],
                ['day_offset' => 5],
                ['day_offset' => 10],
                ['day_offset' => 15],
                ['day_offset' => 20],
            )
        )
        ->create();
        // You can add more CropTemplates with different GrowStages if needed
        CropTemplate::factory()
            ->has(GrowStages::factory()->count(3)->sequence(
                ['day_offset' => 0],
                ['day_offset' => 7],
                ['day_offset' => 14],
            ))
            ->create();

    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CropTemplate;
use App\Models\GrowStages;

class CropTemplateSeeder extends Seeder
{
    public function run(): void
    {
        // Generate 10 data crop template
        CropTemplate::factory()->count(10)->create()->each(function ($cropTemplate) {
            // Generate 3 grow stages for each crop template
            $cropTemplate->growStage()->createMany(
                // Untuk tiap crop template, generate 3-5 grow stages
                GrowStages::factory()
                    ->count(rand(3, 5))
                    ->make([
                        'crop_template_id' => $cropTemplate->id,
                    ])->toArray()
            );
        });
    }
}

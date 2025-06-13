<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Field;

class FieldSeeder extends Seeder
{
    public function run()
        {
            // Ambil semua user kecuali user dengan id 1
            User::where('id', '!=', 1)->each(function ($user) {
                $user->fields()->createMany(
                    Field::factory()->count(3)->make()->map(function ($field) {
                        return $field->toArray();
                    })->all()
                );

            });

        }
}

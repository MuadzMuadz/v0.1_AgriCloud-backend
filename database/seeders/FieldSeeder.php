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
        $users = User::where('id', '!=', 1)->get();

        foreach ($users as $user) {
            // Buat 3 field untuk setiap user (selain id 1)
            $user->fields()->createMany(
            Field::factory()->count(3)->make()->toArray()
            );
        }
    }
}

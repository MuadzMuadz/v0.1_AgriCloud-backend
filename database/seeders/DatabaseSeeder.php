<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
<<<<<<< HEAD
          UserSeeder::class,
          FieldSeeder::class,
          CropTemplateSeeder::class,
=======
          UserSeeder::class,  
>>>>>>> efd2496 (refactor route and auth and gambar)
        ]);
        
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::factory()->create([
            'name' => 'Admin One',
            'email' => 'admin@example.com',
            'phone_number' => '081234567890',
            'role' => 'admin',
        ]);

        // Farmer 1
        User::factory()->create([
            'name' => 'Farmer One',
            'email' => 'farmer1@example.com',
            'phone_number' => '082111112222',
            'role' => 'farmer',
        ]);

        // Farmer 2
        User::factory()->create([
            'name' => 'Farmer Two',
            'email' => 'farmer2@example.com',
            'phone_number' => '083333344444',
            'role' => 'farmer',
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'nama' => 'Test User',
            'username' => 'test@example.com',
            'role' => 'user',
        ]);

        User::create([
            'nama' => 'Admin',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);


        User::create([
            'nama' => 'Petugas',
            'username' => 'user',
            'password' => Hash::make('user123'),
            'role' => 'user'
        ]);
    }
}

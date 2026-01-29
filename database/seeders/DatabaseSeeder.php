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

        // Use updateOrCreate so seeder is idempotent (won't fail on duplicate unique keys)
        User::updateOrCreate([
            'username' => 'test@example.com'
        ], [
            'nama' => 'Test User',
            'role' => 'user',
            'password' => Hash::make('password')
        ]);

        User::updateOrCreate([
            'username' => 'petugas'
        ], [
            'nama' => 'Petugas',
            'password' => Hash::make('petugas123'),
            'role' => 'petugas'
        ]);

        User::updateOrCreate([
            'username' => 'user'
        ], [
            'nama' => 'pengguna',
            'password' => Hash::make('user123'),
            'role' => 'user'
        ]);

        User::updateOrCreate([
            'username' => 'admin'
        ], [
            'nama' => 'Atasan',
            'password' => Hash::make('admin123'),
            'role' => 'atasan'
        ]);

        User::updateOrCreate([
            'username' => 'nama'
        ], [
            'nama' => 'Approval',
            'password' => Hash::make('nama123'),
            'role' => 'approval'
        ]);
    }
}

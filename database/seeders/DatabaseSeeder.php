<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create or find roles
        $adminRole = Role::firstOrCreate(
            ['role' => 'admin'] // Revert to 'role' instead of 'name'
        );

        $userRole = Role::firstOrCreate(
            ['role' => 'user']
        );

        // Create or find users
        User::firstOrCreate(
            ['email' => 'kudo@gmail.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('manzakin'),
                'role_id' => $adminRole->id,
            ]
        );

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password123'),
                'role_id' => $userRole->id,
            ]
        );
    }
}
<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;  // Import the Role model
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $adminRole = Role::create([
            'role' => 'admin',
        ]);

        $userRole = Role::create([
            'role' => 'user',
        ]);


        User::create([
            'name' => 'Admin User',
            'email' => 'kudo@gmail.com',
            'password' => Hash::make('manzakin'),  
            'role_id' => $adminRole->id,  
        ]);

        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'), 
            'role_id' => $userRole->id,  
        ]);
    }
}

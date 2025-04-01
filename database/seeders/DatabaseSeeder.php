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
        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'username' => 'janesmith',
            'password' => Hash::make('password'),
            'bio' => 'Hi, I am Jane!',
            'role_id' => 2,
        ]);

        User::create([
            'name' => 'Alice Brown',
            'email' => 'alice@example.com',
            'username' => 'alice',
            'password' => Hash::make('password'),
            'bio' => 'Hello there!',
            'role_id' => 2,
        ]);

        User::create([
            'name' => 'Bob Wilson',
            'email' => 'bob@example.com',
            'username' => 'bob',
            'password' => Hash::make('password'),
            'bio' => 'I love coding!',
            'role_id' => 2,
        ]);

        User::create([
            'name' => 'Mike Johnson',
            'email' => 'mike@example.com',
            'username' => 'mikej',
            'password' => Hash::make('password'),
            'bio' => 'Nice to meet you!',
            'role_id' => 2,
        ]);

        User::create([
            'name' => 'Sarah Lee',
            'email' => 'sarah@example.com',
            'username' => 'sarahlee',
            'password' => Hash::make('password'),
            'bio' => 'I am Sarah!',
            'role_id' => 2,
        ]);
    }
}
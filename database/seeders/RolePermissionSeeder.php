<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $admin = Role::create(['name' => 'Admin', 'description' => 'Administrator with full access']);
        $moderator = Role::create(['name' => 'Moderator', 'description' => 'Moderator with content moderation access']);
        $user = Role::create(['name' => 'User', 'description' => 'Regular user with basic access']);

        // Create permissions
        $gererUtilisateurs = Permission::create(['name' => 'gerer_utilisateurs', 'description' => 'Manage users']);
        $modererContenus = Permission::create(['name' => 'moderer_contenus', 'description' => 'Moderate content']);
        $gererGroupes = Permission::create(['name' => 'gerer_groupes', 'description' => 'Manage groups']);

        // Assign permissions to roles
        $admin->permissions()->attach([$gererUtilisateurs->id, $modererContenus->id, $gererGroupes->id]);
        $moderator->permissions()->attach([$modererContenus->id, $gererGroupes->id]);
        // User role has no special permissions
    }
}
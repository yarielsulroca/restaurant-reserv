<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            // User permissions
            'login-user-panel',
            'create-reservation',
            'delete-own-reservation',
            'view-available-tables',
            'change-own-password',
            
            // Admin permissions
            'login-admin-panel',
            'manage-tables',
            'update-reservations',
            'manage-user-roles',
            'manage-user-status',
            'update-user-password'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create Roles and assign permissions
        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo([
            'login-user-panel',
            'create-reservation',
            'delete-own-reservation',
            'view-available-tables',
            'change-own-password'
        ]);

        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo([
            'login-admin-panel',
            'manage-tables',
            'update-reservations',
            'manage-user-roles',
            'manage-user-status',
            'update-user-password'
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        // Create permissions
        $permissions = [
            'view users',
            'create users',
            'edit users',
            'delete users',
            'view tables',
            'create tables',
            'edit tables',
            'delete tables',
            'view reservations',
            'create reservations',
            'edit reservations',
            'delete reservations',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Give all permissions to admin role
        $adminRole->givePermissionTo(Permission::all());

        // Give specific permissions to user role
        $userRole->givePermissionTo([
            'view tables',
            'view reservations',
            'create reservations',
            'edit reservations',
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Use default guard
        $guard = config('auth.defaults.guard', 'web');

        // Create basic roles
        $roles = ['Administrador', 'Gerente'];
        foreach ($roles as $r) {
            Role::firstOrCreate(['name' => $r, 'guard_name' => $guard]);
        }

        // Clear permission cache
        if (app()->bound(\Spatie\Permission\PermissionRegistrar::class)) {
            app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }
}

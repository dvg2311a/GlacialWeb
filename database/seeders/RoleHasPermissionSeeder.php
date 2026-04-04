<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleHasPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissionAdministrator = Permission::all();
        Role::findByName('Administrador', 'web')-> syncPermissions($permissionAdministrator);

        $permissionGerente = Permission::all();
        Role::findByName('Gerente', 'web')->syncPermissions($permissionGerente);
    }
}

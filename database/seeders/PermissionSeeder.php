<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permission = [
            'Ver usuarios',
            'Crear usuarios',
            'Editar usuarios',
            'Eliminar usuarios',

            'Ver roles',
            'Crear roles',
            'Editar roles',
            'Eliminar roles',

            'Ver permisos',
            'Crear permisos',
            'Editar permisos',
            'Eliminar permisos',

            'Ver Tipos de Freezers',
            'Crear Tipos de Freezers',
            'Editar Tipos de Freezers',
            'Eliminar Tipos de Freezers',

            'Ver Freezers',
            'Crear Freezers',
            'Editar Freezers',
            'Eliminar Freezers',

            'Ver Dimensiones de Placas',
            'Crear Dimensiones de Placas',
            'Editar Dimensiones de Placas',
            'Eliminar Dimensiones de Placas',

            'Ver Placas',
            'Crear Placas',
            'Editar Placas',
            'Eliminar Placas',

            'Cer Tipos de Productos',
            'Crear Tipos de Productos',
            'Editar Tipos de Productos',
            'Eliminar Tipos de Productos',

            'Ver Sabores de Productos',
            'Crear Sabores de Productos',
            'Editar Sabores de Productos',
            'Eliminar Sabores de Productos',

            'Ver Estado de Productos',
            'Crear Estado de Productos',
            'Editar Estado de Productos',
            'Eliminar Estado de Productos',

            'Ver Productos',
            'Crear Productos',
            'Editar Productos',
            'Eliminar Productos',

            'Ver Pedidos',
            'Crear Pedidos',
            'Editar Pedidos',
            'Eliminar Pedidos',

            'Ver Detalles de Pedidos',
            'Crear Detalles de Pedidos',
            'Editar Detalles de Pedidos',
            'Eliminar Detalles de Pedidos',

            'Ver Tipos de Carros',
            'Crear Tipos de Carros',
            'Editar Tipos de Carros',
            'Eliminar Tipos de Carros',

            'Ver Estado de Carros',
            'Crear Estado de Carros',
            'Editar Estado de Carros',
            'Eliminar Estado de Carros',

            'Ver Carros',
            'Crear Carros',
            'Editar Carros',
            'Eliminar Carros',

            'Ver Vendedores',
            'Crear Vendedores',
            'Editar Vendedores',
            'Eliminar Vendedores',

            'Ver Reporte Diario',
            'Crear Reporte Diario',
            'Editar Reporte Diario',
            'Eliminar Reporte Diario',

            'Ver Detalles de Reporte Diario',
            'Crear Detalles de Reporte Diario',
            'Editar Detalles de Reporte Diario',
            'Eliminar Detalles de Reporte Diario',

            'Ver Inventario',
            'Crear Inventario',
            'Editar Inventario',
            'Eliminar Inventario',
        ];

        $guard = config('auth.defaults.guard', 'web');

        foreach ($permission as $value) {
            Permission::firstOrCreate([
                'name' => $value,
                'guard_name' => $guard,
            ]);
        }
    }
}

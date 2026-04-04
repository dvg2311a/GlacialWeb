<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{


    public function index()
    {
        $roles = Role::with('permissions')->get()->map(function ($role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
                'permissions' => $role->permissions->map(function ($p) {
                    return ['id' => $p->id, 'name' => $p->name];
                })->values(),
            ];
        });

        // dd($roles);

        return Inertia::render('Roles/Index', [
            'roles' => $roles,
        ]);
    }


    public function create()
    {
        $role = new Role();
        // Enviar la lista completa de permisos (id + name) como `permissions`
        $permissions = Permission::select('id', 'name')->orderBy('id', 'asc')->get();

        return Inertia::render('Roles/Create', ['role' => $role, 'permissions' => $permissions]);
    }

    public function store(Request $request)
    {


        return redirect()->route('roles.index')->with('success', 'No se pueden crear roles.');
    }


    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;


use App\Models\TypeFreezer;
use App\Http\Requests\TypeFreezerRequest;
use Inertia\Inertia;

class TypeFreezerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $type_freezer = TypeFreezer::all();

        return Inertia::render('TypeFreezer/Index', ['type_freezer' => $type_freezer]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $type_freezer = new TypeFreezer();

        return Inertia::render('TypeFreezer/Create', ['type_freezer' => $type_freezer]);
    }
    public function store(TypeFreezerRequest $request)
    {

        TypeFreezer::create($request -> validated());
        return redirect()->route('type_freezers.index')->with('success', 'Tipo de Freezer Creado Correctamente');
    }

    public function show(string $id)
    {
        $type_freezer = TypeFreezer::findOrFail($id);
        return Inertia::render('TypeFreezer/Show', ['type_freezer' => $type_freezer]);
    }

    public function edit(string $id)
    {
        $type_freezer = TypeFreezer::findOrFail($id);
        return Inertia::render('TypeFreezer/Edit', ['type_freezer' => $type_freezer]);
    }

    public function update(TypeFreezerRequest $request, string $id)
    {
        $type_freezer = TypeFreezer::findOrFail($id);
        $type_freezer->update($request->validated());
        return redirect()->route('type_freezers.index')->with('success', 'Tipo de Freezer Actualizado Correctamente');
    }

    public function destroy(string $id)
    {
        $type_freezers = TypeFreezer::findOrFail($id);
        $type_freezers->delete();

        return redirect()->route('type_freezers.index')->with('success', 'Tipo de Freezer Eliminado Correctamente');
    }
}

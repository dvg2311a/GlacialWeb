<?php

namespace App\Http\Controllers;

use App\Models\TypeFreezer;
use Inertia\Inertia;
use App\Http\Requests\FreezerRequest;
use App\Models\Freezer;


class FreezerController extends Controller
{
    public function index()
    {
        $freezer = Freezer::all();
        $type = TypeFreezer::all();
        return Inertia::render('Freezer/Index', ['freezer' => $freezer, 'type' => $type]);
    }

    public function create()
    {
        $freezer = new Freezer();
        $type = TypeFreezer::all();
        return Inertia::render('Freezer/Create', ['freezer' => $freezer, 'type' => $type]);
    }

    public function store(FreezerRequest $request)
    {
        Freezer::create($request -> validated());
        return redirect()->route('freezers.index')->with('success', 'Freezer creado correctamente');
    }

    public function show(string $id)
    {
        $freezer = Freezer::findOrFail($id);
        return Inertia::render('Freezer/Show', ['freezer' => $freezer]);

    }

    public function edit(string $id)
    {
        $freezer = Freezer::findOrFail($id);
        $type = TypeFreezer::all();
        return Inertia::render('Freezer/Edit', ['freezer' => $freezer, 'type' => $type]);
    }

    public function update(FreezerRequest $request, string $id)
    {
        $freezer = Freezer::findOrFail($id);
        $freezer->update($request->validated());
        return redirect()->route('freezers.index')->with('success', 'Freezer actualizado correctamente');
    }

    public function destroy(string $id)
    {
        $freezer = Freezer::findOrFail($id);
        $freezer->delete();
        return redirect()->route('freezers.index')->with('success', 'Freezer eliminado correctamente');
    }
}

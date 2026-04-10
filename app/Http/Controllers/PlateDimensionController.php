<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlateDimensionRequest;
use App\Models\PlateDimension;
use Inertia\Inertia;

class PlateDimensionController extends Controller
{
    public function index(){
        $plate_dimensions = PlateDimension::all();

        return Inertia::render('PlateDimension/Index', ['plate_dimensions' => $plate_dimensions]);
    }

    public function create(){
        $plate_dimensions = new PlateDimension();

        return Inertia::render('PlateDimension/Create', ['plate_dimensions' => $plate_dimensions]);
    }

    public function store(PlateDimensionRequest $request){
        PlateDimension::create($request -> validated());
        return redirect()->route('plate_dimensions.index')->with('sucsess', 'Dimensión de placas registrada correctamente');
    }

    public function show(string $id){
        $plate_dimension = PlateDimension::findOrFail($id);

        return Inertia::render('PlateDimension/Show', ['plate_dimension' => $plate_dimension]);
    }

    public function edit(string $id){
        $plate_dimension = PlateDimension::findOrFail($id);
        return Inertia::render('PlateDimension/Edit', ['plate_dimension' => $plate_dimension]);
    }

    public function update(PlateDimensionRequest $request, string $id){
        $plate_dimensions = PlateDimension::findOrFail($id);
        $plate_dimensions->update($request -> validated());

        return redirect()->route('plate_dimensions.index')->with('success', 'Dimensión de placa actualizada correctamente');
    }

    public function destroy(string $id){
        $plate_dimension = PlateDimension::findOrFail($id);
        $plate_dimension->delete();

        return redirect()->route('plate_dimensions.index')->with('success', 'Dimensión de placa eliminada correctamente');
    }
}

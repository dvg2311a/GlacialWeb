<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlateRequest;
use App\Models\Freezer;
use App\Models\Plate;
use App\Models\PlateDimension;
use Inertia\Inertia;

class PlateController extends Controller
{

    public function index()
    {
        $plates = Plate::with('plateDimension', 'freezer')->latest()->get();
        $plate_dimension = PlateDimension::all();
        $freezer = Freezer::all();
        return Inertia::render('Plate/Index', ['plates' => $plates, 'plate_dimension' => $plate_dimension, 'freezer' => $freezer]);
    }

    public function create()
    {
        $plate = new Plate();
        $plate_dimensions = PlateDimension::all();
        $freezers = Freezer::all();
        return Inertia::render('Plate/Create', ['plate' => $plate, 'plate_dimensions' => $plate_dimensions, 'freezers' => $freezers]);
    }

    public function store(PlateRequest $request)
    {
        Plate::create($request->validated());

        return redirect()->route('plates.index')->with('success', 'Placa creada correctamente');
    }

    public function show(string $id)
    {
        $plate = Plate::findOrFail($id);
        $plate_dimension = PlateDimension::all();
        $freezer = Freezer::all();
        return Inertia::render('Plate/Show', ['plate' => $plate, 'plate_dimension' => $plate_dimension, 'freezer' => $freezer]);
    }

    public function edit(string $id)
    {
        $plate = Plate::findOrFail($id);
        $plate_dimension = PlateDimension::all();
        $freezer = Freezer::all();

        return Inertia::render('Plate/Edit', ['plate' => $plate, 'plate_dimension' => $plate_dimension, 'freezer' => $freezer]);
    }


    public function update(PlateRequest $request, string $id)
    {
        $plates = Plate::findOrFail($id);
        $plates->updated($request->validated());

        return redirect()->route('plates.index')->with('success', 'Placa actualizada correctamente');
    }


    public function destroy(string $id)
    {
        $plate = Plate::findOrFail($id);
        $plate->delete();

        return redirect()->route('plates.index')->with('success', 'Placa borrada correctamente');
    }
}

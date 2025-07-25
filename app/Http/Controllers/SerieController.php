<?php

namespace App\Http\Controllers;

use App\Models\Serie;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SerieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todas las series
        $series = Serie::paginate(10);

        return Inertia::render('GestionarLicencias/Series/index', [
            'series' => $series,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('GestionarLicencias/Series/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos del request
        $validated = $request->validate([
            'serial' => 'required|string|max:255',
            'cantidad_equipo' => 'nullable|integer|max:500',
           'estado' => 'required|in:0,1',
            'precio' => 'required|numeric|min:0',
            'duracion' => 'required|integer|min:1',
            'fecha_inicio' => 'required|string|max:255',
            'fecha_finalizacion' => 'required|string|max:255'
        ]);

        // Crear una nueva serie
        Serie::create($validated);

        // Redirigir a la lista de series con un mensaje de éxito
        return redirect()->route('admin.series')->with('success', 'Serie creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Serie $serie)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Serie $serie)
    {
        return Inertia::render('GestionarLicencias/Series/edit', [
            'serie' => $serie,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Serie $serie)
    {
        // Validar los datos del request
        $validated = $request->validate([
            'serial' => 'required|string|max:255',
            'cantidad_equipo' => 'nullable|number|max:500',
            'estado' => 'required|in:activo,inactivo',
            'precio' => 'required|numeric|min:0',
            'duracion' => 'required|integer|min:1',
            'fecha_inicio' => 'required|string|max:255',
            'fecha_finalizacion' => 'required|string|max:255'
        ]);

        // Actualizar la serie
        $serie->update($validated);

        // Redirigir a la lista de series con un mensaje de éxito
        return redirect()->route('admin.series')->with('success', 'Serie actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Serie $serie)
    {
        // Eliminar la serie
        $serie->delete();

        // Redirigir a la lista de series con un mensaje de éxito
        return redirect()->route('admin.series')->with('success', 'Serie eliminada exitosamente.');
    }
}

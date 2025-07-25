<?php

namespace App\Http\Controllers;

use App\Models\Licencia;
use App\Models\Marca;
use App\Models\Serie;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LicenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todas las licencias
       $licencias = Licencia::with(['serie', 'marca'])->paginate(10);

      return Inertia::render('GestionarLicencias/Licencias/index', [
            'licencias' => $licencias,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
          $series = Serie::all();
          $marcas = Marca::all();

      return Inertia::render('GestionarLicencias/Licencias/create', [
            'series' => $series,
            'marcas' => $marcas
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    // Validar los datos del request
    $validated = $request->validate([
        'nombre' => 'required|string|max:255',
        'serie_id' => 'required|exists:series,id',
        'marca_id' => 'required|exists:marcas,id',
        'precio' => 'required|numeric|min:0',
        'precio_mayorista' => 'nullable|numeric|min:0',
        'precio_renovacion' => 'nullable|numeric|min:0',
        'compartida' => 'required|in:0,1',
        'trasladable' => 'required|in:0,1',
        'caducable' => 'required|in:0,1',
        'formateable' => 'required|in:0,1',
        'compra_asistida' => 'required|in:0,1',
    ]);

    // Crear una nueva licencia con los datos validados
    Licencia::create($validated);

    // Redirigir a la lista de licencias con un mensaje de éxito
    return redirect()->route('admin.licencias')->with('success', 'Licencia creada exitosamente.');
}


    /**
     * Display the specified resource.
     */
    public function show(Licencia $licencia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Licencia $licencia)
    {
        return Inertia::render('GestionarLicencias/Licencias/edit', [
            'licencia' => $licencia,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Licencia $licencia)
    {
        // Validar los datos del request
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'precio' => 'required|numeric|min:0',
        ]);

        // Actualizar la licencia
        $licencia->update($validated);

        // Redirigir a la lista de licencias con un mensaje de éxito
        return redirect()->route('admin.licencias.index')->with('success', 'Licencia actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Licencia $licencia)
    {
        // Eliminar la licencia
        $licencia->delete();

        // Redirigir a la lista de licencias con un mensaje de éxito
        return redirect()->route('admin.licencias.index')->with('success', 'Licencia eliminada exitosamente.');
    }
}

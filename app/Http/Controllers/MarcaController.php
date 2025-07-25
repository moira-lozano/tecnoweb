<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todas las marcas
        $marcas = Marca::paginate(10);

        return Inertia::render('GestionarLicencias/Marcas/index', [
            'marcas' => $marcas,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('GestionarLicencias/Marcas/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos del request
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
        ]);

        // Crear una nueva marca
        Marca::create($validated);

        // Redirigir a la lista de marcas con un mensaje de éxito
        return redirect()->route('admin.marcas')->with('success', 'Marca creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Marca $marca)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Marca $marca)
    {
        return Inertia::render('GestionarLicencias/Marcas/edit', [
            'marca' => $marca,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Marca $marca)
    {
        // Validar los datos del request
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
        ]);

        // Actualizar la marca
        $marca->update($validated);

        // Redirigir a la lista de marcas con un mensaje de éxito
        return redirect()->route('admin.marcas')->with('success', 'Marca actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Marca $marca)
    {
        // Eliminar la marca
        $marca->delete();

        // Redirigir a la lista de marcas con un mensaje de éxito
        return redirect()->route('admin.marcas')->with('success', 'Marca eliminada exitosamente.');
    }
}

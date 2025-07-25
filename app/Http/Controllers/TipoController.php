<?php

namespace App\Http\Controllers;

use App\Models\Tipo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todos los tipos
        $tipos = Tipo::paginate(10);

        return Inertia::render('GestionarPersonas/Tipos/index', [
            'tipos' => $tipos
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('GestionarPersonas/Tipos/create');
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

        // Crear un nuevo tipo
        Tipo::create($validated);

        // Redirigir a la lista de tipos con un mensaje de éxito
        return redirect()->route('admin.tipos')->with('success', 'Tipo creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tipo $tipo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tipo $tipo)
    {
        return Inertia::render('GestionarPersonas/Tipos/edit', [
            'tipo' => $tipo,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tipo $tipo)
    {
        // Validar los datos del request
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
        ]);

        // Actualizar el tipo
        $tipo->update($validated);

        // Redirigir a la lista de tipos con un mensaje de éxito
        return redirect()->route('admin.tipos')->with('success', 'Tipo actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tipo $tipo)
    {
        // Eliminar el tipo
        $tipo->delete();

        // Redirigir a la lista de tipos con un mensaje de éxito
        return redirect()->route('admin.tipos')->with('success', 'Tipo eliminado exitosamente.');
    }
}

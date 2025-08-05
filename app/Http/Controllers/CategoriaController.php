<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todas las categorías
        $categorias = Categoria::paginate(10);

       return Inertia::render('GestionarLicencias/Categorias/index', [
            'categorias' => $categorias

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return Inertia::render('GestionarLicencias/Categorias/create');
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

        // Crear una nueva categoría
        Categoria::create($validated);

        // Redirigir a la lista de categorías con un mensaje de éxito
        return redirect()->route('admin.categorias')->with('success', 'Categoría creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria)
    {
        return view('GestionarCategorias.Categorias.edit', [
            'categoria' => $categoria,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categoria $categoria)
    {
        // Validar los datos del request
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
        ]);

        // Actualizar la categoría
        $categoria->update($validated);

        // Redirigir a la lista de categorías con un mensaje de éxito
        return redirect()->route('admin.categorias')->with('success', 'Categoría actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        // Eliminar la categoría
        $categoria->delete();

        // Redirigir a la lista de categorías con un mensaje de éxito
        return redirect()->route('admin.categorias')->with('success', 'Categoría eliminada exitosamente.');
    }
}

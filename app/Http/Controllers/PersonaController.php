<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\Tipo;
use App\Models\TipoPersona;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todas las personas
        $personas = Persona::with('tipos')->paginate(10);

        return Inertia::render('GestionarPersonas/Personas/index', [
            'personas' => $personas,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener los tipos disponibles
        $tipos = Tipo::all();
        return Inertia::render('GestionarPersonas/Personas/create', [
            'tipos' => $tipos,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    // Validar los datos del request SIN exigir tipo
    $validated = $request->validate([
        'nombre'    => 'required|string|max:255',
        'cedula'    => 'required|string|max:255',
        'celular'   => 'required|string|max:255',
        'direccion' => 'required|string|max:255',
        'email'     => 'required|email|unique:users,email',
        // 👇 ya no validamos tipo
    ]);

    // Crear la persona sin tipo
    $persona = Persona::create($validated);

if ($request->has('tipo')) {
    TipoPersona::create([
         'tipo_id'    => $request->tipo,
        'persona_id' => $persona->id

    ]);
}



    return redirect()
        ->route('admin.personas')
        ->with('success', 'Persona creada exitosamente sin tipo.');
}


    /**
     * Display the specified resource.
     */
    public function show(Persona $persona)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Persona $persona)
    {
        return Inertia::render('GestionarPersonas/Personas/edit', [
            'persona' => $persona,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Persona $persona)
    {
        // Validar los datos del request
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:personas,email,' . $persona->id,
            'telefono' => 'nullable|string|max:20',
        ]);

        // Actualizar la persona
        $persona->update($validated);

        // Redirigir a la lista de personas con un mensaje de éxito
        return redirect()->route('admin.personas')->with('success', 'Persona actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Persona $persona)
    {
        // Eliminar la persona
        $persona->delete();

        // Redirigir a la lista de personas con un mensaje de éxito
        return redirect()->route('admin.personas')->with('success', 'Persona eliminada exitosamente.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Comision;
use App\Models\Licencia;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ComisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todas las comisiones
   $comisiones = Comision::with('licencia')->paginate(10);

        return Inertia::render('GestionarLicencias/Comisiones/index', [
            'comisiones' => $comisiones,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         $licencias = Licencia::all();

        return Inertia::render('GestionarLicencias/Comisiones/create', [
            'licencias' => $licencias,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos del request
        $validated = $request->validate([
            'licencia_id' => 'required|exists:licencias,id',
            'porcentaje' => 'required|numeric|min:0|max:100',
        ]);

        // Crear una nueva comisión
        Comision::create($validated);

        // Redirigir a la lista de comisiones con un mensaje de éxito
        return redirect()->route('admin.comisiones')->with('success', 'Comisión creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function detalleComisionVendedor()
    {
        

        return Inertia::render('GestionarLicencias/Comisiones/detalleComisionVendedor', [
            // 'detalleComisiones' => $detalleComisiones,
        ]);
    }   

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comision $comision)
    {
        return Inertia::render('GestionarLicencias/Comisiones/edit', [
            'comision' => $comision,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comision $comision)
    {
        // Validar los datos del request
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'porcentaje' => 'required|numeric|min:0|max:100',
        ]);

        // Actualizar la comisión
        $comision->update($validated);

        // Redirigir a la lista de comisiones con un mensaje de éxito
        return redirect()->route('admin.comisiones')->with('success', 'Comisión actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comision $comision)
    {
        // Eliminar la comisión
        $comision->delete();

        // Redirigir a la lista de comisiones con un mensaje de éxito
        return redirect()->route('admin.comisiones')->with('success', 'Comisión eliminada exitosamente.');
    }
}

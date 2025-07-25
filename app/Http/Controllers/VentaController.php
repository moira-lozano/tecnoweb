<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todas las ventas
       $ventas  =Venta::with(['comprador', 'vendedor'])->paginate(10);

        return Inertia::render('GestionarVentas/Ventas/index', [
            'ventas' => $ventas,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('GestionarVentas/Ventas/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos del request
        $validated = $request->validate([
            'vendedor_id' => 'required|exists:personas,id',
            'comprador_id' => 'required|exists:personas,id',
            'montototal' => 'required|numeric|min:0',
            'estado' => 'required|in:0,1',
        ]);

        // Crear una nueva venta
        Venta::create($validated);

        // Redirigir a la lista de ventas con un mensaje de éxito
        return redirect()->route('admin.ventas.index')->with('success', 'Venta creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Venta $venta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Venta $venta)
    {
        return Inertia::render('GestionarVentas/Ventas/edit', [
            'venta' => $venta,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, Venta $venta)
{
    // Validar los datos del request
    $validated = $request->validate([
        'vendedor_id'   => 'required|exists:personas,id',
        'comprador_id'  => 'required|exists:personas,id',
        'montototal'    => 'required|numeric|min:0',
        'estado'        => 'required|in:0,1',
    ]);

    // Actualizar la venta
    $venta->update($validated);

    // Redirigir a la lista de ventas con un mensaje de éxito
    return redirect()->route('admin.ventas.index')->with('success', 'Venta actualizada exitosamente.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Venta $venta)
    {
        // Eliminar la venta
        $venta->delete();

        // Redirigir a la lista de ventas con un mensaje de éxito
        return redirect()->route('admin.ventas.index')->with('success', 'Venta eliminada exitosamente.');
    }
}

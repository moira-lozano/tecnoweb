<?php

namespace App\Http\Controllers;

use App\Models\DetalleComisionVendendor;
use App\Models\Persona;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DetalleComisionVendendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $vendendoresComisiones = DetalleComisionVendendor::with(['detalle_venta', 'vendedor'])
            ->select('detalle_comision_vendendors.*', 'personas.nombre as vendedor_nombre')
            ->join('personas', 'detalle_comision_vendendors.vendedor_id', '=', 'personas.id')
            ->paginate(10);
            //dd($vendendoresComisiones);
        return Inertia::render('GestionarLicencias/DetallesComisionesVendedores/index', [
            'vendendoresComisiones' => $vendendoresComisiones,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(DetalleComisionVendendor $detalleComisionVendendor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DetalleComisionVendendor $detalleComisionVendendor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DetalleComisionVendendor $detalleComisionVendendor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DetalleComisionVendendor $detalleComisionVendendor)
    {
        //
    }
}

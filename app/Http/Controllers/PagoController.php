<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 public function index()
{
    // Traer los pagos con su venta asociada
    $pagos = Pago::with(['venta'])->paginate(10);

    return Inertia::render('GestionarVentas/Pagos/index', [
        'pagos' => $pagos
    ]);
}


    /**
     * Show the form for creating a new resource.
     */
   public function create(){
        $user = null;
        if (Auth::check()) {
            $email = Auth::user()->email;
            $user = User::where('email', $email)->first();
        }
        return Inertia::render('LandingPage/Components/PagoCreate', [
        'user'=>$user
        ]);
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
    public function show(Pago $pago)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pago $pago)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pago $pago)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pago $pago)
    {
        //
    }
}

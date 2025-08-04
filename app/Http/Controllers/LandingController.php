<?php

namespace App\Http\Controllers;

use App\Models\Licencia; // Asumiendo que tienes un modelo Licencia
use Illuminate\Http\Request;
use Inertia\Inertia;

class LandingController extends Controller
{
    public function index()
    {
        // Obtener las licencias disponibles para mostrar en la landing
        // Solo mostrar las que tienen stock > 0 y están activas
        $licencias = Licencia::where('stock', '>', 0)
                            ->where('activo', true) // Si tienes un campo activo
                            ->orderBy('created_at', 'desc')
                            ->limit(12) // Limitar a 12 productos para la landing
                            ->get(['id', 'nombre', 'descripcion', 'precio', 'stock']);

        return Inertia::render('Landing', [
            'licencias' => $licencias
        ]);
    }
}
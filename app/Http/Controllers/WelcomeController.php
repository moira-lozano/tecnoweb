<?php

namespace App\Http\Controllers;

use App\Models\Licencia;
use App\Models\PageView;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function __invoke()
    {
        $pageName = 'Pagina principal';
        $PageView = $this->pageView($pageName);

        $allProductos = Licencia::with(['serie', 'marca'])->get();

        // Verificar si hay un usuario autenticado
        $user = null;
        if (Auth::check()) {
            $email = Auth::user()->email;
            $user = User::where('email', $email)->first();
        }

        return Inertia::render('LandingPage/index', [
            'productos' => $allProductos,
            'pageview' => $PageView,
            'user' => $user,
        ]);
    }


public function search(Request $request)
{
    $pageName = 'Seccion Busqueda';
    $this->pageView($pageName);

    $search = strtolower($request->input('search'));

    $productos = Licencia::query()
        ->when($search, function ($query, $search) {
            $query->whereRaw('LOWER(nombre) LIKE ?', ['%' . $search . '%']);
        })
        ->paginate(6);

    return response()->json(['productos' => $productos]);
}




    public function pageView($pageName)
    {
        // Usamos firstOrCreate para no hacer dos consultas
        $PageView = PageView::firstOrCreate(
            ['page_name' => $pageName],
            ['visits' => 0]
        );

        $PageView->increment('visits');

        // Recargamos para tener el valor actualizado
        $PageView->refresh();

        return $PageView;
    }
}

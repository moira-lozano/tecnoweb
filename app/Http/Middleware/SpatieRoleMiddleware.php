<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpatieRoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Verificar si el usuario tiene alguno de los roles especificados
        if (!$user->hasAnyRole($roles)) {
            // Si no tiene permisos y trata de acceder a admin, redirigir al landing
            if ($request->is('admin/*') || $request->is('dashboard')) {
                return redirect()->route('landing')->with('error', 'No tienes permisos para acceder a esta área.');
            }
        }

        return $next($request);
    }
}
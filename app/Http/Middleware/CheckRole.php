## Middleware personalizado
<?php
//namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // ✅ CARGAR ROLES SI NO ESTÁN CARGADOS
        if (!$user->relationLoaded('roles')) {
            $user->load('roles');
        }
        
        // Verificar si tiene alguno de los roles requeridos
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        \Log::info('🔍 Usuario sin acceso:', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'roles_required' => $roles,
            'user_roles' => $user->roles->pluck('name')->toArray(),
            'requested_url' => $request->url()
        ]);

        return redirect()->route('landing')->with('error', 'No tienes permisos para acceder a esta sección.');
    }
}
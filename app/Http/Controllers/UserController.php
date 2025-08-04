<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserStoreRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Spatie\Permission\Models\Role; // Importar Role de Spatie

class UserController extends Controller
{
    /**
     * Get the permissions for the user.
     *
     * @return array
     */
    public function index()
    {
        // Obtener los últimos usuarios registrados primero
        $users = User::with('roles')
                    ->orderBy('created_at', 'desc') // 👈 Ordenar por fecha de creación descendente
                    ->paginate(10);

        return Inertia::render('GestionarUsuarios/Usuarios/index', [
            'users' => $users
        ]);
    }


    public function create()
    {
        // Obtener roles usando Spatie Permission
        $roles = Role::all();
        
        return Inertia::render('GestionarUsuarios/Usuarios/create', [
            'roles' => $roles,
        ]);
    }

    public function store(UserStoreRequest $request)
    {
        // Validar los datos del request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cedula' => 'required|string|max:255',
            'celular' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,id', 
        ]);

        // Encriptar la contraseña
        $validated['password'] = bcrypt($validated['password']);
        
        // Remover el role del array de datos del usuario
        $roleToAssign = $validated['role'];
        unset($validated['role']);
        
        // Crear el usuario
        $user = User::create($validated);

        // Asignar el rol al usuario usando Spatie Permission
        $user->assignRole($roleToAssign);

        // Redirigir después de la creación del usuario
        return redirect()->route('admin.users')->with('success', 'Usuario creado exitosamente.');
    }

    public function edit(User $user)
    {
        // Obtener roles usando Spatie Permission
        $roles = Role::all();
        
        // Cargar el usuario con sus roles
        $user->load('roles');
        
        return Inertia::render('GestionarUsuarios/Usuarios/edit', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    
    public function update(Request $request, User $user)
    {
        // Validar los datos del request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'cedula' => 'required|string|max:255',
            'celular' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,id', 
        ]);

        // Encriptar la contraseña si es proporcionada
        if (!empty($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        // Guardar el rol para asignarlo después
        $roleToAssign = $validatedData['role'];
        unset($validatedData['role']);

        // Actualizar el usuario
        $user->update($validatedData);

        // Actualizar el rol del usuario usando Spatie Permission
        // Primero remover todos los roles y luego asignar el nuevo
        $user->syncRoles([$roleToAssign]);

        // Redirigir con éxito
        return redirect()->route('admin.users')->with('success', 'Usuario y rol actualizados exitosamente.');
    }

    public function destroy(User $user)
    {
        // Eliminar usuario (Spatie Permission se encarga automáticamente de limpiar las relaciones)
        $user->delete();

        // Redirigir después de eliminar
        return redirect()->route('admin.users')->with('success', 'Usuario eliminado exitosamente.');
    }
}
<?php

namespace Database\Seeders;

use App\Models\RoleUser;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         $role = Role::firstOrCreate(
            ['name' => 'gerente', 'guard_name' => 'web']

        );

           $user = User::where('email', 'moira@gmail.com')->first();

        if (!$user) {
            // Crear el usuario
            $user = User::create([
                'name' => 'moira',
                'cedula' => '9000008',
                'celular' => '69187774',
                'direccion' => 'los lotes',
                'email' => 'moira@gmail.com',
                'password' => Hash::make('admin'),
            ]);

            // Asignar el rol "gerente" al usuario
            $user->assignRole($role);

            $roleUser = RoleUser::create([
                'user_id' => $user->id,
                'role_id' => $role->id,
            ]);
        }
    }
}

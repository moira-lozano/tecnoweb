<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear roles si no existen
        $roles = ['gerente', 'vendedor', 'cliente'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web'
            ]);
        }

        // 2. Crear usuarios y asignarles roles
        // Gerente
        $gerente = User::firstOrCreate(
            ['email' => 'moira@gmail.com'],
            [
                'name' => 'Moira',
                'cedula' => '9000008',
                'celular' => '6914',
                'direccion' => 'Los Lotes',
                'password' => Hash::make('admin'),
                'role_id' => null, // No usar role_id con Spatie
            ]
        );
        $gerente->assignRole('gerente');

        // Vendedor
        $vendedor = User::firstOrCreate(
            ['email' => 'vendedor@gmail.com'],
            [
                'name' => 'Vendedor Uno',
                'cedula' => '12345678',
                'celular' => '7000000',
                'direccion' => 'Mercado Central',
                'password' => Hash::make('vendedor123'),
                'role_id' => null, // No usar role_id con Spatie
            ]
        );
        $vendedor->assignRole('vendedor');

        // Cliente
        $cliente = User::firstOrCreate(
            ['email' => 'cliente@gmail.com'],
            [
                'name' => 'Cliente Final',
                'cedula' => '87654321',
                'celular' => '7999999',
                'direccion' => 'Av. Principal',
                'password' => Hash::make('cliente123'),
                'role_id' => null, // No usar role_id con Spatie
            ]
        );
        $cliente->assignRole('cliente');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'cedula',
        'celular',
        'direccion',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Método helper para obtener el primer rol
    public function getPrimaryRoleAttribute()
    {
        return $this->roles->first();
    }

    // Método helper para verificar si es admin (gerente o vendedor)
    public function isAdmin()
    {
        return $this->hasAnyRole(['gerente', 'vendedor']);
    }

    // Método helper para verificar si es cliente
    public function isClient()
    {
        return $this->hasRole('cliente');
    }
}
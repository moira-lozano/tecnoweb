<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    protected $guarded = [];

    // Relación uno a muchos con Licencia
    public function licencias()
    {
        return $this->hasMany(Licencia::class);
    }

    // Relación uno a muchos con DetalleComisionVendendor
    public function detalleComisionVendendors()
    {
        return $this->hasMany(DetalleComisionVendendor::class);
    }
}

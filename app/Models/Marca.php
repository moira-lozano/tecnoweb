<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    // Relación uno a muchos
    public function licencias()
    {
        return $this->hasMany(Licencia::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
   use HasFactory;

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

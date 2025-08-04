<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipo extends Model
{
   
    protected $fillable = [
        'nombre',
        'descripcion',
    ];
    // Relación uno a muchos con Personas
    public function personas()
    {
        return $this->belongsToMany(Persona::class, 'tipo_personas', 'tipo_id', 'persona_id');
    }


}

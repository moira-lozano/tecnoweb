<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoPersona extends Model
{
  protected $fillable = [
        'persona_id',
        'tipo_id',
    ];

    // Relación con Persona
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    // Relación con Tipo
    public function tipo()
    {
        return $this->belongsTo(Tipo::class, 'tipo_id');
    }


}

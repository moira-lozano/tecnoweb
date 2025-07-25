<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $fillable = [
        'nombre',
        'cedula',
        'email',
        'celular',
        'direccion',
          ];  
    // Relación uno a muchos con Licencia
    public function licencias()
    {
        return $this->hasMany(Licencia::class);
    }

     public function ventas()
     {
         return $this->hasMany(Venta::class);
     }

 public function tipos()
{
    return $this->belongsToMany(Tipo::class, 'tipo_personas', 'persona_id', 'tipo_id');
}

    // Relación uno a muchos con DetalleComisionVendendor
    public function detalleComisionVendendors()
    {
        return $this->hasMany(DetalleComisionVendendor::class, 'vendedor_id') ;
    }
}

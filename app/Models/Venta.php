<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = [
        'comprador_id',
        'vendedor_id',
        'montototal',
        'estado',
    ];

    public function comprador()
    {
        return $this->belongsTo(Persona::class, 'comprador_id');
    }

    public function vendedor()
    {
        return $this->belongsTo(Persona::class, 'vendedor_id');
    }

}

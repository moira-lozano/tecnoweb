<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
      protected $fillable = [
        'venta_id',
        'licencia_id',
        'cantidad',
        'total',
    ];

    public function venta(){
        return $this->belongsTo(Venta::class);
    }

    public function licencia(){
        return $this->belongsTo(Licencia::class);
    }
}

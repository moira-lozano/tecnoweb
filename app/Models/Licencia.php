<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Licencia extends Model
{

      protected $fillable = [
        'nombre',
        'serie_id',
        'marca_id',
        'precio',
        'precio_mayorista',
        'precio_renovacion',
        'compartida',
        'trasladable',
        'caducable',
        'formateable',
        'compra_asistida',
        'cantidad',
        'categoria_id',
        'cantidad_disponible',
    ];

     public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function serie()
    {
        return $this->belongsTo(Serie::class);
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
    public function detalleVentas()
    {
        return $this->hasMany(DetalleVenta::class);
    }

        public function comisiones()
    {
        return $this->hasMany(Comision::class);
    }
}

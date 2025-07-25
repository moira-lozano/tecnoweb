<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleComisionVendendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'detalle_venta_id',
        'vendedor_id',
        'monto_comision'
    ];

    public function detalle_venta()
    {
        return $this->belongsTo(DetalleVenta::class);
    }

    public function vendedor()
    {
        return $this->belongsTo(Persona::class, 'vendedor_id');
    }
}

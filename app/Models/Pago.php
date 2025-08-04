<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
     use HasFactory;
  protected $fillable = ['venta_id','fechapago','metodopago','estado'];


// App\Models\Pago.php
public function venta()
{
    return $this->belongsTo(Venta::class);
}


}

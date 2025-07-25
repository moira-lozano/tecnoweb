<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comision extends Model
{
    use HasFactory;
   protected $fillable = [
        'licencia_id',
        'porcentaje'           // <- aquí agregas serie_id

    ];

   public function licencia()
    {
        return $this->belongsTo(Licencia::class);
    }


}

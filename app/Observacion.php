<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Observacion extends Model
{
    protected $table = 'observaciones';
    protected $fillable = [
        'id', 
        'detalle_venta_id',
        'descripcion',
        'estado',
     ];
     public function venta()
     {
         return $this->belongsTo('App\DetalleVenta');
     }
}

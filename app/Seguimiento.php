<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seguimiento extends Model
{
    protected $fillable =[
        'id_detalle_venta',
        'fecha_entrega_proveedor',
        'fecha_entrega_almacen',
        'fecha_entrega_inst_softw',
        'fecha_entrega',
        'fecha_entrega_real'
    ];

    protected $casts = [
        'fecha_entrega_proveedor' => 'datetime:d.m.Y g a',
        'fecha_entrega_almacen' => 'datetime:d.m.Y g a',
        'fecha_entrega_inst_softw' => 'datetime:d.m.Y g a',
        'fecha_entrega' => 'datetime:d.m.Y',
        'fecha_entrega_real' => 'datetime:d.m.Y'
    ];

    public function detalle_venta(){
        return $this->belongsTo('App\DetalleVente');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Solicitudcompra extends Model
{
    protected $fillable =[
        'cantidad_solicitada',
        'idarticulo'
    ];
    
    public function articulo(){
        return $this->belongsTo('App\Articulo');
    }
}

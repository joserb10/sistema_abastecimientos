<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Requerimiento extends Model
{
    protected $fillable = ['requerimiento','descripcion'];

    public function venta()
    {
        return $this->hasOne('App\Venta');
    }
}

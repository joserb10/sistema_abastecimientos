<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'id', 'idpersona'
    ];

    public $timestamps = false;

    public function persona()
    {
        return $this->belongsTo('App\Persona');
    }
}

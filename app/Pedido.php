<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $primaryKey = 'pedidoid';
    protected $fillable = ['clienteid','descripcion'];

    public function cliente(){
        return $this->$this->belongsTo('App\Clientes');
    }
}

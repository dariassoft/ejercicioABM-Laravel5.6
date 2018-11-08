<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    protected $primaryKey = 'clienteid';
    protected $fillable = ['name'];

    public function pedidos(){
        return $this->hasMany('App\Pedido');
    }
}

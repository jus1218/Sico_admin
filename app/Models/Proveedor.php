<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;
    protected $table = 'Proveedores';
    protected $fillable = ['nombre','descripcion','telefono'];

    public function producto(){
        return $this->hasMany('App\Models\Producto');
    }
}

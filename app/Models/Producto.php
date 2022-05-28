<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    protected $table = 'Productos';
    protected $fillable = ['proveedor','nombre','precio'];


    public function proveedor(){
        return $this->belongsTo('App\Models\Proveedor','proveedor');
    }

    public function facturaProveedor(){
        return $this->hasMany('App\Models\FacturaProveedor');
    }
}

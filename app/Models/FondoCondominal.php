<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FondoCondominal extends Model
{
    use HasFactory;
    protected $table = 'FondoCondominal';
    protected $fillable = ['monto'];

    public function detalleCuota(){
        return $this->hasMany('App\Models\DetalleCuota');
    }
    public function detallePago(){
        return $this->hasMany('App\Models\DetallePago');
    }
    public function facturaProveedor(){
        return $this->hasMany('App\Models\FacturaProveedor');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'Pagos';
    protected $fillable = ['colaborador','monto','descripcion','fecha'];

    public function colaborador(){// Pago pertenece
        return $this->belongsTo('App\Models\Colaborador','colaborador');
    }
    public function detallePago(){//Pago aparece
        return $this->hasMany('App\Models\DetallePago');
    }
}

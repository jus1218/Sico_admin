<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuota extends Model
{
    use HasFactory;
    protected $table = 'Cuotas';
    protected $fillable = ['periodo','monto','condomino'];

    public function condomino(){// Cuota pertenece
        return $this->belongsTo('App\Models\Condomino','condomino');
    }
    public function detalleCuota(){//Cuota aparece
        return $this->hasMany('App\Models\DetalleCuota');
    }
}

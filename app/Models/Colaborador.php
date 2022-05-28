<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colaborador extends Model
{
    use HasFactory;
    protected $table = 'Colaboradores';
    protected $fillable = ['nombre','apellidos','fecNacimiento','estado','correo'];

    public function pago(){
        return $this->hasMany('App\Models\Pago');
    }
}

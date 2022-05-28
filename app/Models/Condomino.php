<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condomino extends Model
{
    use HasFactory;
    protected $table = 'Condominos';
    protected $fillable =['propietario','numFilial','usuario'];

  

    
    public function usuario(){
        return $this->belongsTo('App\Models\User','usuario');
    }

    public function cuota(){
        return $this->hasMany('App\Models\Cuota');
    }
}

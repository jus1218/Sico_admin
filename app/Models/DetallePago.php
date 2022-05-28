<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePago extends Model
{
    use HasFactory;
    protected $table = 'DetallePagos';
    protected $fillable = ['pago','fondoCondominal','total'];

    public function pago(){
        return $this->belongsTo('App\Models\Pago','pago');
    }
    public function fondoCondominal(){
        return $this->belongsTo('App\Models\FondoCondominal','fondoCondominal');
    }    
}

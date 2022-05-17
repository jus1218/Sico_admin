<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleCuota extends Model
{
    use HasFactory;
    protected $table = 'DetalleCuotas';
    protected $fillable = ['cuota','fondoCondominal','monto'];

    public function cuota(){
        return $this->belongsTo('App\Models\Cuota','cuota');
    }
    public function fondoCondominal(){
        return $this->belongsTo('App\Models\FondoCondominal','fondoCondominal');
    }
}

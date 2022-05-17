<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturaProveedor extends Model
{
    use HasFactory;

    protected $table = 'FacturaProveedores';
    protected $fillable =['producto','fondoCondominal','precioUni','cantidad','total'];

    public function producto(){
        return $this->belongsTo('App\Models\Producto','producto');
    }
    public function fondoCondominal(){
        return $this->belongsTo('App\Models\FondoCondominal','fondoCondominal');
    }
}

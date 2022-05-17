<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\FondoCondominal;

class FondoController extends Controller
{
    //
    public function __construct(){

    }

        //index -> Devolver todos los elementos << GET >>
    public function index(){
        $data=FondoCondominal::all();//Devuelve todos los obj
        $response = array(
            'status'=>'sucess',
            'code' => 200,
            'data' => $data
        );
        return response()->json($response,200);//devolvemos el arreglo y el code 200(Consulta exitosa)
    }


    //store -> agrega o guarda un elemento << POST >>
    public function store(Request $request){
        $json = $request->input('json',null);//obj json que viene de la vista(frontend) o nulo
        $data = json_decode($json,true);
        if (!empty($data)) {
            $data = array_map('trim',$data);//trin: quitar cualquier campo vacio que viene en ese arreglo
            //alpha: que solo sea letras
            $rules=[
                'tipoTransaccion'=> 'required',
                'monto'=> 'required',
            ];
            
            $validate =\validator($data,$rules);
            
            if ($validate->fails()) {//fails: envia booleano
                $response = array(
                    'status'=>'error',
                    'code'=>406,
                    'menssage'=>'Los datos enviados son incorrectos',
                    'errors'=>$validate->errors()
                );
            }else {    
                
                $fondo = new FondoCondominal();
                $fondo->tipoTransaccion = $data['tipoTransaccion'];
                $fondo->monto = $data['monto'];
                $fondo->save(); //Guarda en la BD
                

                //var_dump($cuota->id);

                $response = array(
                    'status'=>'success',
                    'code'=>201,
                    'menssage'=>'Datos almacenados satisfactoriamente'
                    
                );
            }
        }else {
            $response = array(
                'status'=>'error',
                'code'=>400,
                'menssage'=>'Faltan parametros'
            );
        }

        return response()->json($response,$response['code']);// retornamos el obj respuesta y el codigos = retorna 2 cosas la funcion

    }

      //show -> Devuelve un elemento por su id << GET >>
    public function show($id){
        $data = FondoCondominal::find($id);
        // si viene vacio no se encontro
        if (is_object($data)) {
            $response=array(
                'status'=> 'success',
                'code'=> 200,
                'data' => $data
            );
        }else {
            $response = array(
                'status'=> 'error',
                'code'=> 404,
                'message' => 'Recurso no encontrado'
            );
        }
        return response()->json($response,$response['code']);
    }


      //update -> modifica un elemento << PUT >>
    public function update(Request $request){
      
        $json = $request->input('json',null);
        $data = json_decode($json,true);
        //error al solucionar
        var_dump($data);

        $data = array_map('trim',$data);
        $rules=[
            'monto'=> 'required'
        ];
        $valid = \validator($data,$rules);
        if ($valid->fails()) {
            var_dump('ENTRO');
            $response = array(
                'status'=>'error',
                'code'=>406,
                'data'=>'Datos enviados no cumplen con las reglas establecidas',
                'errors'=>$valid->errors()
            );
        }else {
            
            $id = $data['id'];//Busqueda por id
            unset($data['created_at']);//ver escritura en los otros controllers
            $fondo = FondoCondominal::find($id);
            $data['monto'] += $fondo->monto;

            $updated = FondoCondominal::where('id',$id)->update($data);
            if ($updated>0) {
                $response = array(
                    'status'=>'success',
                    'code'=>200,
                    'data'=>'Datos actualizados satisfactoriamente'
                );
            }else {
                $response = array(
                    'status'=>'error',
                    'code'=>400,
                    'data'=>'No se pudo actualizar el elemento,puede ser que no exista'
                );
            }
        }
        return response()->json($response,$response['code']);
    }

        //destroy -> Elimina un elemento << DELETE >>
    public function destroy($id){
        if (isset($id)) {//si la variable esta creada
            $deleted = FondoCondominal::where('id',$id)->delete();
            if ($deleted) {
                $response = array(
                    'status'=>'success',
                    'code'=>200,
                    'menssage'=>'Eliminado correctamente'
                );
            }else {
                $response = array(
                    'status'=>'error',
                    'code'=>400,
                    'menssage'=>'Problemas al eliminar, puede que el recurso no exista'
                );
            }
        }else {
            $response = array(
                'status'=>'error',
                'code'=>400,
                'menssage'=>'falta el identificador del recurso'
            );
        }
        return response()->json($response,$response['code']);
    }
}

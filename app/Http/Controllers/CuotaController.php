<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//
use Illuminate\Http\Response;
use App\Models\Cuota;
use App\Models\Condomino;
//use App\Http\Controllers\DetalleCuotaController;
//use App\Models\DetalleCuota;

class CuotaController extends Controller
{
    //

 
    public function __construct(){


    }


    //index -> Devolver todos los elementos << GET >>
    public function index(){
       // $data = Cuota::with('condomino')->get();//Es mejor que el all() porque te trae las relaciones
       $data = Cuota::all()->load('condomino');
        $response = array(
            'status'=>'success',
            'code' => 200,
            'data' => $data
        );

        if (!count($data)) {//Verifica si el array viene vacio
            $response = array(
                'status'=>'error',
                'code' => 400,
                'data' => "Recursos no encontrados"
            );
        }

        return response()->json($response,$response['code']);//devolvemos el arreglo y el code 200(Consulta exitosa)
    }

    //store -> agrega o guarda un elemento << POST >>
    public function store(Request $request){
        $json = $request->input('json',null);//obj json que viene de la vista(frontend) o nulo
        $data = json_decode($json,true);
        if (!empty($data)) {
            $data = array_map('trim',$data);//trim: quitar cualquier campo vacio que viene en ese arreglo
            //alpha: que solo sea letras
            
            $validate =\validator($data,[
                'periodo'=> 'required|date',
                'monto'=> 'required',
                'condomino'=> 'required',
            ]);
            
            if ($validate->fails()) {//fails: envia booleano
                $response = array(
                    'status'=>'error',
                    'code'=>406,
                    'menssage'=>'Los datos enviados son incorrectos',
                    'errors'=>$validate->errors()
                );
            }else {    

                try {
                    $cuota = Cuota::create($data);
                    $response = array(
                    'status'=>'success',
                    'code'=>201,
                    'menssage'=>'Datos almacenados satisfactoriamente',
                    'ObjId' => $cuota->id
                );
                } catch (\Throwable $th) {
                    $response = array(
                        'status'=>'error',
                        'code'=>406,
                        'menssage'=>'Condomino no registrado'
                    );
                }
                
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
        
        // $data = Cuota::find($id);
        //$data->condomino = Condomino::find($data->condomino);// cambia id por el arreglo relacionado a ese id

        //$data = Cuota::with('condomino')->get()->find($id);
        //$data = Cuota::all()->load('condomino');
        $data = Cuota::find($id);

        if (is_object($data)) {

            $data->load('condomino');
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
        //var_dump($data);

        $data = array_map('trim',$data);
        $rules=[
            'periodo'=> 'required|date',
            'monto'=> 'required'
            //'condomino'=> 'required',
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
            
            $id = $data['id'];//Busqueda por email
            unset($data['condomino']);//ver esto
            unset($data['id']);
            unset($data['created_at']);//ver escritura en los otros controllers
            
            $updated = Cuota::where('id',$id)->update($data);
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
            $deleted = Cuota::where('id',$id)->delete();
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

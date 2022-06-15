<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Proveedor;

class ProveedorController extends Controller
{
    //


    public function __construct(){

    }

    //index -> Devolver todos los elementos << GET >>
    public function index(){
        $data = Proveedor::all();//Es mejor que el all() porque te trae las relaciones

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

        //show -> Devuelve un elemento por su id << GET >>
    public function show($id){
        $user = Proveedor::find($id);
        if (is_object($user)) {
            $response = array(
                'status'=>'success',
                 'code'=>200,
                 'data'=>$user
            );
        }else{
            $response = array(
                'status'=>'error',
                'code'=>404,
                'data'=>'Proveedor no encontrado'
            );
        }
        return response()->json($response,$response['code']);
    }

    //store -> agrega o guarda un elemento << POST >>
    public function store(Request $request){
        $json = $request->input('json',null);
           
        $data = json_decode($json,true);
        //var_dump($data);
        $data = array_map('trim',$data);
        $rules = [
            'nombre'=>'required|alpha',
            'descripcion' => 'required',
            'telefono' => 'required'
        ];
        $valid = \validator($data,$rules);
        if ($valid->fails()) {
            $response = array(
                'status'=>'error',
                'code'=>406,
                'data'=>'Datos enviados no cumplen con las reglas establecidas',
                'errors'=>$valid->errors()
            );
        }else {
                
            $user = new Proveedor();
               
            $user->nombre = $data['nombre'];
            $user->descripcion = $data['descripcion'];
            $user->telefono = $data['telefono'];

            $user->save();//AQUI ESTA EL ERROR
                
            $response = array(
                'status'=>'success',
                'code'=>200,
                'data'=>'Datos almacenados satisfactoriamente'        
            );
    
        }
        return response()->json($response,$response['code']);
    }

    //update -> modifica un elemento << PUT >>
    public function update(Request $request){
        var_dump('ENTRO');
        $json = $request->input('json',null);
        $data = json_decode($json,true);
        //error al solucionar
        //var_dump($data);
        $data = array_map('trim',$data);
        $rules = [
            'nombre'=>'required|alpha',
            'descripcion' => 'required',
            'telefono' => 'required'
        ];
        $valid = \validator($data,$rules);
        if ($valid->fails()) {
            
            $response = array(
                'status'=>'error',
                'code'=>406,
                'data'=>'Datos enviados no cumplen con las reglas establecidas',
                'errors'=>$valid->errors()
            );
        }else {
            
            $id = $data['id'];//Busqueda por email
            unset($data['id']);
            unset($data['created_at']);//ver escritura en los otros controllers

            $updated = Proveedor::where('id',$id)->update($data);
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
        if(isset($id)){
            $deleted = Proveedor::where('id',$id)->delete();
            if($deleted){
                $response = array(
                    'status'=>'success',
                    'code'=>200,
                    'data'=>'Proveedor eliminado correctamente'
                );
            }else {
                $response = array(
                    'status'=>'error',
                    'code'=>400,
                    'data'=>'No se pudo eliminar el elemento,puede ser que no exista'
                );
            }

        }else {
            $response = array(
                'status'=>'error',
                'code'=>400,
                'data'=>'Falta el id del recurso'
            );
        }
        return response()->json($response,$response['code']);
    }


}

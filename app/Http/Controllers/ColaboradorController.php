<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Colaborador;

class ColaboradorController extends Controller
{
    //
    public function __construct(){

    }
     //index -> Devolver todos los elementos << GET >>
     public function index(){
        var_dump("Mostrar todo");
        $data = Colaborador::all();//Es mejor que el all() porque te trae las relaciones

        $response = array(
            'status'=>'sucess',
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
        return response()->json($response,200);//devolvemos el arreglo y el code 200(Consulta exitosa)
    }
      //show -> Devuelve un elemento por su id << GET >>
    public function show($id){
        $proveedor = Colaborador::find($id);
        if (is_object($proveedor)) {
            $response = array(
                'status'=>'success',
                 'code'=>200,
                 'data'=>$proveedor
            );
        }else{
            $response = array(
                'status'=>'error',
                'code'=>404,
                'data'=>'Colaborador no encontrado'
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
            'apellidos' => 'required',
            'fecNacimiento' => 'required',
            'estado' => 'required'
            //Correo no es requerido
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
                
            $proveedor = new Colaborador();
               
            $proveedor->nombre = $data['nombre'];
            $proveedor->apellidos = $data['apellidos'];
            $proveedor->fecNacimiento = $data['fecNacimiento'];
            $proveedor->estado = $data['estado'];
            $proveedor->correo = $data['correo'];
            $proveedor->save();//AQUI ESTA EL ERROR
                
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
        $rules = $rules = [
            'nombre'=>'required|alpha',
            'apellidos' => 'required',
            'fecNacimiento' => 'required',
            'estado' => 'required'
            //Correo no es requerido
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

            $updated = Colaborador::where('id',$id)->update($data);
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
            $deleted = Colaborador::where('id',$id)->delete();
            if($deleted){
                $response = array(
                    'status'=>'success',
                    'code'=>200,
                    'data'=>'Colaborador eliminado correctamente'
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

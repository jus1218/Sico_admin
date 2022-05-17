<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Producto;

class ProductoController extends Controller
{
    //

    public function __construct(){

    }

    //index -> Devolver todos los elementos << GET >>
    public function index(){
        $data=Producto::all();//Devuelve todos los obj
        $response = array(
            'status'=>'sucess',
            'code' => 200,
            'data' => $data
        );
        return response()->json($response,200);//devolvemos el arreglo y el code 200(Consulta exitosa)
    }

        //show -> Devuelve un elemento por su id << GET >>
    public function show($id){
    
        $data = Producto::find($id);
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



     //store -> agrega o guarda un elemento << POST >>
    public function store(Request $request){
        $json = $request->input('json',null);//obj json que viene de la vista(frontend) o nulo
        $data = json_decode($json,true);
        if (!empty($data)) {
            $data = array_map('trim',$data);//trin: quitar cualquier campo vacio que viene en ese arreglo
            //alpha: que solo sea letras
            $rules=[
                'proveedor'=> 'required',
                'nombre'=> 'required',
                'precio'=> 'required',
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

                $cuota = Producto::create($data);

                //var_dump($cuota->id);

                $response = array(
                    'status'=>'success',
                    'code'=>201,
                    'menssage'=>'Datos almacenados satisfactoriamente',
                    'ObjId' => $cuota->id
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

     //update -> modifica un elemento << PUT >>
     public function update(Request $request){
        
        $json = $request->input('json',null);
        $data = json_decode($json,true);//viene decodificado. true hace que convierta el arreglo a data
        //var_dump($data);
        if (!empty($data)) {
            $data = array_map('trim',$data);
            //VER SI ID VA IGUAL O CON OTRO NAME--------------------------------------------------------------------------------------->>>>
            $rules=[
                'proveedor'=> 'required',
                'nombre'=> 'required',
                'precio'=> 'required',
            ];
            
            $validate = \validator($data,$rules);//obj validator del sistema
             var_dump("modificar");
            if ($validate->fails()) {
                
                $response = array(
                    'status'=>'error',
                    'code'=>406,
                    'menssage'=>'Los datos enviados son incorrectos',
                    'errors'=>$validate->errors()
                );
            }else {
             
                $id = $data['id'];
                unset($data['id']);//quitar el elemento id del data
                unset($data['created_at']);//este tambien, esto se da porque son datos que no queremos modificar
                //VER ID SI ES ASI O CAMBIAR NAME ---------------------------------------------------------------------------------------->>
                $updated = Producto::where('id',$id)->update($data); //hace una busqueda comparando los dos parametros id y $id
                if ($updated>0) {
                    $response = array(
                        'status'=>'success',
                        'code'=>200,
                        'menssage'=>'Datos actualizados exitosamente'
                    );
                }else {
                    $response = array(
                        'status'=>'error',
                        'code'=>400,
                        'menssage'=>'error al actualizar los datos'
                    );
                }
            }
        }else {

            $response = array(
                'status'=>'error',
                'code'=>400,
                'menssage'=>'faltan parametros'
            );
        }
        return response()->json($response,$response['code']);
    }

     //destroy -> Elimina un elemento << DELETE >>
     public function destroy($id){
        if (isset($id)) {//si la variable esta creada
            //VER ID SI ES ASI O CAMBIAR NAME ------------------------------------------------------->>
            $deleted = Producto::where('id',$id)->delete();
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

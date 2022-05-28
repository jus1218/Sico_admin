<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\DetallePago;

class DetallePagoController extends Controller
{
    //


    public function __construct(){

    }

     //index -> Devolver todos los elementos << GET >>
    public function index(){
        var_dump("Mostrar todo");
        //$data=DetallePago::with(['pago','fondoCondominal'])->get();//Devuelve todos los obj
        $data = DetallePago::all()->load('pago','fondoCondominal');
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
        //$data=DetallePago::with(['pago','fondoCondominal'])->get()->find($id);
        $data = DetallePago::find($id);


        if (is_object($data)) {

            $data = $data->load('pago','fondoCondominal');
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
                'pago'=> 'required',
                'fondoCondominal'=> 'required',
                'total'=>'required'
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
                
                $detPago = new DetallePago();
            
                $detPago->pago = $data['pago'];//AQUI ESTA EL GRAN PROBLEMA
                $detPago->fondoCondominal = $data['fondoCondominal'];
                $detPago->total = $data['total'];
                

                try {
                    $detPago->save(); //Guarda en la BD
                    $response = array(
                    'status'=>'success',
                    'code'=>201,
                    'menssage'=>'Datos almacenados satisfactoriamente'
                    );
                } catch (\Throwable $th) {
                    $response = array(
                        'status'=>'error',
                        'code'=>406,
                        'menssage'=>'pago o fondo no registrados'
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

        //update -> modifica un elemento << PUT >>
    public function update(Request $request){
        
        $json = $request->input('json',null);
        $data = json_decode($json,true);//viene decodificado. true hace que convierta el arreglo a data
        //var_dump($data);
        if (!empty($data)) {
            $data = array_map('trim',$data);
            $rules = [
                'total'=> 'required'
            ];
            
            $validate = \validator($data,$rules);//obj validator del sistema
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
                $updated = DetallePago::where('id',$id)->update($data); //hace una busqueda comparando los dos parametros id y $id
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
            $deleted = DetallePago::where('id',$id)->delete();
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

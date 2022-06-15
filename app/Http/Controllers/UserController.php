<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//
use Illuminate\Http\Response;
use App\Models\User;
use App\Helpers\JwtAuth; 

class UserController extends Controller
{
    //
    public function __construct(){//except index y otro
       // $this->middleware('api.auth',['except'=>['idex','show']]);
    }
    public function __invoke(){//Method of security

    }
    //index -> Devolver todos los elementos << GET >>
    public function index(){
        $data = User::all();
        $response = array(
            'status'=>'success',
            'code'=>200,
            'data'=>$data
        );

        if (!count($data)) {//Verifica si el array viene vacio
            $response = array(
                'status'=>'error',
                'code' => 400,
                'data' => "Recursos no encontrados"
            );
        }
        return response()->json($response,$response['code']);
    }
    //show -> Devuelve un elemento por su id << GET >>
    public function show($id){
        $user = User::find($id);
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
                'data'=>'Usuario no encontrado'
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
            'email' => 'required|email|unique:Usuarios',
            'contrasena' => 'required',
            'tipo'=> 'required',
            'estado'=>'required'  
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
            
            $user = new User();
           
          
            $user->nombre = $data['nombre'];
            $user->email = $data['email'];
            $user->contrasena = hash('sha256',$data['contrasena']); 
            $user->tipo = $data['tipo'];
            $user->estado = $data['estado'];
            //var_dump('Hola mundo');
            //var_dump($user);
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
      
        $json = $request->input('json',null);
        $data = json_decode($json,true);
        //error al solucionar
        //var_dump($data);
        $data = array_map('trim',$data);
        $rules = [
            'nombre'=>'required|alpha',
            'email' => 'required|email',
            'contrasena' => 'required',
            'tipo'=> 'required',
            'estado'=>'required'  
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
            
            $email = $data['email'];//Busqueda por email
            unset($data['email']);
            unset($data['id']);
            unset($data['created_at']);//ver escritura en los otros controllers
            unset($data['remember_token']);
            $updated = User::where('email',$email)->update($data);
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
            $deleted = User::where('id',$id)->delete();
            if($deleted){
                $response = array(
                    'status'=>'success',
                    'code'=>200,
                    'data'=>'Usuario eliminado correctamente'
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


     /**
     * Funciona con el method POST
     * retorna token
    */
    public function login(Request $request){
        $jwtAuth = new JwtAuth();
        $json = $request->input('json',null);
        $data = json_decode($json,true);
        $data = array_map('trim',$data);
        $rules = [
        'email'=> 'required|email',
        'contrasena'=> 'required'];
        $valid = \validator($data,$rules);
        if ($valid->fails()) {
            $response = array(
                'status'=>'error',
                'code'=>406,
                'message'=>'Los datos enviados son incorrectos',
                'errors'=>$valid->errors()
            );
            return response()->json($response,406);
        }else {
            $response = $jwtAuth->getToken($data['email'],$data['contrasena']);//1:35:07
            return response()->json($response);
        }
        
    }
    /**
     * Funciona con el method POST
     * retorna json(datos del usuario)
    */
    public function getIdentity(Request $request){
        $jwtAuth = new JwtAuth();
        $token = $request->header('token');
        if (isset($token)) {
            $response = $jwtAuth->checkToken($token,true);
        }else {
            $response = array(
                'status'=>'error',
                'code'=>406,
                'message'=>'Token no encontrado'
            );
        }
        return response()->json($response);
    }

    // METODOS PARA LAS IMAGENES

    public function uploadImage(Request $request){
        $image=$request->file('file0');
        $valid=\Validator::make($request->all(),[
            'file0'=>'required|image|mimes:jpg,png'
        ]);
        if(!$image||$valid->fails()){
            $response=array(
                'status'=>'error',
                'code'=>406,
                'message'=>'Error al subir el archivo',
                'errors'=>$valid->errors()
            );
        }else{
            $filename=time().$image->getClientOriginalName();
            \Storage::disk('users')->put($filename,\File::get($image));
            $response=array(
                'status'=>'success',
                'code'=>200,
                'message'=>'Imagen guardada correctamente',
                'image_name'=>$filename
            );
        }
        return response()->json($response,$response['code']);
    }
    public function getImage($filename){
        $exist=\Storage::disk('users')->exists($filename);
        if($exist){
            $file=\Storage::disk('users')->get($filename);
            return new Response($file,200);
        }else{
            $response=array(
                'status'=>'error',
                'code'=>404,
                'message'=>'Imagen no existe'
            );
            return response()->json($response,404);
        }
    }


}

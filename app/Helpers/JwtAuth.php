<?php
namespace App\Helpers;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;


class JwtAuth{
    private $key;

    function __construct(){
        $this->key='12Sawsfe2saWaaasawSQ';
    }

    /**
     * Este metodo devuelve en caso de que el usuario y contraseña sean correctas
     * o devuelve un array con un mensaje en caso de que la autentificacion sea incorrecta
     */
    public function getToken($email,$contrasena){
        $user = User::where(['email'=>$email,'contrasena'=>hash('sha256',$contrasena)])->first();
        if(is_object($user)){
            $token = array(
                'sub'=>$user->id,
                'email'=>$user->email,
                'nombre'=>$user->nombre,
                'tipo'=>$user->tipo,
                'estado'=>$user->estado,
                'iat'=>time(),//Aqui es el error creo
                'exp'=>time()+(120)
            );
            $data = JWT::encode($token,$this->key,'HS256');

        }else {
            $data = array(
                'status'=>'error',
                'code'=>401,
                'message'=>'Datos de autentificacion incorrectos'
            );
        }
        return $data;
    }

    public function checkToken($jwt,$getIdentity=false){
        $auth=false;
        if(isset($jwt)){
            try{
                
                $decoded=JWT::decode($jwt,new Key($this->key,'HS256'));
               
            }catch(\DomainException $ex){
                $auth=false;
             }catch(\UnexpectedValueException $ex){
                $auth=false;
            }catch(\ExpiredException $ex){
                $auth=false;
            }

            if(!empty($decoded)&&is_object($decoded)&&isset($decoded->sub)){
                $auth=true;
            }
            if($getIdentity&&$auth){
                return $decoded;
            }

        }
        return $auth;
    }
}
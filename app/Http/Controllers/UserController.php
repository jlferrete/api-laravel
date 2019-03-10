<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use App\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function register(Request $request){

        //Recoger post
        $json = $request->input('json', null);
        $params = json_decode($json);

        $email = (!is_null($json) && isset($params->email)) ? $params->email : null;
        $name = (!is_null($json) && isset($params->name)) ? $params->name : null;
        $surname = (!is_null($json) && isset($params->surname)) ? $params->surname : null;
        $role = 'ROLE_USER';
        $password = (!is_null($json) && isset($params->password)) ? $params->password : null;


        if(!is_null($email) && !is_null($password) && !is_null($name)){

            //Crear el usuario
            $user = new User();
            $user->email = $email;
            $user->name = $name;
            $user->surname = $surname;
            $user->role = $role;

            $pwd = hash('sha256', $password);
            $user->password = $pwd;

            //Comprobar usuario duplicado
//            $isset_user = User::where('email', '=', $email)->first();

            $isset_user = User::where('email', "=", $email)->first();

            if($isset_user == null){
                //Guardar el usuario
                $user->save();

                $data = array(
                    'status' => 'success',
                    'code' => '200',
                    'message' => 'Usuario registrado correctamente'
                );


            }else{
                //No guardarlo porque ya existe
                $data = array(
                    'status' => 'error',
                    'code' => '400',
                    'message' => 'Usuario duplicado, no puede registrarse'
                );
            }


        }else{

            $data = array(
                'status' => 'error',
                'code' => '400',
                'message' => 'Usuario no creado'
            );

        }

        return response()->json($data, 200);
    }

    public function login(Request $request){

        $jwtAuth = new JwtAuth();

        //Recibir POST
        $json = $request->input('json', null);
        $params = json_decode($json);

        $email = (!is_null($json) && isset($params->email)) ? $params->email : null;
        $password = (!is_null($json) && isset($params->password)) ? $params->password : null;
        $getToken =  (!is_null($json) && isset($params->gettoken)) ? $params->gettoken : null;

        //Cifrar la password

        $pwd = hash('sha256', $password);

        if(!is_null($email) && !is_null($password) && ($getToken == null || $getToken == 'false')){
            $signup = $jwtAuth->signup($email, $pwd);

        }elseif($getToken != null){

            $signup = $jwtAuth->signup($email, $pwd, $getToken);

        }else{
            $signup = array(
                    'status' => 'error',
                    'message' => 'Envia tus datos por post'
                );
        }

        return response()->json($signup, 200);

    }

}

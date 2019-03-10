<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;

class CarController extends Controller
{
    public function index(Request $request) {

        $hash = $request->header('Authorization', null);

        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if($checkToken){
            echo "Index de CarController AUTENTICADO"; die();
        }else{
            echo "NO AUTENTICADO -> Index de CarController"; die();
        }

    }
}

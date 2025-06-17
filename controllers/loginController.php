<?php

require_once __DIR__ . '/../models/loginModel.php';

class loginController
{
    public function login($user, $pass)
    {
        
        $loginCorrecto = new loginModel();
        $autenticado = $loginCorrecto->verificarCredenciales($user, $pass);

        if ($autenticado != false) {
           return $autenticado;
        }else{
            return "error";
        }
    }

    public function getUsario($usuario){
        if (!empty($usuario)) {
        $model = new loginModel();
        $getUsuariio = $model->getUsuario($usuario);

        if ($getUsuariio != 'error'){
            return $getUsuariio;
        }else{
            return 'error';
        }
       }
    }
}

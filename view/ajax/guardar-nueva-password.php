<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once '../../controllers/usuariosController.php';

$id = $_POST['id'] ?? '';
$pass = $_POST['pass'] ?? '';
$respuesta = ['ok' => false];

if ($id && $pass) {

    if (preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $pass)) {
        $controller = new usuariosController();

        // Ahora enviar a la base de datos SOLO el nombre del archivo
        $updatePass = $controller->updatePass($id, $pass);

        if($updatePass == 'success')
        {
            $respuesta = ['ok' => true];
        }else{
            $respuesta = ['ok' => false];
        }
    }else{
        $respuesta = ['ok' => false];
    }

}

header('Content-Type: application/json');
echo json_encode($respuesta);

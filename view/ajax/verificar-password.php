<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once '../../controllers/loginController.php';

$clave = $_POST['clave'] ?? '';
$user = $_POST['user'] ?? '';
$respuesta = ['ok' => false];

if ($clave && $user) {
    $controller = new loginController();

    // Evitar cualquier salida accidental
    ob_start();
    $verificaPass = $controller->login($user, $clave);
    ob_end_clean();

    if ($verificaPass === 'success') {
        $respuesta['ok'] = true;
    }
}

header('Content-Type: application/json');
echo json_encode($respuesta);

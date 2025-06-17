<?php


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       
    require_once '../controllers/loginController.php';

  

    $usuario = $_POST['usuario'] ?? '';
    $clave = $_POST['password'] ?? '';


    $respuesta = new loginController();
    $validar = $respuesta->login($usuario, $clave);

    if ($validar['success']) {
        $_SESSION['usuario'] = $validar ['usuario'];
        header('Location: ../dashboard.php');
        exit;
    } else {
        $_SESSION['error'] = $respuesta['mensaje'];
        header('Location: ../index.php'); // Redirige y oculta login.php
        exit;
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    exit('Método no permitido');
}

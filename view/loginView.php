<?php
require_once '../routes/rutas.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../assets/images/logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguridad HC</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="<?= CSS_PATH; ?>/styleLogin.css">
</head>

<body>

    <?php
    session_start();

    if (isset($_SESSION['usuario'])) {
        session_unset();
        session_destroy();
        header("Location: ../index.php");
        exit();
    }

    // Verifica si el formulario fue enviado
    if (isset($_POST['ingresar'])) {

        // Obtener y limpiar los datos del formulario
        $usuario = trim($_POST['usuario'] ?? '');
        $clave = trim($_POST['password'] ?? '');

        // Validación de campos vacíos
        if (empty($usuario) || empty($clave)) {
    ?>
            <script>
                swal.fire({
                    title: 'Error',
                    text: 'Por favor, digite el nombre de usuario y contraseña',
                    icon: 'error',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/index.php';
                    }
                })
            </script>
        <?php
            //echo "Por favor digite su nombre de usuario y contraseña";
            return; // Detener la ejecución si hay un error
        }

        // Validación de longitud de usuario y contraseña
        if (strlen($usuario) > 15 || strlen($clave) > 17) {
        ?>
            <script>
                swal.fire({
                    title: 'Lo sentimos',
                    text: 'Los datos digitados exceden el límite establecido.',
                    icon: 'warning',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/index.php';
                    }
                })
            </script>
        <?php
            //echo "Lo sentimos, los datos digitados exceden el límite establecido.";
            return; // Detener la ejecución si hay un error
        }

        // Llamar al controlador para verificar las credenciales
        require_once '../controllers/loginController.php';
        $respuesta = new loginController();
        $validar = $respuesta->login($usuario, $clave);

        // Comprobar si la respuesta es 'success' o si contiene un error
        if ($validar != 'error') {
            $_SESSION['usuario'] = $validar['user_name']; // Guarda el nombre de usuario en la sesión
            $_SESSION['nivel_acceso'] = $validar['user_nivelAcceso']; // Guarda el nivel de acceso del usuario en la sesión
            $_SESSION['nombre'] = $validar['emp_nombre'];
           
            header('Location: ' . BASE_PATH . '/dashboard.php'); // Redirige a la página de dashboard
            exit;
        } else {
        ?>
            <script>
                swal.fire({
                    title: 'Error',
                    text: 'Los datos igresados son incorrectos',
                    icon: 'error',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if(result.isConfirmed){
                        window.location.href = '<?= BASE_PATH ?>/index.php';
                    }
                })
            </script>
    <?php
            // echo "Lo sentimos, los datos ingresados son incorrectos"; // Error si las credenciales son incorrectas
        }
    }
    ?>

    <div class="content">
        <form action="" method="post">
            <h2>
                <img src="<?= IMAGES_PATH ?> /logo.png" alt="Logo" style="width: 50px; height: 50px; margin-right: 8px; vertical-align: middle;"> <br>
                Panel de Control HC
            </h2>

            <div class="input-user">
                <input type="text" name="usuario" required>
                <label><b>Ingrese su nombre de usuario</b></label>
            </div>

            <div class="input-user">
                <input type="password" name="password" required>
                <label><b>Ingrese su contraseña</b></label>
            </div>

            <button type="submit" name="ingresar">Ingresar</button>
        </form>
    </div>
</body>

</html>
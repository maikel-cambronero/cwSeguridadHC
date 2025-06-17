<?php
require_once __DIR__ . '/../core/conexion.php';

class loginModel
{
    private $conn;

    public function __construct()
    {
        $conexion = new Conexion();
        $this->conn = $conexion->getConexion(); // Esto es lo que te faltaba
    }

    public function verificarCredenciales($user, $pass)
    {
        try {
            $ISuser = $this->conn->prepare("CALL sp_obtiene_usuario(:usuario)");
            $ISuser->bindParam(':usuario', $user);
            $ISuser->execute();

            $existe = $ISuser->fetch(PDO::FETCH_ASSOC);
            $ISuser->closeCursor();

            if ($existe) {
                $hash = $existe['user_password'];

                if (password_verify($pass, $hash)) {
                    // Eliminar el password antes de retornar
                    unset($existe['user_password']);
                    return $existe; // Retornar todos los datos del usuario
                }
            }
            return false; // Usuario no encontrado o contraseña incorrecta
        } catch (PDOException $e) {
            $error = $e->getMessage();
            echo "<script>console.error(" . json_encode("Error: $error") . ");</script>";
            return ['error' => 'Excepción: ' . $error];
        }
    }


    public function getUsuario($usuario)
    {
        try {
            $stmt = $this->conn->prepare("CALL sp_obtiene_usuario(:usuario)");
            $stmt->bindParam(':usuario', $usuario);

            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $stmt->closeCursor();

                if (!empty($result)) {
                    return $result; // Retorna los datos del usuario
                } else {
                    return 'error';
                }
            } else {
                return 'error';
            }
        } catch (PDOException $e) {
            return ['error' => 'Excepción: ' . $e->getMessage()];
        }
    }
}

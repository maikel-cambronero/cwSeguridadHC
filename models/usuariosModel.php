<?php
require_once __DIR__ . '/../core/conexion.php';

class usuarioModel
{
    private $conn;

    public function __construct()
    {
        $conexion = new Conexion();
        $this->conn = $conexion->getConexion();
    }

    /**
     * FUNCIONES PARA LEER
     */
    public function get_empleado()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_get_empleado_activo");

            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $stmt->closeCursor();

                if (!empty($result)) {
                    return $result; // Retorna los datos del proveedor
                } else {
                    return 'error'; // Retorna error si no hay datos
                }
            } else {
                return 'error'; // Si la consulta falla
            }
        } catch (PDOException $e) {
            // Puedes agregar más detalles de error para depurar
            return ['error' => 'Excepción: ' . $e->getMessage()];
        }
    }

    public function get_depto()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_get_departamento ");

            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $stmt->closeCursor();

                if (!empty($result)) {
                    return $result; // Retorna los datos del proveedor
                } else {
                    return 'error'; // Retorna error si no hay datos
                }
            } else {
                return 'error'; // Si la consulta falla
            }
        } catch (PDOException $e) {
            // Puedes agregar más detalles de error para depurar
            return ['error' => 'Excepción: ' . $e->getMessage()];
        }
    }

    public function get_rol()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_get_rol");

            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $stmt->closeCursor();

                if (!empty($result)) {
                    return $result; // Retorna los datos del proveedor
                } else {
                    return 'error'; // Retorna error si no hay datos
                }
            } else {
                return 'error'; // Si la consulta falla
            }
        } catch (PDOException $e) {
            // Puedes agregar más detalles de error para depurar
            return ['error' => 'Excepción: ' . $e->getMessage()];
        }
    }

    public function get_empleados()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_empleados_general");

            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $stmt->closeCursor();

                if (!empty($result)) {
                    return $result; // Retorna los datos del proveedor
                } else {
                    return 'error'; // Retorna error si no hay datos
                }
            } else {
                return 'error'; // Si la consulta falla
            }
        } catch (PDOException $e) {
            $error = $e->getMessage();
            echo "<script>console.error(" . json_encode("Error: $error") . ");</script>";
            return ['error' => 'Excepción: ' . $error];
        }
    }

    public function get_usuarios_activos()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_usuarios_activos");

            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $stmt->closeCursor();

                if (!empty($result)) {
                    return $result; // Retorna los datos del proveedor
                } else {
                    return 'error'; // Retorna error si no hay datos
                }
            } else {
                return 'error'; // Si la consulta falla
            }
        } catch (PDOException $e) {
            $error = $e->getMessage();
            echo "<script>console.error(" . json_encode("Error: $error") . ");</script>";
            return ['error' => 'Excepción: ' . $error];
        }
    }

    public function get_nivel_acceso()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_nivel_acceso");

            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $stmt->closeCursor();

                if (!empty($result)) {
                    return $result; // Retorna los datos del proveedor
                } else {
                    return 'error'; // Retorna error si no hay datos
                }
            } else {
                return 'error'; // Si la consulta falla
            }
        } catch (PDOException $e) {
            $error = $e->getMessage();
            echo "<script>console.error(" . json_encode("Error: $error") . ");</script>";
            return ['error' => 'Excepción: ' . $error];
        }
    }

    public function get_usuario_inactivo()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_get_usuario_inactivo");

            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $stmt->closeCursor();

                if (!empty($result)) {
                    return $result; // Retorna los datos del proveedor
                } else {
                    return 'error'; // Retorna error si no hay datos
                }
            } else {
                return 'error'; // Si la consulta falla
            }
        } catch (PDOException $e) {
            $error = $e->getMessage();
            echo "<script>console.error(" . json_encode("Error: $error") . ");</script>";
            return ['error' => 'Excepción: ' . $error];
        }
    }

    public function get_colaborador_inactivo()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_get_colaborador_inactivo");

            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $stmt->closeCursor();

                if (!empty($result)) {
                    return $result; // Retorna los datos del proveedor
                } else {
                    return 'error'; // Retorna error si no hay datos
                }
            } else {
                return 'error'; // Si la consulta falla
            }
        } catch (PDOException $e) {
            $error = $e->getMessage();
            echo "<script>console.error(" . json_encode("Error: $error") . ");</script>";
            return ['error' => 'Excepción: ' . $error];
        }
    }

    public function get_colaborador_despedido()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_get_colaborador_despedido");

            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $stmt->closeCursor();

                if (!empty($result)) {
                    return $result; // Retorna los datos del proveedor
                } else {
                    return 'error'; // Retorna error si no hay datos
                }
            } else {
                return 'error'; // Si la consulta falla
            }
        } catch (PDOException $e) {
            $error = $e->getMessage();
            echo "<script>console.error(" . json_encode("Error: $error") . ");</script>";
            return ['error' => 'Excepción: ' . $error];
        }
    }

    public function get_usuario_general()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_get_usuario_general");

            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $stmt->closeCursor();

                if (!empty($result)) {
                    return $result; // Retorna los datos del proveedor
                } else {
                    return 'error'; // Retorna error si no hay datos
                }
            } else {
                return 'error'; // Si la consulta falla
            }
        } catch (PDOException $e) {
            $error = $e->getMessage();
            echo "<script>console.error(" . json_encode("Error: $error") . ");</script>";
            return ['error' => 'Excepción: ' . $error];
        }
    }

    public function get_empleadoID($id)
    {
        try {
            $stmt = $this->conn->prepare("CALL sp_get_empleadoID(:p_id)");
            $stmt->bindParam(':p_id', $id);

            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $stmt->closeCursor();

                if (!empty($result)) {
                    return $result; // Retorna los datos del proveedor
                } else {
                    return 'error'; // Retorna error si no hay datos
                }
            } else {
                return 'error'; // Si la consulta falla
            }
        } catch (PDOException $e) {
            $error = $e->getMessage();
            echo "<script>console.error(" . json_encode("Error: $error") . ");</script>";
            return ['error' => 'Excepción: ' . $error];
        }
    }


    /**
     * FUNCIONES PARA AGREGAR
     */

    public function addColab($nombre, $apellido, $cedula, $telefono, $correo, $fecha_ingreso, $direccion, $salario, $cuenta, $depto, $rol, $vacaciones, $licencias, $carnet_agente, $carnet_armas, $psicologico, $huellas, $nombreArchivoFinal, $delta, $puesto)
    {
        try {
            $anio = date('Y', strtotime($fecha_ingreso));
            $ced = substr(str_pad($cedula, 4, '0', STR_PAD_LEFT), -4);
            $codigo = "FS-CR-$anio-$ced";

            $obd = 1;

            $stm = $this->conn->prepare('CALL sp_insert_colaborador(:p_nombre, :p_apellidos, :p_cedula, :p_telefono, :p_correo, :p_direccion, :p_salario, :p_fechaIngreso, :p_cuenta, :p_codigo, :p_foto, :p_carnetAgente, :p_carnetArma, :p_testPsicologico, :p_huellas, :p_vacaciones, :p_licencias, :p_obd_id, :p_rol_id, :p_dep_id, :p_delta, :p_puesto)');
            $stm->bindParam(':p_nombre', $nombre);
            $stm->bindParam(':p_apellidos', $apellido);
            $stm->bindParam(':p_cedula', $cedula);
            $stm->bindParam(':p_telefono', $telefono);
            $stm->bindParam(':p_correo', $correo);
            $stm->bindParam(':p_direccion', $direccion);
            $stm->bindParam(':p_salario', $salario);
            $stm->bindParam(':p_fechaIngreso', $fecha_ingreso);
            $stm->bindParam(':p_cuenta', $cuenta);
            $stm->bindParam(':p_codigo', $codigo);
            $stm->bindParam(':p_foto', $nombreArchivoFinal);
            $stm->bindParam(':p_carnetAgente', $carnet_agente);
            $stm->bindParam(':p_carnetArma', $carnet_armas);
            $stm->bindParam(':p_testPsicologico', $psicologico);
            $stm->bindParam(':p_huellas', $huellas);
            $stm->bindParam(':p_vacaciones', $vacaciones);
            $stm->bindParam(':p_licencias', $licencias);
            $stm->bindParam(':p_obd_id', $obd);
            $stm->bindParam(':p_rol_id', $rol);
            $stm->bindParam(':p_dep_id', $depto);
            $stm->bindParam(':p_delta', $delta);
            $stm->bindParam(':p_puesto', $puesto);

            if ($stm->execute()) {
                return "success";
            } else {
                return "error";
            }
        } catch (PDOException $e) {
            $error = $e->getMessage();
            echo "<script>console.error(" . json_encode("Error: $error") . ");</script>";
            return ['error' => 'Excepción: ' . $error];
        }
    }

    public function addUser($cedula, $acceso, $password)
    {
        try {
            $empleado = $this->conn->prepare('CALL sp_get_empleado_cedula(:p_cedula)');
            $empleado->bindParam(':p_cedula', $cedula);

            if ($empleado->execute()) {
                $result = $empleado->fetchAll(PDO::FETCH_ASSOC);
                $empleado->closeCursor();

                if (empty($result)) {
                    return 'error';
                }

                // Generar nombre de usuario
                $username = $this->generarNombreUsuario($result);

                // Hashear la contraseña
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                // Insertar usuario con los datos correctos
                $stm = $this->conn->prepare('CALL sp_insert_usuario(:p_cedula, :p_acceso, :p_username, :p_password)');
                $stm->bindParam(':p_cedula', $cedula);
                $stm->bindParam(':p_acceso', $acceso);
                $stm->bindParam(':p_username', $username);
                $stm->bindParam(':p_password', $passwordHash);

                if ($stm->execute()) {
                    return "success";
                } else {
                    return "error";
                }
            } else {
                return "error";
            }
        } catch (PDOException $e) {
            $error = $e->getMessage();
            echo "<script>console.error(" . json_encode("Error: $error") . ");</script>";
            return ['error' => 'Excepción: ' . $error];
        }
    }


    /**
     * FUNCIONES PARA EDITAR
     */
    public function updateColab($id, $nombre, $apellido, $cedula, $telefono, $correo, $fecha_ingreso, $direccion, $cuenta, $depto, $rol, $vacaciones, $licencias, $carnet_agente, $carnet_armas, $psicologico, $huellas, $nombreArchivoFinal)
    {
        try {
            $anio = date('Y', strtotime($fecha_ingreso));
            $ced = substr(str_pad($cedula, 4, '0', STR_PAD_LEFT), -4);
            $codigo = "FS-CR-$anio-$ced";

            $stm = $this->conn->prepare('CALL sp_update_empleado(:p_id, :p_nombre, :p_apellido, :p_cedula, :p_telefono, :p_correo, :p_fecha_ingreso, :p_direccion, :p_cuenta, :p_depto, :p_rol, :p_vacaciones, :p_licencias, :p_carnet_agente, :p_carnet_arma, :p_psicologico, :p_huellas, :p_foto, :p_codigo)');
            $stm->bindParam(':p_id', $id);
            $stm->bindParam(':p_nombre', $nombre);
            $stm->bindParam(':p_apellido', $apellido);
            $stm->bindParam(':p_cedula', $cedula);
            $stm->bindParam(':p_telefono', $telefono);
            $stm->bindParam(':p_correo', $correo);
            $stm->bindParam(':p_fecha_ingreso', $fecha_ingreso);
            $stm->bindParam(':p_direccion', $direccion);
            $stm->bindParam(':p_cuenta', $cuenta);
            $stm->bindParam(':p_depto', $depto);
            $stm->bindParam(':p_rol', $rol);
            $stm->bindParam(':p_vacaciones', $vacaciones);
            $stm->bindParam(':p_licencias', $licencias);
            $stm->bindParam(':p_carnet_agente', $carnet_agente);
            $stm->bindParam(':p_carnet_arma', $carnet_armas);
            $stm->bindParam(':p_psicologico', $psicologico);
            $stm->bindParam(':p_huellas', $huellas);
            $stm->bindParam(':p_foto', $nombreArchivoFinal);
            $stm->bindParam(':p_codigo', $codigo);

            if ($stm->execute()) {
                return "success";
            } else {
                return "error";
            }
        } catch (PDOException $e) {
            $error = $e->getMessage();
            echo "<script>console.error(" . json_encode("Error: $error") . ");</script>";
            return ['error' => 'Excepción: ' . $error];
        }
    }

    public function updateEstado($id, $situacion, $usuario, $observacion)
    {
        try {
            $stm = $this->conn->prepare('CALL sp_update_colaborador_estado(:p_id, :p_situacion, :p_usuario, :p_observacion)');
            $stm->bindParam(':p_id', $id);
            $stm->bindParam(':p_situacion', $situacion);
            $stm->bindParam(':p_usuario', $usuario);
            $stm->bindParam(':p_observacion', $observacion);

            if ($stm->execute()) {
                return "success";
            } else {
                return "error";
            }
        } catch (PDOException $e) {
            $error = $e->getMessage();
            echo "<script>console.error(" . json_encode("Error: $error") . ");</script>";
            return ['error' => 'Excepción: ' . $error];
        }
    }

    public function updateEstadoUser($id, $estado)
    {
        try {
            $stm = $this->conn->prepare('CALL sp_update_estado_usuario(:p_id, :p_estado)');
            $stm->bindParam(':p_id', $id);
            $stm->bindParam(':p_estado', $estado);

            if ($stm->execute()) {
                return "success";
            } else {
                return "error";
            }
        } catch (PDOException $e) {
            $error = $e->getMessage();
            echo "<script>console.error(" . json_encode("Error: $error") . ");</script>";
            return ['error' => 'Excepción: ' . $error];
        }
    }

    public function update_acceso($id, $usuario, $acceso, $observaciones)
    {
        try {
            $stm = $this->conn->prepare('CALL sp_update_usuario_acceso(:p_id, :p_usuario, :p_acceso, :p_observacion)');
            $stm->bindParam(':p_id', $id);
            $stm->bindParam(':p_usuario', $usuario);
            $stm->bindParam(':p_acceso', $acceso);
            $stm->bindParam(':p_observacion', $observaciones);

            if ($stm->execute()) {
                return "success";
            } else {
                return "error";
            }
        } catch (PDOException $e) {
            $error = $e->getMessage();
            echo "<script>console.error(" . json_encode("Error: $error") . ");</script>";
            return ['error' => 'Excepción: ' . $error];
        }
    }

    public function updatePass($id, $pass)
    {
        try {
            $passwordHash = password_hash($pass, PASSWORD_DEFAULT);

            $stm = $this->conn->prepare('CALL sp_update_password(:p_id, :p_pass)');
            $stm->bindParam(':p_id', $id);
            $stm->bindParam(':p_pass', $passwordHash);

            if ($stm->execute()) {
                return "success";
            } else {
                return "error";
            }
        } catch (PDOException $e) {
            $error = $e->getMessage();
            echo "<script>console.error(" . json_encode("Error: $error") . ");</script>";
            return ['error' => 'Excepción: ' . $error];
        }
    }

    /**
     * OTRAS FUNCIONES
     */

    function generarNombreUsuario($empleado)
    {
        try {
            foreach ($empleado as $emp) {
                $nombre = $emp['emp_nombre'];
                $apellido = $emp['emp_apellidos'];
                $cedula = $emp['emp_cedula'];
                $depto = $emp['dep_detalle'];
            }

            $nombre = strtolower(preg_replace('/[^a-z]/i', '', $nombre));
            $apellido = strtolower(preg_replace('/[^a-z]/i', '', $apellido));
            $cedula = preg_replace('/\D/', '', $cedula);
            $anio = date('Y');
            $random = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 3);

            $patrones = [
                substr($nombre, 0, 1) . substr($apellido, 0, 1) . $anio . $random,
                substr($cedula, -4) . '_' . $random,
                substr($depto, 0, 3) . '_' . substr($nombre, 0, 2) . rand(10, 99),
                'usr_' . substr(sha1(uniqid()), 0, 6),
                strrev(substr($nombre, 0, 4)) . '_' . $random
            ];

            return $patrones[array_rand($patrones)];
        } catch (PDOException $e) {
            $error = $e->getMessage();
            echo "<script>console.error(" . json_encode("Error: $error") . ");</script>";
            return ['error' => 'Excepción: ' . $error];
        }
    }
}

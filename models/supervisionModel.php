<?php
require_once __DIR__ . '/../core/conexion.php';

class supervisionModel
{
    private $conn;

    public function __construct()
    {
        $conexion = new Conexion();
        $this->conn = $conexion->getConexion(); // Esto es lo que te faltaba
    }


    /**
     ********** FUNCIONES PARA OBTENER DATOS **********
     */

    public function get_oficiales_agrupados($estado)
    {
        try {
            $stmt = $this->conn->prepare("CALL sp_get_oficiales_agrupados(:p_estado)");
            $stmt->bindparam('p_estado', $estado);

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

    public function get_reportes($estado)
    {
        try {
            $stmt = $this->conn->prepare("CALL sp_get_reportes_oficiales_agrupados(:p_estado)");
            $stmt->bindparam('p_estado', $estado);

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

    public function get_oficiales_general()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_get_reportes_oficiales_general");

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

    public function get_reportes_general($id)
    {
        try {
            $stmt = $this->conn->prepare("CALL 	sp_get_reportes_oficiales(:p_emp_id)");
            $stmt->bindparam('p_emp_id', $id);

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

    public function get_reportes_todos()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_get_reportes_oficiales_todos");

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

    /**
     * FUNCIONES PARA AGREGAR
     */


    public function addComentario($id, $motivo, $justificacion, $nombre, $estado)
    {
        try {
            $stm = $this->conn->prepare("CALL sp_insert_reporteOficial(:p_empID, :p_motivo, :p_justi, :p_nombre, :p_estado)");
            $stm->bindParam(':p_empID', $id);
            $stm->bindParam(':p_motivo', $motivo);
            $stm->bindParam(':p_justi', $justificacion);
            $stm->bindParam(':p_nombre', $nombre);
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

    /**
     * FUNCIONES PARA EDITAR
     */

    public function update_reporte_oficial($id_empleado, $id_reporte, $motivo, $justificacion, $estado, $nombre)
    {
        try {
            $stm = $this->conn->prepare("CALL sp_editar_reporte_oficial (:p_id , :p_motivo, :p_justi, :p_empID , :p_estado, :p_nombre)");
            $stm->bindParam(':p_id', $id_reporte);
            $stm->bindParam(':p_motivo', $motivo);
            $stm->bindParam(':p_justi', $justificacion);
            $stm->bindParam(':p_empID', $id_empleado);
            $stm->bindParam(':p_estado', $estado);
            $stm->bindParam(':p_nombre', $nombre);

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
     * FUNCIONES PARA ELIMIAR
     */
    public function delete_reporte_oficial($id)
    {
        try {
            $stm = $this->conn->prepare("CALL sp_delete_reporte_oficial(:p_reporte_id)");
            $stm->bindParam(':p_reporte_id', $id);

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
}

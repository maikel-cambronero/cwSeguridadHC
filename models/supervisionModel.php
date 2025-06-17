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











    public function getColaboradores()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_empleadoSeguridad");

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

    public function getCategoria()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_categoriaseguridad");

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

    public function getsubCategoria()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_subcategoriaseguridad");

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

    public function getEquipo()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_seguridad_sinasignar");

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

    public function getEquipoAsignado()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_seguridad_equipoasigando");

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
     ********** FUNCIONES PARA AGRGEGAR **********
     */

    public function addEquipo($stock, $condicion, $categoria, $subCategoria, $colaborador, $detalle)
    {
        try {
            $stm = $this->conn->prepare("CALL 	sp_insert_Seguridad(:p_cantidad, :p_detalle, :p_condicion, :p_IDempleado, :p_IDcategoria , :p_IDsubcategoria )");

            $stm->bindParam(':p_cantidad', $stock);
            $stm->bindParam(':p_detalle', $detalle);
            $stm->bindParam(':p_condicion', $condicion);
            $stm->bindParam(':p_IDcategoria', $categoria);
            $stm->bindParam(':p_IDsubcategoria', $subCategoria);
            $stm->bindParam(':p_IDempleado', $colaborador);


            if ($stm->execute()) {
                return "success";
            } else {
                return "error";
            }
        } catch (PDOException $e) {
            return ['error' => 'Excepción: ' . $e->getMessage()];
        }
    }


    /**
     ********** FUNCIONES PARA EDITAR **********
     */

    public function updateEquipo($stock, $condicion, $categoria, $subCategoria, $colaborador, $detalle, $id)
    {
        try {
            $stm = $this->conn->prepare('CALL sp_update_Seguridad(:p_id, :p_cantidad, :p_IDempleado, :p_IDcategoria, :p_IDsubcategoria, :p_detalle, :p_condicion)');
            $stm->bindParam(':p_id', $id);
            $stm->bindParam(':p_cantidad', $stock);
            $stm->bindParam(':p_IDempleado', $colaborador);
            $stm->bindParam(':p_IDcategoria', $categoria);
            $stm->bindParam(':p_IDsubcategoria', $subCategoria);
            $stm->bindParam(':p_detalle', $detalle);
            $stm->bindParam(':p_condicion', $condicion);

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
     ********** FUNCIONES PARA ELIMINAR **********
     */

    public function deleteEquipo($id)
    {
        try {
            $stm = $this->conn->prepare('CALL sp_delete_Seguridad(:p_id)');
            $stm->bindParam(':p_id', $id);

            if ($stm->execute()) {
                return "success";
            } else {
                return "error";
            }
        } catch (PDOException $e) {
            return ['error' => 'Excepción: ' . $e->getMessage()];
        }
    }
}

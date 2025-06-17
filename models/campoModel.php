<?php
require_once __DIR__ . '/../core/conexion.php';

class campoModel
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

    public function getColaboradores()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_empleado_campo");

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

    public function getCategoriaCampo()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_categoriaCampo");

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

    public function getsubCategoriaCampo()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_subcategoriaCampo");

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

    public function getMarcasCampo()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_marcasCampo");

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

    public function getHerramientas()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_campo_sinAsignar");

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

    public function getHerramientasAsignadas()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_campoAsignado");

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

    public function getHerramientasGeneral()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_campo_general");

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

    public function addHerramineta($stock, $marca, $categoria, $subCategoria, $colaborador, $detalle)
    {
        try {
            $stm = $this->conn->prepare("CALL sp_insert_Herramienta(:p_cantidad, :p_detalle, :p_marca, :p_idCategoria , :p_idSubcategoria , :p_idEmpleado)");

            $stm->bindParam(':p_cantidad', $stock);
            $stm->bindParam(':p_detalle', $detalle);
            $stm->bindParam(':p_marca', $marca);
            $stm->bindParam(':p_idCategoria', $categoria);
            $stm->bindParam(':p_idSubcategoria', $subCategoria);
            $stm->bindParam(':p_idEmpleado', $colaborador);


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

    public function upadateHerramienta($stock, $marca, $categoria, $subCategoria, $colaborador, $detalle, $id)
    {
        try {
            $stm = $this->conn->prepare('CALL sp_update_Herramienta(:p_id, :p_cantidad, :p_detalle, :p_marca, :p_catg_idCategoria, :p_scat_idSubcategoria, :p_empo_idEmpleado)');
            $stm->bindParam(':p_id', $id);
            $stm->bindParam(':p_cantidad', $stock);
            $stm->bindParam(':p_detalle', $detalle);
            $stm->bindParam(':p_marca', $marca);
            $stm->bindParam(':p_catg_idCategoria', $categoria);
            $stm->bindParam(':p_scat_idSubcategoria', $subCategoria);
            $stm->bindParam(':p_empo_idEmpleado', $colaborador);

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
     ********** FUNCIONES PARA ELIMINAR **********
     */

    public function deleteHerramienta($id)
    {
        try {
            $stm = $this->conn->prepare('CALL sp_delete_Herramienta(:p_id)');
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

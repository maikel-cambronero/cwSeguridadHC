<?php
require_once __DIR__ . '/../core/conexion.php';

class cotizacionesModel
{
    private $conn;

    public function __construct()
    {
        $conexion = new Conexion();
        $this->conn = $conexion->getConexion(); // Esto es lo que te faltaba
    }

    /**
     * FUNCIONES PARA LEER
     */

    public function getProductos()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_electronicos_agrupados");

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


    public function getLastID()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_coti_lastID");

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





































    public function getElectronicos_Agrupados($estado)
    {
        try {
            $stmt = $this->conn->prepare("CALL sp_getElectronicosByEstado(:p_estado)");
            $stmt->bindParam(':p_estado', $estado);

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


    public function getElectronicos_Agrupados_general()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_electronicos_agrupados");

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


    public function getElectronicos_Todos($codigo)
    {
        try {
            $stmt = $this->conn->prepare("CALL sp_get_electronicosDesagrupados(:p_codigo)");
            $stmt->bindParam(':p_codigo', $codigo);

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

    public function getElectronicos_General()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_get_electronicos_general");

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

    public function getElectroinco_advertencia()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_electronicoadvertencia");

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


    public function getMarcas()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_marcas");

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

    public function getProveedores()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_proveedores");

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
            $stmt = $this->conn->prepare("SELECT * FROM v_categoria");

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
            $stmt = $this->conn->prepare("SELECT * FROM v_subcategoria");

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

    public function addEquipo($detalle, $codigo, $stock, $limite, $buffer, $marca, $categoria, $subcategoria, $proveedor, $consecutivo, $compra, $utilidad, $venta)
    {
        try {
            $stm = $this->conn->prepare("CALL sp_insert_equipo(:p_stok, :p_detalle, :p_marca, :p_codigo, :p_cantMin, :p_precio_prov, :p_utilidad, :p_total, :p_prov_id, :p_catg_id, :p_scat_id, :p_fact_consecutivo, :p_buffer)");
            $stm->bindParam(':p_stok', $stock);
            $stm->bindParam(':p_detalle', $detalle);
            $stm->bindParam(':p_marca', $marca);
            $stm->bindParam(':p_codigo', $codigo);
            $stm->bindParam(':p_cantMin', $limite);
            $stm->bindParam(':p_precio_prov', $compra);
            $stm->bindParam(':p_utilidad', $utilidad);
            $stm->bindParam(':p_total', $venta);
            $stm->bindParam(':p_prov_id', $proveedor);
            $stm->bindParam(':p_catg_id', $categoria);
            $stm->bindParam(':p_scat_id', $subcategoria);
            $stm->bindParam(':p_fact_consecutivo', $consecutivo);
            $stm->bindParam(':p_buffer', $buffer);

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

    public function updateEquipo($id, $detalle, $codigo, $stock, $limite, $buffer, $marca, $categoria, $subcategoria, $proveedor, $consecutivo, $compra, $utilidad, $venta)
    {
        try {
            $stm = $this->conn->prepare("CALL sp_update_equipo(:p_id, :p_detalle, :p_codigo, :p_stock, :p_limite, :p_buffer, :p_marca, :p_categoria, :p_subcategoria, :p_proveedor, :p_consecutivo, :p_compra, :p_utilidad, :p_venta, :p_estado)");

            $optimo = $limite + $buffer;
            if ($stock > $optimo) {
                $estado = 1;
            } elseif ($stock <= $optimo && $stock > $limite) {
                $estado = 2;
            } elseif ($stock <= $limite) {
                $estado = 3;
            }

            // Validar y vincular las variables con bindParam
            $stm->bindParam(':p_id', $id);
            $stm->bindParam(':p_detalle', $detalle);
            $stm->bindParam(':p_codigo', $codigo);
            $stm->bindParam(':p_stock', $stock);
            $stm->bindParam(':p_limite', $limite);
            $stm->bindParam(':p_buffer', $buffer);
            $stm->bindParam(':p_marca', $marca);
            $stm->bindParam(':p_categoria', $categoria);
            $stm->bindParam(':p_subcategoria', $subcategoria);
            $stm->bindParam(':p_proveedor', $proveedor);
            $stm->bindParam(':p_consecutivo', $consecutivo);
            $stm->bindParam(':p_compra', $compra);
            $stm->bindParam(':p_utilidad', $utilidad);
            $stm->bindParam(':p_venta', $venta);
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
     * FUNCIONES PARA ELIMINAR
     */

    public function deleteEquipo($id)
    {
        try {
            $stm = $this->conn->prepare("CALL sp_delete_equipo(:p_id_equipo)");

            $stm->bindParam(':p_id_equipo', $id);

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

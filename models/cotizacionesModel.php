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


    private function limpiarNumero($numero)
    {
        // Quita espacios invisibles, convierte coma a punto, elimina cualquier símbolo extraño
        $numero = str_replace(["\xc2\xa0", " ", " "], "", $numero); // quita espacios duros y normales
        $numero = str_replace(",", ".", $numero); // convierte coma a punto decimal
        return floatval($numero);
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
            $error = $e->getMessage();
            echo "<script>console.error(" . json_encode("Error: $error") . ");</script>";
            return ['error' => 'Excepción: ' . $error];
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
            $error = $e->getMessage();
            echo "<script>console.error(" . json_encode("Error: $error") . ");</script>";
            return ['error' => 'Excepción: ' . $error];
        }
    }

    public function get_coti_codigo($numCoti)
    {
        try {
            $stmt = $this->conn->prepare("CALL sp_get_coti_codigo(:p_codigo)");
            $stmt->bindParam(':p_codigo', $numCoti);

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

    public function get_equiposCoti_codigo($cot_id)
    {
        try {
            $stmt = $this->conn->prepare("CALL sp_get_equiposCoti(:p_coti_id)");
            $stmt->bindParam(':p_coti_id', $cot_id);

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

    public function getCotizaciones()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_get_cotizaciones");

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

    public function getCotiID($id)
    {
        try {
            $stmt = $this->conn->prepare("CALL sp_get_coti_id(:p_id)");
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

    public function get_equiposCoti()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_equipos_coti");

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

    public function addCoti($cotizacion, $dateEmite, $dateValida, $saler, $cliente, $tell, $subtotal_general, $iva_general, $descuento_general, $total_general, $equipos)
    {
        try {
            $subtotal_general = $this->limpiarNumero($subtotal_general);
            $iva_general = $this->limpiarNumero($iva_general);
            $descuento_general = $this->limpiarNumero($descuento_general);
            $total_general = $this->limpiarNumero($total_general);

            $stm = $this->conn->prepare("CALL sp_insert_cotizacion(:p_codigo, :p_vendor, :p_cliente, :p_telefono, :p_fecha1, :p_fecha2,
            :p_subtotal, :p_iva, :p_descuento, :p_total)");

            $stm->bindParam(':p_codigo', $cotizacion);
            $stm->bindParam(':p_vendor', $saler);
            $stm->bindParam(':p_cliente', $cliente);
            $stm->bindParam(':p_telefono', $tell);
            $stm->bindParam(':p_fecha1', $dateEmite);
            $stm->bindParam(':p_fecha2', $dateValida);
            $stm->bindParam(':p_subtotal', $subtotal_general);
            $stm->bindParam(':p_iva', $iva_general);
            $stm->bindParam(':p_descuento', $descuento_general);
            $stm->bindParam(':p_total', $total_general);

            if ($stm->execute()) {

                $stm->closeCursor();

                $stmt = $this->conn->prepare("SELECT * FROM v_coti_lastID");
                $stmt->execute();
                $fila = $stmt->fetch(PDO::FETCH_ASSOC);
                $stmt->closeCursor();

                if (!$fila || !isset($fila['last_id'])) {
                    echo "No se obtuvo cot_id nuevo";
                }
                $coti_id = (int)$fila['last_id'];

                if (is_array($equipos) && count($equipos) > 0) {
                    foreach ($equipos as $equipo) {
                        $detalle = $equipo['descripcion'];
                        $cantidad = $equipo['cantidad'];
                        $precio = $equipo['precio'];
                        $ivainput = $equipo['iva'];
                        $descinput = $equipo['descuento'];
                        $subtotal = $equipo['subtotal_hidden'];
                        $iva = $equipo['iva_hidden'];
                        $descuento = $equipo['descuento_hidden'];
                        $total = $equipo['total_hidden'];

                        $stm_equipo = $this->conn->prepare("CALL sp_insert_equipoCotizacion (:p_detalle, :p_cantidad, :p_precio, :p_iva,
                        :p_descuento, :p_subtotal, :p_sub_iva, :p_sub_desc, :p_total_line, :p_coti_id)");
                        $stm_equipo->bindParam(':p_detalle', $detalle);
                        $stm_equipo->bindParam(':p_cantidad', $cantidad);
                        $stm_equipo->bindParam(':p_precio', $precio);
                        $stm_equipo->bindParam(':p_iva', $ivainput);
                        $stm_equipo->bindParam(':p_descuento', $descinput);
                        $stm_equipo->bindParam(':p_subtotal', $subtotal);
                        $stm_equipo->bindParam(':p_sub_iva', $iva);
                        $stm_equipo->bindParam(':p_sub_desc', $descuento);
                        $stm_equipo->bindParam(':p_total_line', $total);
                        $stm_equipo->bindParam(':p_coti_id', $coti_id, PDO::PARAM_INT);


                        if (!$stm_equipo->execute()) {
                            return "error";
                        }
                    }
                    return "success";
                } else {
                    echo "<script>console.error(" . json_encode("No tengo equipos para cotizar") . ");</script>";
                    return "error";
                }
            } else {
                echo "<script>console.error(" . json_encode("No se insertó la cotizacion general") . ");</script>";
                return "error";
            }
        } catch (PDOException $e) {
            $error = $e->getMessage();
            echo "<script>console.error(" . json_encode("Error: $error") . ");</script>";
            return ['error' => 'Excepción: ' . $error];
        }
    }

    /**
     * FUNCIONES PARA ELIMANAR
     */

    public function deleteCoti($id)
    {
        try {
            $stmt = $this->conn->prepare("CALL sp_delete_coti(:p_id)");
            $stmt->bindParam(':p_id', $id);

            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $stmt->closeCursor();

                if (!empty($result)) {
                    return 'success'; // Retorna los datos del proveedor
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

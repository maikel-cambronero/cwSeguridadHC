<?php
require_once __DIR__ . '/../core/conexion.php';

class gestionModel
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

    public function getCategorias()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_get_categoriagestion");

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

    public function getEstados()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_get_estadosGestion");

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

    public function getSubcategoria()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_get_subcategoriagestion");

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
            return ['error' => 'Excepción: ' . $e->getMessage()];
        }
    }

    public function getMarca()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_gestion_marca");

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
            return ['error' => 'Excepción: ' . $e->getMessage()];
        }
    }

    public function getProveedor()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM `v_gestion_proveedores`");

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
            return ['error' => 'Excepción: ' . $e->getMessage()];
        }
    }

    public function getVehiculo()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM `v_vehiculo`");

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
            return ['error' => 'Excepción: ' . $e->getMessage()];
        }
    }

    /**
     ********** FUNCIONES PARA AGRGEGAR **********
     */

    public function addCategoria($detalle, $estado)
    {
        try {
            $stm = $this->conn->prepare("CALL sp_insert_categoria(:p_detalle, :p_estado_id)");
            $stm->bindParam(':p_detalle', $detalle);
            $stm->bindParam(':p_estado_id', $estado);

            if ($stm->execute()) {
                return "success";
            } else {
                return "error";
            }
        } catch (PDOException $e) {
            // Puedes agregar más detalles de error para depurar
            return ['error' => 'Excepción: ' . $e->getMessage()];
        }
    }

    public function addSubcategoria($detalle, $catPadre, $estado)
    {
        try {
            $stm = $this->conn->prepare("CALL sp_insert_subcategoria(:p_detalle , :p_categoriaPadre , :p_estado)");
            $stm->bindParam(':p_detalle', $detalle);
            $stm->bindParam(':p_categoriaPadre', $catPadre);
            $stm->bindParam(':p_estado', $estado);

            if ($stm->execute()) {
                return "success";
            } else {
                return "error";
            }
        } catch (PDOException $e) {
            // Puedes agregar más detalles de error para depurar
            return ['error' => 'Excepción: ' . $e->getMessage()];
        }
    }

    public function addMarca($detalle, $estado)
    {
        try {
            $stm = $this->conn->prepare("CALL sp_insert_marca(:p_detalle, :p_est_idEstado)");
            $stm->bindParam(':p_detalle', $detalle);
            $stm->bindParam(':p_est_idEstado', $estado);

            if ($stm->execute()) {
                return "success";
            } else {
                return "error";
            }
        } catch (PDOException $e) {
            // Puedes agregar más detalles de error para depurar
            return ['error' => 'Excepción: ' . $e->getMessage()];
        }
    }

    public function addProveedor($nombre_empresa, $cedula_empresa, $pago_empresa, $ubicacion_empresa, $nombre_proveedor, $telefono_proveedor, $correo_proveedor, $moneda)
    {
        try {
            $stm = $this->conn->prepare("CALL sp_insert_proveedor(:p_empresa, :p_identificacion, :p_telefono, :p_correo, :p_direccion, :p_contacto_nombre, :p_contacto_telefono, :p_contacto_correo, :_moneda_preferida, :p_condiciones_pago)");
            $stm->bindParam(':p_empresa', $nombre_empresa);
            $stm->bindParam(':p_identificacion', $cedula_empresa);
            $stm->bindParam(':p_telefono', $telefono_proveedor);
            $stm->bindParam(':p_correo', $correo_proveedor);
            $stm->bindParam(':p_direccion', $ubicacion_empresa);
            $stm->bindParam(':p_contacto_nombre', $nombre_proveedor);
            $stm->bindParam(':p_contacto_telefono', $telefono_proveedor);
            $stm->bindParam(':p_contacto_correo', $correo_proveedor);
            $stm->bindParam(':_moneda_preferida', $moneda);
            $stm->bindParam(':p_condiciones_pago', $pago_empresa);

            if ($stm->execute()) {
                return "success";
            } else {
                return "error";
            }
        } catch (PDOException $e) {
            // Puedes agregar más detalles de error para depurar
            return ['error' => 'Excepción: ' . $e->getMessage()];
        }
    }

    public function addVehiculo($placa, $marca, $modelo, $fabricacion, $tipo_vehiculo, $chasis, $motor, $km, $seguro, $revision, $observaciones)
    {
        try {
            $stm = $this->conn->prepare("CALL sp_insert_vehiculo(:p_placa, :p_marca, :p_modelo, :p_anio, :p_color, :p_tipo, :p_chasis, :p_motor, :p_kilometraje, :p_vencimiento_seguro, :p_revision, :p_observaciones)");
            $color = "Blanco";
            $stm->bindParam(':p_placa', $placa);
            $stm->bindParam(':p_marca', $marca);
            $stm->bindParam(':p_modelo', $modelo);
            $stm->bindParam(':p_anio', $fabricacion);
            $stm->bindParam(':p_color', $color);
            $stm->bindParam(':p_tipo', $tipo_vehiculo);
            $stm->bindParam(':p_chasis', $chasis);
            $stm->bindParam(':p_motor', $motor);
            $stm->bindParam(':p_kilometraje', $km);
            $stm->bindParam(':p_vencimiento_seguro', $seguro);
            $stm->bindParam(':p_revision', $revision);
            $stm->bindParam(':p_observaciones', $observaciones);

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

    public function updateCategoria($detalle, $estado, $id)
    {
        try {
            $stm = $this->conn->prepare("CALL sp_update_categoria(:p_id, :p_detalle, :p_estado)");
            $stm->bindParam(':p_id', $id);
            $stm->bindParam(':p_detalle', $detalle);
            $stm->bindParam(':p_estado', $estado);

            if ($stm->execute()) {
                return "success";
            } else {
                return "error";
            }
        } catch (PDOException $e) {
            // Puedes agregar más detalles de error para depurar
            return ['error' => 'Excepción: ' . $e->getMessage()];
        }
    }

    public function updateSubcategoria($id, $detalle, $catPadre, $estado)
    {
        try {
            $stm = $this->conn->prepare("CALL sp_update_subcategoria(:p_id, :p_detalle, :p_catg_catgPadre, :p_est_idEstado)");
            $stm->bindParam(':p_id', $id);
            $stm->bindParam(':p_detalle', $detalle);
            $stm->bindParam(':p_catg_catgPadre', $catPadre);
            $stm->bindParam(':p_est_idEstado', $estado);

            if ($stm->execute()) {
                return "success";
            } else {
                return "error";
            }
        } catch (PDOException $e) {
            // Puedes agregar más detalles de error para depurar
            return ['error' => 'Excepción: ' . $e->getMessage()];
        }
    }

    public function updateMarca($id, $detalle, $estado)
    {
        try {
            $stm = $this->conn->prepare("CALL sp_update_marca(:p_id, :p_detalle, :p_est_idEstado)");
            $stm->bindParam(':p_id', $id);
            $stm->bindParam(':p_detalle', $detalle);
            $stm->bindParam(':p_est_idEstado', $estado);

            if ($stm->execute()) {
                return "success";
            } else {
                return "error";
            }
        } catch (PDOException $e) {
            // Puedes agregar más detalles de error para depurar
            return ['error' => 'Excepción: ' . $e->getMessage()];
        }
    }

    public function updateProveedor($id, $nombre_empresa, $cedula_empresa, $pago_empresa, $ubicacion_empresa, $nombre_proveedor, $telefono_proveedor, $correo_proveedor, $moneda)
    {
        try {
            $stm = $this->conn->prepare("CALL sp_update_proveedor(:p_id, :p_empresa, :p_identificacion, :p_telefono, :p_correo, :p_direccion, :p_contacto_nombre, :p_contacto_telefono, :p_contacto_correo, :_moneda_preferida, :p_condiciones_pago)");
            $stm->bindParam(':p_id', $id);
            $stm->bindParam(':p_empresa', $nombre_empresa);
            $stm->bindParam(':p_identificacion', $cedula_empresa);
            $stm->bindParam(':p_telefono', $telefono_proveedor);
            $stm->bindParam(':p_correo', $correo_proveedor);
            $stm->bindParam(':p_direccion', $ubicacion_empresa);
            $stm->bindParam(':p_contacto_nombre', $nombre_proveedor);
            $stm->bindParam(':p_contacto_telefono', $telefono_proveedor);
            $stm->bindParam(':p_contacto_correo', $correo_proveedor);
            $stm->bindParam(':_moneda_preferida', $moneda);
            $stm->bindParam(':p_condiciones_pago', $pago_empresa);

            if ($stm->execute()) {
                return "success";
            } else {
                return "error";
            }
        } catch (PDOException $e) {
            // Puedes agregar más detalles de error para depurar
            return ['error' => 'Excepción: ' . $e->getMessage()];
        }
    }

    public function updateVehiculo($id, $placa, $marca, $modelo, $fabricacion, $tipo_vehiculo, $chasis, $motor, $km, $seguro, $revision, $observaciones)
    {
        try {
            $stm = $this->conn->prepare("CALL sp_update_vehiculo(:p_id, :p_placa, :p_marca, :p_modelo, :p_anio, :p_tipo, :p_chasis, :p_motor, :p_kilometraje, :p_vencimiento_seguro, :p_revision, :p_observaciones)");
            $stm->bindParam(':p_id', $id);
            $stm->bindParam(':p_placa', $placa);
            $stm->bindParam(':p_marca', $marca);
            $stm->bindParam(':p_modelo', $modelo);
            $stm->bindParam(':p_anio', $fabricacion);
            $stm->bindParam(':p_tipo', $tipo_vehiculo);
            $stm->bindParam(':p_chasis', $chasis);
            $stm->bindParam(':p_motor', $motor);
            $stm->bindParam(':p_kilometraje', $km);
            $stm->bindParam(':p_vencimiento_seguro', $seguro);
            $stm->bindParam(':p_revision', $revision);
            $stm->bindParam(':p_observaciones', $observaciones);

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

    public function deleteCategoria($id)
    {
        try {
            $stm = $this->conn->prepare('CALL sp_delete_categoria(:p_id)');
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

    public function deleteSubcategoria($id)
    {
        try {
            $stm = $this->conn->prepare('CALL sp_delete_subcategoria(:p_id)');
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

    public function deleteMarca($id)
    {
        try {
            $stm = $this->conn->prepare('CALL sp_delete_marca(:p_id)');
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

    public function deleteProveedor($id)
    {
        try {
            $stm = $this->conn->prepare('CALL sp_delete_proveedor(:p_id)');
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

    public function deleteVehiculo($id)
    {
        try {
            $stm = $this->conn->prepare('CALL sp_delete_vehiculo(:p_id)');
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

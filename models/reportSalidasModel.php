<?php
require_once __DIR__ . '/../core/conexion.php';

class ordenModel
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

    public function get_orden_trabajo()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM `v_orden_trabajo`");

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
            $stmt = $this->conn->prepare("SELECT * FROM v_orden_lastID");
            if ($stmt->execute()) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $stmt->closeCursor();

                if (!empty($result)) {
                    return $result['lastID'];
                } else {
                    return 0;
                }
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            // Puedes agregar más detalles de error para depurar
            return ['error' => 'Excepción: ' . $e->getMessage()];
        }
    }

    public function get_orden_codigo($orden)
    {
        try {
            $stmt = $this->conn->prepare("CALL sp_get_ordenCodigo(:p_codigo)");
            $stmt->bindParam(':p_codigo', $orden);

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

    public function get_equipos_orden($id)
    {
        try {
            $stmt = $this->conn->prepare("CALL sp_get_equipos_orden_id(:p_orden_id)");
            $stmt->bindParam(':p_orden_id', $id);

            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $stmt->closeCursor();

                return $result; // Siempre retorna el arreglo, aunque esté vacío
            } else {
                return []; // Si la ejecución falla, también retorna arreglo vacío
            }
        } catch (PDOException $e) {
            $error = $e->getMessage();
            echo "<script>console.error(" . json_encode("Error: $error") . ");</script>";
            return []; // En caso de excepción, retorna arreglo vacío también
        }
    }

    public function get_buseta()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM v_get_vehiculos_busetas");

            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $stmt->closeCursor();

                return $result; // Siempre retorna el arreglo, aunque esté vacío
            } else {
                return "error";
            }
        } catch (PDOException $e) {
            $error = $e->getMessage();
            echo "<script>console.error(" . json_encode("Error: $error") . ");</script>";
            return []; // En caso de excepción, retorna arreglo vacío también
        }
    }

    /**
     * FUNCIONES PARA AGREGAR
     */

    public function addOrden($orden, $fecha, $tecnico, $asistente1, $asistente2, $tipoTrabajo, $cliente, $direccion, $telefono, $descripcion, $equipos, $vehiculo)
    {
        try {
            $stm = $this->conn->prepare("CALL sp_insert_orden(:p_num_orden, :p_fecha, :p_tecnico, :p_asistente1, :p_asistente2, :p_tipo_trabajo, :p_cliente, :p_direccion, :p_telefono, :p_trabajo, :p_vehiculo)");
            $stm->bindParam(':p_num_orden', $orden);
            $stm->bindParam(':p_fecha', $fecha);
            $stm->bindParam(':p_tecnico', $tecnico);
            $stm->bindParam(':p_asistente1', $asistente1);
            $stm->bindParam(':p_asistente2', $asistente2);
            $stm->bindParam(':p_tipo_trabajo', $tipoTrabajo);
            $stm->bindParam(':p_cliente', $cliente);
            $stm->bindParam(':p_direccion', $direccion);
            $stm->bindParam(':p_telefono', $telefono);
            $stm->bindParam(':p_trabajo', $descripcion);
            $stm->bindParam(':p_vehiculo', $vehiculo);

            if ($stm->execute()) {
                $result = $stm->fetch(PDO::FETCH_ASSOC);
                $orden_id = $result['orden_id'];

                $stm->closeCursor();

                if (is_array($equipos) && count($equipos) > 0) {
                    foreach ($equipos as $equipo) {
                        $codigo = $equipo['codigo'];
                        $descripcionEq = $equipo['descripcion'];
                        $cantidad = $equipo['cantidad'];
                        $tipo_entrega = $equipo['tipo_entrega'];

                        $stm_equipo = $this->conn->prepare("CALL sp_insert_equipo_orden(:p_codigo, :p_descripcion, :p_cantidad, :p_tipo, :p_orden_id)");
                        $stm_equipo->bindParam(':p_codigo', $codigo);
                        $stm_equipo->bindParam(':p_descripcion', $descripcionEq);
                        $stm_equipo->bindParam(':p_cantidad', $cantidad);
                        $stm_equipo->bindParam(':p_tipo', $tipo_entrega);
                        $stm_equipo->bindParam(':p_orden_id', $orden_id);

                        if (!$stm_equipo->execute()) {
                            return "error";
                        }
                    }

                    return "success";
                } else {
                    return "success";
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



    public function update_equipos_orden_id_integrar($id)
    {
        try {
            $stmt = $this->conn->prepare("CALL sp_get_equipos_orden_id(:p_orden_id)");
            $stmt->bindParam(':p_orden_id', $id);

            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $stmt->closeCursor();

                if (!empty($result)) {
                    $equipos = $result;

                    $success = true;

                    foreach ($equipos as $equipo) {
                        $codigo = $equipo['erd_codigo'];
                        $cantidad = $equipo['erd_cantidad'];



                        $stmt = $this->conn->prepare("CALL sp_sumar_cantidad_equipo_eliminar(:p_codigo, :p_cantidad)");
                        $stmt->bindParam(':p_codigo', $codigo);
                        $stmt->bindParam(':p_cantidad', $cantidad);

                        if (!$stmt->execute()) {
                            $success = false;
                            $errorInfo = $stmt->errorInfo();
                        }

                        do {
                            $stmt->fetch();
                        } while ($stmt->nextRowset());

                        $stmt->closeCursor();

                        if (!$success) break; // Salir si ya falló
                    }

                    if ($success) {
                        // Eliminar equipos
                        $stmtEliminarEquipos = $this->conn->prepare("CALL sp_delete_equipos_por_orden(:p_orden_id)");
                        $stmtEliminarEquipos->bindParam(':p_orden_id', $id);
                        if (!$stmtEliminarEquipos->execute()) $success = false;
                        $stmtEliminarEquipos->closeCursor();
                    }

                    if ($success) {
                        // Eliminar orden
                        $stmtEliminarOrden = $this->conn->prepare("CALL sp_delete_orden(:p_orden_id)");
                        $stmtEliminarOrden->bindParam(':p_orden_id', $id);
                        if (!$stmtEliminarOrden->execute()) $success = false;
                        $stmtEliminarOrden->closeCursor();
                    }

                    return $success ? 'success' : 'error';
                } else {
                    return 'error'; // No hay equipos
                }
            } else {
                return 'error'; // Falló consulta inicial
            }
        } catch (PDOException $e) {
            // Opcional: loggear excepción
            return ['error' => 'Excepción: ' . $e->getMessage()];
        }
    }

    public function update_orden_sinEquipo($id, $fecha, $tecnico, $asistente1, $asistente2, $tipoTrabajo, $cliente, $direccion, $telefono, $descripcion, $vehiculo)
    {
        try {
            $stmt = $this->conn->prepare("CALL sp_update_orden_sinEquipos(:p_orden_id, :p_orden_fecha, :p_orden_tecnico, :p_orden_asistente1, :p_orden_asistente2, :p_orden_tipoTrabajo, :p_orden_cliente, :p_orden_direccion, :p_orden_telefono, :p_orden_descripcion, :p_orden_vehiculo)");
            $stmt->bindParam(':p_orden_id', $id);
            $stmt->bindParam(':p_orden_fecha', $fecha);
            $stmt->bindParam(':p_orden_tecnico', $tecnico);
            $stmt->bindParam(':p_orden_asistente1', $asistente1);
            $stmt->bindParam(':p_orden_asistente2', $asistente2);
            $stmt->bindParam(':p_orden_tipoTrabajo', $tipoTrabajo);
            $stmt->bindParam(':p_orden_cliente', $cliente);
            $stmt->bindParam(':p_orden_direccion', $direccion);
            $stmt->bindParam(':p_orden_telefono', $telefono);
            $stmt->bindParam(':p_orden_descripcion', $descripcion);
            $stmt->bindParam(':p_orden_vehiculo', $vehiculo);

            if ($stmt->execute()) {
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

    public function delete_equipos_orden_id($id)
    {
        try {
            $success = true;

            // Eliminar equipos
            $stmtEliminarEquipos = $this->conn->prepare("CALL sp_delete_equipos_por_orden(:p_orden_id)");
            $stmtEliminarEquipos->bindParam(':p_orden_id', $id);
            if (!$stmtEliminarEquipos->execute()) {
                $success = false;
            }
            $stmtEliminarEquipos->closeCursor();

            // Eliminar orden
            $stmtEliminarOrden = $this->conn->prepare("CALL sp_delete_orden(:p_orden_id)");
            $stmtEliminarOrden->bindParam(':p_orden_id', $id);
            if (!$stmtEliminarOrden->execute()) {
                $success = false;
            }
            $stmtEliminarOrden->closeCursor();

            return $success ? 'success' : 'error';
        } catch (PDOException $e) {
            $error = $e->getMessage();
            echo "<script>console.error(" . json_encode("Error: $error") . ");</script>";
            return ['error' => 'Excepción: ' . $error];
        }
    }



    public function valida_equipo_instalacion($id)
    {
        try {
            // Llamada al SP con parámetro OUT
            $stm = $this->conn->prepare("CALL sp_get_equipos_orden_instalacion(:p_id_equipo, @tiene_instalacion)");
            $stm->bindParam(':p_id_equipo', $id, PDO::PARAM_INT);
            $stm->execute();

            // Obtener el valor de salida
            $result = $this->conn->query("SELECT @tiene_instalacion AS tiene_instalacion")->fetch(PDO::FETCH_ASSOC);

            if ($result && isset($result['tiene_instalacion'])) {
                return $result['tiene_instalacion'] == 1 ? "instalacion" : "sin_instalacion";
            } else {
                return "error";
            }
        } catch (PDOException $e) {
            $error = $e->getMessage();
            echo "<script>console.error(" . json_encode("Error: $error") . ");</script>";
            return ['error' => 'Excepción: ' . $error];
        }
    }

    public function valida_equipos($id, $fecha, $tecnico, $asistente1, $asistente2, $tipoTrabajo, $cliente, $direccion, $telefono, $descripcion, $vehiculo, $equipos_formulario)
    {
        try {
            // Arreglos que contiene los equipos enviados desde el formulario y desde la bd, respectivamente
            $equipos_BD = $this->get_equipos_orden($id);

            $formulario_tipo1 = [];
            foreach ($equipos_formulario as $eq) {
                if ($eq['tipo_entrega'] == 1) {
                    $formulario_tipo1[$eq['codigo']] = $eq;
                }
            }

            $equiposDB_tipo1 = [];
            foreach ($equipos_BD as $eq) {
                if ($eq['erd_tipo'] == 1) {
                    $equiposDB_tipo1[$eq['erd_codigo']] = $eq;
                }
            }

            // Compara los equipos con tipo 1
            if (count($formulario_tipo1) !== count($equiposDB_tipo1)) {
                $equiposIgual = $this->update_equipo_orden_suma($id, $fecha, $tecnico, $asistente1, $asistente2, $tipoTrabajo, $cliente, $direccion, $telefono, $descripcion, $vehiculo, $equipos_formulario, $equipos_BD);
            } else {
                $update = $this->update_equipos_orden_noSuma($id, $fecha, $tecnico, $asistente1, $asistente2, $tipoTrabajo, $cliente, $direccion, $telefono, $descripcion, $vehiculo, $equipos_formulario);
                if ($update == "success") {
                    return 'success';
                } else {
                    return 'error';
                }
            }
        } catch (PDOException $e) {
            $error = $e->getMessage();
            echo "<script>console.error(" . json_encode("Error: $error") . ");</script>";
            return ['error' => 'Excepción: ' . $error];
        }
    }

    public function update_equipos_orden_noSuma($id, $fecha, $tecnico, $asistente1, $asistente2, $tipoTrabajo, $cliente, $direccion, $telefono, $descripcion, $vehiculo, $equipos)
    {
        try {
            // Actualizar la orden sin equipos
            $stmt = $this->conn->prepare("CALL sp_update_orden_sinEquipos(:p_orden_id, :p_orden_fecha, :p_orden_tecnico, :p_orden_asistente1, :p_orden_asistente2, :p_orden_tipoTrabajo, :p_orden_cliente, :p_orden_direccion, :p_orden_telefono, :p_orden_descripcion, :p_orden_vehiculo)");
            $stmt->bindParam(':p_orden_id', $id);
            $stmt->bindParam(':p_orden_fecha', $fecha);
            $stmt->bindParam(':p_orden_tecnico', $tecnico);
            $stmt->bindParam(':p_orden_asistente1', $asistente1);
            $stmt->bindParam(':p_orden_asistente2', $asistente2);
            $stmt->bindParam(':p_orden_tipoTrabajo', $tipoTrabajo);
            $stmt->bindParam(':p_orden_cliente', $cliente);
            $stmt->bindParam(':p_orden_direccion', $direccion);
            $stmt->bindParam(':p_orden_telefono', $telefono);
            $stmt->bindParam(':p_orden_descripcion', $descripcion);
            $stmt->bindParam(':p_orden_vehiculo', $vehiculo);
            $stmt->execute();
            $stmt->closeCursor();



            if (is_array($equipos) && count($equipos) > 0) {
                $stmt2 = $this->conn->prepare("CALL sp_delete_equipoOrden_noSuma(:p_orden_id)");
                $stmt2->bindParam(':p_orden_id', $id);
                $stmt2->execute();
                $stmt2->closeCursor();

                foreach ($equipos as $equipo) {
                    $codigo = $equipo['codigo'];
                    $descripcionEq = $equipo['descripcion'];
                    $cantidad = $equipo['cantidad'];
                    $tipo_entrega = $equipo['tipo_entrega'];

                    $stm_equipo = $this->conn->prepare("CALL sp_update_equiposOrden_noSuma(:p_orden_id, :p_equipo_codigo, :p_equipo_descripcion, :p_equipo_cantidad, :p_equipo_tipoEntrega)");
                    $stm_equipo->bindValue(':p_equipo_codigo', $codigo);
                    $stm_equipo->bindValue(':p_equipo_descripcion', $descripcionEq);
                    $stm_equipo->bindValue(':p_equipo_cantidad', $cantidad);
                    $stm_equipo->bindValue(':p_equipo_tipoEntrega', $tipo_entrega);
                    $stm_equipo->bindValue(':p_orden_id', $id);

                    if (!$stm_equipo->execute()) {
                        return "error";
                    }
                }

                return "success";
            } else {
                return "success";
            }
        } catch (PDOException $e) {
            $error = $e->getMessage();
            echo "<script>console.error(" . json_encode("Error: $error") . ");</script>";
            return ['status' => 'error', 'message' => $error];
        }
    }

    /*public function update_equipo_ordern_suma($id, $fecha, $tecnico, $asistente1, $asistente2, $tipoTrabajo, $cliente, $direccion, $telefono, $descripcion, $vehiculo, $equipos_formulario, $equiposBD)
    {
        try {
            if (is_array($equiposBD) && count($equiposBD) > 0) {
                // Paso 1: Recorrer el arreglo "$equiposBD" con tipo 1 y sumarlos a la tabla inventario
                foreach ($equiposBD as $equipo) {
                    if ($equipo['erd_tipo'] == 1) {
                        $codigo = $equipo['erd_codigo'];
                        $stock = $equipo['erd_cantidad'];

                        $stmt_sumar = $this->conn->prepare("CALL sp_sumar_equipo_inventario(:p_codigo, :p_cantidad)");
                        $stmt_sumar->bindValue(':p_codigo', $codigo);
                        $stmt_sumar->bindValue(':p_cantidad', $stock);

                        if (!$stmt_sumar->execute()) {
                            return "error";
                        }
                        $stmt_sumar->closeCursor();
                    }
                }
                
                // Paso 2: Eliminar los equipos de la tabla "equipos_orden"
                $stmt_eliminar = $this->conn->prepare("CALL sp_delete_equipoOrden_noSuma(:p_orden_id)");
                $stmt_eliminar->bindValue(':p_orden_id', $id);
                $stmt_eliminar->execute();
                $stmt_eliminar->closeCursor();

                // Paso 3: Insertar y Restar nuevamente los equipos a la tabla "hc_electronicos"
                if (is_array($equipos_formulario) && count($equipos_formulario) > 0) {
                    foreach ($equipos_formulario as $equipo) {
                        $codigo = $equipo['codigo'];
                        $descripcionEq = $equipo['descripcion'];
                        $cantidad = $equipo['cantidad'];
                        $tipo_entrega = $equipo['tipo_entrega'];

                        $stm_equipo = $this->conn->prepare("CALL sp_insert_equipo_orden(:p_codigo, :p_descripcion, :p_cantidad, :p_tipo, :p_orden_id)");
                        $stm_equipo->bindParam(':p_codigo', $codigo);
                        $stm_equipo->bindParam(':p_descripcion', $descripcionEq);
                        $stm_equipo->bindParam(':p_cantidad', $cantidad);
                        $stm_equipo->bindParam(':p_tipo', $tipo_entrega);
                        $stm_equipo->bindParam(':p_orden_id', $id);

                        if (!$stm_equipo->execute()) {
                            return "error";
                        }
                    }
                }

                // Paso 4: Editar los datos de la tabla orden
                $stmt = $this->conn->prepare("CALL sp_update_orden_sinEquipos(:p_orden_id, :p_orden_fecha, :p_orden_tecnico, :p_orden_asistente1, :p_orden_asistente2, :p_orden_tipoTrabajo, :p_orden_cliente, :p_orden_direccion, :p_orden_telefono, :p_orden_descripcion, :p_orden_vehiculo)");
                $stmt->bindParam(':p_orden_id', $id);
                $stmt->bindParam(':p_orden_fecha', $fecha);
                $stmt->bindParam(':p_orden_tecnico', $tecnico);
                $stmt->bindParam(':p_orden_asistente1', $asistente1);
                $stmt->bindParam(':p_orden_asistente2', $asistente2);
                $stmt->bindParam(':p_orden_tipoTrabajo', $tipoTrabajo);
                $stmt->bindParam(':p_orden_cliente', $cliente);
                $stmt->bindParam(':p_orden_direccion', $direccion);
                $stmt->bindParam(':p_orden_telefono', $telefono);
                $stmt->bindParam(':p_orden_descripcion', $descripcion);
                $stmt->bindParam(':p_orden_vehiculo', $vehiculo);

                if ($stmt->execute()) {
                    return "success";
                }else{
                    return "error";
                } 
            }
        } catch (PDOException $e) {
            $error = $e->getMessage();
            echo "<script>console.error(" . json_encode("Error: $error") . ");</script>";
            return ['status' => 'error', 'message' => $error];
        }
    }*/

    public function update_equipo_orden_suma($id, $fecha, $tecnico, $asistente1, $asistente2, $tipoTrabajo, $cliente, $direccion, $telefono, $descripcion, $vehiculo, $equipos_formulario, $equiposBD)
    {
        try {
            // Paso 2: Eliminar equipos de la orden
            $stmt_eliminar = $this->conn->prepare("CALL sp_delete_equipoOrden_noSuma(:p_orden_id)");
            $stmt_eliminar->bindValue(':p_orden_id', $id);
            $stmt_eliminar->execute();
            $stmt_eliminar->closeCursor();
            while ($stmt_eliminar->nextRowset()) {
                // vacía todos los resultados para evitar conflictos en la próxima llamada
            }

            // Paso 1: Devolver al inventario los equipos anteriores (solo tipo 1)
            if (is_array($equiposBD) && count($equiposBD) > 0) {
                foreach ($equiposBD as $equipo) {
                    if ($equipo['erd_tipo'] == 1) {
                        $codigo = $equipo['erd_codigo'];
                        $stock = $equipo['erd_cantidad'];

                        try {
                            // 🔄 Verificación agregada: comprobar existencia del código antes de llamar al SP
                            $verificacion = $this->conn->prepare("SELECT COUNT(*) AS cantidad FROM hc_electronicos WHERE elec_codigo = :codigo");
                            $verificacion->bindValue(':codigo', $codigo);
                            $verificacion->execute();
                            $resultado = $verificacion->fetch(PDO::FETCH_ASSOC);
                            $verificacion->closeCursor();

                            if ($resultado['cantidad'] == 0) {
                                error_log("Código omitido (no existe en inventario): " . $codigo);
                                continue; // saltar a siguiente equipo
                            }

                            // Si existe, sumar al inventario
                            $stmt_sumar = $this->conn->prepare("CALL sp_sumar_equipo_inventario(:p_codigo, :p_cantidad)");
                            $stmt_sumar->bindValue(':p_codigo', $codigo);
                            $stmt_sumar->bindValue(':p_cantidad', $stock);
                            $stmt_sumar->execute();
                            $stmt_sumar->closeCursor();
                            while ($stmt_sumar->nextRowset()) {
                                // limpiar resultados
                            }
                        } catch (PDOException $e) {
                            $errorMsg = $e->getMessage();

                            if (str_contains($errorMsg, 'Código no encontrado en inventario')) {
                                error_log("Código omitido (error): " . $codigo);
                                continue;
                            } else {
                                echo "<script>console.error(" . json_encode("Error grave al sumar inventario: $errorMsg") . ");</script>";
                                return ['status' => 'error', 'message' => $errorMsg];
                            }
                        }
                    }
                }
            }

            // Paso 3: Insertar los nuevos equipos en la orden
            if (is_array($equipos_formulario) && count($equipos_formulario) > 0) {
                $stmt_insertar = $this->conn->prepare("CALL sp_insert_equipo_orden(:p_codigo, :p_descripcion, :p_cantidad, :p_tipo, :p_orden_id)");

                foreach ($equipos_formulario as $equipo) {
                    $stmt_insertar->bindValue(':p_codigo', $equipo['codigo']);
                    $stmt_insertar->bindValue(':p_descripcion', $equipo['descripcion']);
                    $stmt_insertar->bindValue(':p_cantidad', $equipo['cantidad']);
                    $stmt_insertar->bindValue(':p_tipo', $equipo['tipo_entrega']);
                    $stmt_insertar->bindValue(':p_orden_id', $id);

                    if (!$stmt_insertar->execute()) {
                        $errorInfo = $stmt_insertar->errorInfo();
                        throw new Exception("Error al insertar equipo: " . $errorInfo[2]);
                    }

                    $stmt_insertar->closeCursor();
                    while ($stmt_insertar->nextRowset()) {
                        // limpiar resultados
                    }
                }
            }

            // Paso 4: Actualizar los datos de la orden
            $stmt = $this->conn->prepare("CALL sp_update_orden_sinEquipos(:p_orden_id, :p_orden_fecha, :p_orden_tecnico, :p_orden_asistente1, :p_orden_asistente2, :p_orden_tipoTrabajo, :p_orden_cliente, :p_orden_direccion, :p_orden_telefono, :p_orden_descripcion, :p_orden_vehiculo)");
            $stmt->bindParam(':p_orden_id', $id);
            $stmt->bindParam(':p_orden_fecha', $fecha);
            $stmt->bindParam(':p_orden_tecnico', $tecnico);
            $stmt->bindParam(':p_orden_asistente1', $asistente1);
            $stmt->bindParam(':p_orden_asistente2', $asistente2);
            $stmt->bindParam(':p_orden_tipoTrabajo', $tipoTrabajo);
            $stmt->bindParam(':p_orden_cliente', $cliente);
            $stmt->bindParam(':p_orden_direccion', $direccion);
            $stmt->bindParam(':p_orden_telefono', $telefono);
            $stmt->bindParam(':p_orden_descripcion', $descripcion);
            $stmt->bindParam(':p_orden_vehiculo', $vehiculo);

            if ($stmt->execute()) {
                return "success";
            } else {
                return "error al actualizar la orden";
            }
        } catch (PDOException $e) {
            $error = $e->getMessage();
            echo "<script>console.error(" . json_encode("Error: $error") . ");</script>";
            return ['status' => 'error', 'message' => $error];
        }
    }
}

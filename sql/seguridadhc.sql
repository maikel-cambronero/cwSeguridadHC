-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 24, 2025 at 08:55 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `seguridadhc`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_asigna_equipo` (IN `p_id` INT, IN `p_cantidad1` INT, IN `p_condicion` VARCHAR(50), IN `p_asigana` INT, IN `p_detalle` TEXT)   BEGIN
	DECLARE v_cat INT;
    DECLARE v_sub INT;
    DECLARE v_cantidad_actual INT;
    DECLARE v_resta INT;
    
	-- Obtener los valores actuales
    SELECT scat_cantidad, segd_catg_IDcategoria, segd_scat_IDsubcategoria
    INTO v_cantidad_actual, v_cat, v_sub
    FROM hc_seguridad
    WHERE segd_id = p_id;

    -- Calcular la nueva cantidad
    SET v_resta = v_cantidad_actual - p_cantidad1;

	-- Actualizar el registro existente
    UPDATE hc_seguridad
    SET scat_cantidad = v_resta
    WHERE segd_id = p_id;

	-- Insertar nuevo registro
    INSERT INTO hc_seguridad (
        scat_cantidad,
        segd_detalle,
        segd_condicion,
        segd_empl_IDempleado,
        segd_catg_IDcategoria,
        segd_scat_IDsubcategoria,
        segd_estado
    )
    VALUES (
        p_cantidad1,
        p_detalle,
        p_condicion,
        p_asigana,
        v_cat,
        v_sub,
        16
    );

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_contar_codigo` (`p_codigo` VARCHAR(50), OUT `p_cantidad` INT)   BEGIN
    SELECT COUNT(elec_id)
    INTO p_cantidad
    FROM hc_electronicos
    WHERE elec_codigo = p_codigo;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_delete_categoria` (IN `p_id` INT)   BEGIN
    DELETE FROM hc_categoria
    WHERE catg_id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_delete_coti` (IN `p_id` INT)   BEGIN
    -- Eliminar los equipos asociados a la cotización
    DELETE FROM hc_coti_equipo 
    WHERE cteq_coti_id = p_id;

    -- Eliminar la cotización principal
    DELETE FROM hc_cotizaciones 
    WHERE cot_id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_delete_Electronico` (IN `p_id` INT)   BEGIN
    DELETE FROM hc_electronico
    WHERE elec_id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_delete_equipo` (IN `p_id_equipo` INT)   BEGIN
    DELETE FROM hc_electronicos
    WHERE elec_id = p_id_equipo;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_delete_equipoOrden_noSuma` (IN `p_orden_id` INT)   BEGIN
    IF EXISTS (
        SELECT 1 FROM hc_equipos_orden WHERE erd_orden_id = p_orden_id
    ) THEN
        DELETE FROM hc_equipos_orden
        WHERE erd_orden_id = p_orden_id;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_delete_equipos_por_orden` (IN `p_orden_id` INT)   BEGIN
    DELETE FROM hc_equipos_orden WHERE hc_equipos_orden.erd_orden_id = p_orden_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_delete_Herramienta` (IN `p_id` INT)   DELETE FROM hc_campo
WHERE camp_id = p_id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_delete_marca` (IN `p_id` INT)   BEGIN
    DELETE FROM hc_marcas
    WHERE marc_id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_delete_orden` (IN `p_orden_id` INT)   BEGIN
    DELETE FROM hc_orden WHERE hc_orden.ord_id = p_orden_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_delete_proveedor` (IN `p_id` INT)   BEGIN
  UPDATE hc_proveedores SET
    activo = 22
  WHERE prov_id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_delete_reporte_oficial` (IN `p_reporte_id` INT)   BEGIN
    -- Eliminar el reporte
    DELETE FROM reporte_oficial
    WHERE reof_id = p_reporte_id;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_delete_Seguridad` (IN `p_id` INT)   DELETE FROM hc_seguridad
WHERE hc_seguridad.segd_id= p_id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_delete_subcategoria` (IN `p_id` INT)   BEGIN
    DELETE FROM hc_subcategoria
    WHERE scat_id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_delete_vehiculo` (IN `p_id` INT)   BEGIN
    UPDATE hc_vehiculos SET veh_estado = 22 WHERE veh_id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_editar_reporte_oficial` (IN `p_id` INT, IN `p_motivo` TEXT, IN `p_justi` TEXT, IN `p_empID` INT, IN `p_estado` INT, IN `p_nombre` VARCHAR(100))   BEGIN
    -- Actualizar el reporte
    UPDATE reporte_oficial
    SET 
        reof_motivo = p_motivo,
        reof_justificacion = p_justi,
        reof_bitacora = CONCAT('Editado por: ', p_nombre, ', a las: ', NOW())
    WHERE reof_id = p_id AND reof_emp_id = p_empID;

    -- Si se afectó alguna fila, actualizar el estado del oficial
    IF ROW_COUNT() > 0 THEN
        UPDATE hc_empleados
        SET emp_estado_supervision = p_estado
        WHERE emp_id = p_empID;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getElectronicosByEstado` (IN `p_estado` INT)   BEGIN
    SELECT *
    FROM v_electronicos_agrupados
    WHERE estado_promedio = p_estado;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_coti_codigo` (IN `p_codigo` VARCHAR(100))   BEGIN
SELECT *
FROM hc_cotizaciones
WHERE hc_cotizaciones.cot_codigo = p_codigo;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_coti_id` (IN `p_id` INT)   BEGIN
SELECT * 
FROM hc_cotizaciones
WHERE hc_cotizaciones.cot_id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_electronicosDesagrupados` (IN `p_codigo` VARCHAR(60))   SELECT * 
FROM hc_electronicos
WHERE hc_electronicos.elec_codigo = p_codigo$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_empleadoID` (IN `p_id` INT)   BEGIN

SELECT * 
FROM hc_empleados 
INNER JOIN hc_roles ON hc_empleados.emp_rol_id = hc_roles.rol_id
INNER JOIN hc_departamento ON hc_empleados.emp_dep_id = hc_departamento.dep_id
WHERE hc_empleados.emp_id = p_id;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_empleado_cedula` (IN `p_cedula` VARCHAR(25))   SELECT * FROM hc_empleados INNER JOIN hc_departamento ON hc_empleados.emp_dep_id = hc_departamento.dep_id WHERE hc_empleados.emp_cedula = p_cedula$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_equiposCoti` (IN `p_coti_id` INT)   BEGIN
SELECT *
FROM hc_coti_equipo
WHERE hc_coti_equipo.cteq_coti_id = p_coti_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_equipos_orden_id` (IN `p_orden_id` INT)   BEGIN
   
    SELECT *
    FROM seguridadhc.v_get_equipos_orden
    WHERE erd_orden_id = p_orden_id;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_equipos_orden_instalacion` (IN `p_orden_id` INT, OUT `p_tiene_instalacion` BOOLEAN)   BEGIN
    DECLARE total INT;

    SELECT COUNT(*) INTO total
    FROM seguridadhc.v_get_equipos_orden
    WHERE erd_orden_id = p_orden_id;

    SET p_tiene_instalacion = (total > 0);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_oficiales_agrupados` (IN `p_estado` INT)   BEGIN
    SELECT 
    	hc_empleados.emp_id,
        hc_empleados.emp_nombre,
        hc_empleados.emp_apellidos,
        hc_empleados.emp_cedula, 
        hc_empleados.emp_delta, 
        hc_empleados.emp_puesto, 
        hc_roles.rol_detalle
    FROM 
        hc_empleados
    INNER JOIN 
        hc_roles ON hc_empleados.emp_rol_id = hc_roles.rol_id
    WHERE 
        hc_empleados.emp_dep_id = 7 
        AND hc_empleados.emp_estado_supervision = p_estado;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_ordenCodigo` (IN `p_codigo` VARCHAR(80))   BEGIN
	SELECT *
    FROM hc_orden
    LEFT JOIN hc_equipos_orden
    ON hc_orden.ord_id = hc_equipos_orden.erd_orden_id
    INNER JOIN hc_vehiculos
    ON hc_orden.ord_vehiculo_id = hc_vehiculos.veh_id
    WHERE hc_orden.ord_codigo = p_codigo;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_reportes_oficiales` (IN `p_emp_id` INT)   BEGIN
    SELECT *
    FROM reporte_oficial
    INNER JOIN hc_empleados
        ON reporte_oficial.reof_emp_id = hc_empleados.emp_id
    WHERE reporte_oficial.reof_emp_id = p_emp_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_reportes_oficiales_agrupados` (IN `p_estado` INT)   BEGIN
   SELECT
    e.emp_id,
    e.emp_nombre,
    e.emp_apellidos,
    e.emp_cedula, 
    e.emp_delta,
    e.emp_puesto,
    e.emp_estado_supervision,
    MAX(r.reof_bitacora) AS ultimo_comentario
FROM 
    hc_empleados e
INNER JOIN reporte_oficial r
    ON r.reof_emp_id = e.emp_id
WHERE 
    e.emp_dep_id = 7 
    AND e.emp_estado_supervision = p_estado
GROUP BY 
    e.emp_id,
    e.emp_nombre,
    e.emp_apellidos,
    e.emp_cedula, 
    e.emp_delta,
    e.emp_puesto,
    e.emp_estado_supervision;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_categoria` (IN `p_detalle` VARCHAR(60), IN `p_estado_id` INT)   BEGIN
    INSERT INTO hc_categoria (
        catg_detalle,
        catg_est_idEstado
    ) VALUES (
        p_detalle,
        p_estado_id
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_colaborador` (IN `p_nombre` VARCHAR(60), IN `p_apellidos` VARCHAR(120), IN `p_cedula` VARCHAR(20), IN `p_telefono` VARCHAR(20), IN `p_correo` VARCHAR(50), IN `p_direccion` TEXT, IN `p_salario` DECIMAL(10,2), IN `p_fechaIngreso` DATE, IN `p_cuenta` TEXT, IN `p_codigo` VARCHAR(15), IN `p_foto` VARCHAR(255), IN `p_carnetAgente` DATE, IN `p_carnetArma` DATE, IN `p_testPsicologico` DATE, IN `p_huellas` DATE, IN `p_vacaciones` VARCHAR(25), IN `p_licencias` VARCHAR(255), IN `p_obd_id` INT, IN `p_rol_id` INT, IN `p_dep_id` INT, IN `p_delta` VARCHAR(100), IN `p_puesto` VARCHAR(150))   BEGIN
    INSERT INTO hc_empleados (
        emp_nombre,
        emp_apellidos,
        emp_cedula,
        emp_telefono,
        emp_correo,
        emp_direccion,
        emp_salario,
        emp_fechaIngreso,
        emp_cuenta,
        emp_codigo,
        emp_foto,
        emp_carnetAgente,
        emp_carnetArma,
        emp_testPsicologico,
        emp_huellas,
        emp_vacaciones,
        emp_licencias,
        emp_obd_id,
        emp_rol_id,
        emp_dep_id,
        emp_estado,
        emp_delta,
        emp_puesto
    ) VALUES (
        p_nombre,
        p_apellidos,
        p_cedula,
        p_telefono,
        p_correo,
        p_direccion,
        p_salario,
        p_fechaIngreso,
        p_cuenta,
        p_codigo,
        p_foto,
        p_carnetAgente,
        p_carnetArma,
        p_testPsicologico,
        p_huellas,
        p_vacaciones,
        p_licencias,
        p_obd_id,
        p_rol_id,
        p_dep_id,
        25, -- estado por defecto
        p_delta,
        p_puesto
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_cotizacion` (IN `p_codigo` VARCHAR(60), IN `p_vendor` VARCHAR(60), IN `p_cliente` VARCHAR(60), IN `p_telefono` VARCHAR(60), IN `p_fecha1` DATE, IN `p_fecha2` DATE, IN `p_subtotal` DECIMAL(10,2), IN `p_iva` DECIMAL(10,2), IN `p_descuento` DECIMAL(10,2), IN `p_total` DECIMAL(10,2))   BEGIN
    INSERT INTO hc_cotizaciones (
        cot_codigo, 
        cot_vendor, 
        cot_cliente, 
        cot_telefono, 
        cot_fecha1, 
        cot_fecha2, 
        cot_subtotal, 
        cot_iva, 
        cot_descuento, 
        cot_total
    ) VALUES (
        p_codigo, 
        p_vendor, 
        p_cliente, 
        p_telefono, 
        p_fecha1, 
        p_fecha2, 
        p_subtotal, 
        p_iva, 
        p_descuento, 
        p_total
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_Electronico` (IN `p_stock` INT, IN `p_detalle` TEXT, IN `p_marca` VARCHAR(100), IN `p_codigo` VARCHAR(100), IN `p_cantMin` INT, IN `p_precioDolar` DECIMAL(10,2), IN `p_porv_IDdolar` INT, IN `p_precio` DECIMAL(10,2), IN `p_porcentaje` DECIMAL(10,2), IN `p_precioTotal` DECIMAL(10,2), IN `p_prov_IDproveedor` INT, IN `p_catg_IDcategoria` INT, IN `p_scat_IDsubcategoria` INT)   BEGIN
	DECLARE 	v_estado INT;
    
    IF p_stock >= (p_cantMin + 8) THEN 
    	SET v_estado = 1; -- Suficiente
    ELSEIF p_stock >= p_cantMin THEN
    	SET v_estado = 2; -- Advertencia
    ELSE
    	SET v_estado = 3; -- Crítico
    END IF;
    
    INSERT INTO hc_electronico (
        elec_stock,
        elec_detalle,
        elec_marca,
        elec_codigo,
        elec_cantMin,
        elec_precioDolar,
        elec_porv_IDdolar,
        elec_precio,
        elec_porcentaje,
        elec_precioTotal,
        elec_prov_IDproveedor,
        elec_catg_IDcategoria,
        elec_scat_IDsubcategoria,
elec_est_IDestado
    )
    VALUES (
        p_stock,
        p_detalle,
        p_marca,
        p_codigo,
        p_cantMin,
        p_precioDolar,
        p_porv_IDdolar,
        p_precio,
        p_porcentaje,
        p_precioTotal,
        p_prov_IDproveedor,
        p_catg_IDcategoria,
        p_scat_IDsubcategoria,
        v_estado
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_equipo` (IN `p_stok` INT, IN `p_detalle` TEXT, IN `p_marca` VARCHAR(100), IN `p_codigo` VARCHAR(50), IN `p_cantMin` INT, IN `p_precio_prov` DECIMAL(10,2), IN `p_utilidad` DECIMAL(10,2), IN `p_total` DECIMAL(10,2), IN `p_prov_id` INT, IN `p_catg_id` INT, IN `p_scat_id` INT, IN `p_fact_consecutivo` INT, IN `p_buffer` INT)   BEGIN
    DECLARE v_optimo INT;
    DECLARE v_estado INT;
    DECLARE v_existente_id INT;
    DECLARE v_stock_actual INT;

    -- Verificar si ya existe un equipo con el mismo código y número de factura
    SELECT elec_id, elec_stok INTO v_existente_id, v_stock_actual
    FROM hc_electronicos
    WHERE elec_codigo = p_codigo AND elec_fact_consecutivo = p_fact_consecutivo
    LIMIT 1;

    IF v_existente_id IS NOT NULL THEN
        -- Ya existe: actualizar stock sumando
        UPDATE hc_electronicos
        SET elec_stok = v_stock_actual + p_stok
        WHERE elec_id = v_existente_id;
    ELSE
        -- No existe: calcular estado e insertar
        SET v_optimo = p_cantMin + p_buffer;

        IF p_stok > v_optimo THEN
            SET v_estado = 1; -- Stock mayor al óptimo
        ELSEIF p_stok <= v_optimo AND p_stok > p_cantMin THEN
            SET v_estado = 2; -- Stock entre límite y óptimo
        ELSE
            SET v_estado = 3; -- Stock por debajo del mínimo
        END IF;

        INSERT INTO hc_electronicos (
            elec_stok,
            elec_detalle,
            elec_marca,
            elec_codigo,
            elec_cantMin,
            elec_precio_prov,
            elec_utilidad,
            elec_total,
            elec_prov_id,
            elec_catg_id,
            elec_scat_id,
            elec_est_id,
            elec_fact_consecutivo,
            elec_buffer
        ) VALUES (
            p_stok,
            p_detalle,
            p_marca,
            p_codigo,
            p_cantMin,
            p_precio_prov,
            p_utilidad,
            p_total,
            p_prov_id,
            p_catg_id,
            p_scat_id,
            v_estado,
            p_fact_consecutivo,
            p_buffer
        );
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_equipoCotizacion` (IN `p_detalle` TEXT, IN `p_cantidad` INT, IN `p_precio` DECIMAL(10,2), IN `p_iva` INT, IN `p_descuento` INT, IN `p_subtotal` DECIMAL(10,2), IN `p_sub_iva` DECIMAL(10,2), IN `p_sub_desc` DECIMAL(10,2), IN `p_total_line` DECIMAL(10,2), IN `p_coti_id` INT)   BEGIN
    INSERT INTO hc_coti_equipo (
        cteq_detalle,
        cteq_can,
        cteq_precio,
        cteq_iva,
        cteq_descuento,
        cteq_subtotal,
        cteq_sub_iva,
        cteq_sub_desc,
        cteq_total_linea,
        cteq_coti_id
    ) VALUES (
        p_detalle,
        p_cantidad,
        p_precio,
        p_iva,
        p_descuento,
        p_subtotal,
        p_sub_iva,
        p_sub_desc,
        p_total_line,
        p_coti_id
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_equipo_orden` (IN `p_codigo` VARCHAR(150), IN `p_descripcion` VARCHAR(250), IN `p_cantidad` INT, IN `p_tipo` INT, IN `p_orden_id` INT)   BEGIN
DECLARE v_id_equipo INT;

    INSERT INTO hc_equipos_orden (
        erd_codigo,
        erd_descripcion,
        erd_cantidad,
        erd_tipo,
        erd_orden_id
    ) VALUES (
        p_codigo,
        p_descripcion,
        p_cantidad,
        p_tipo,
        p_orden_id
    );
    
    -- Si es de tipo Instalación (1), actualizar el stock
    IF p_tipo = 1 THEN
        -- Buscar el ID del equipo con el código dado
        SELECT MIN(elec_id)
        INTO v_id_equipo
        FROM hc_electronicos
        WHERE elec_codigo = p_codigo;

        -- Si encontró un equipo, actualizar el stock
        IF v_id_equipo IS NOT NULL THEN
            UPDATE hc_electronicos
            SET elec_stok = GREATEST(elec_stok - p_cantidad, 0)
            WHERE elec_id = v_id_equipo;
        END IF;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_Herramienta` (IN `p_cantidad` INT, IN `p_detalle` TEXT, IN `p_marca` VARCHAR(100), IN `p_idCategoria` INT, IN `p_idSubcategoria` INT, IN `p_idEmpleado` INT)   BEGIN
    INSERT INTO hc_campo (
        camp_cantidad,
        camp_detalle,
        camp_marca,
        camp_catg_idCategoria,
        camp_scat_idSubcategoria,
        camp_empo_idEmpleado
    ) VALUES (
        p_cantidad,
        p_detalle,
        p_marca,
        p_idCategoria,
        p_idSubcategoria,
        p_idEmpleado
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_marca` (IN `p_detalle` VARCHAR(60), IN `p_est_idEstado` INT)   BEGIN
    INSERT INTO hc_marcas (marc_detalle, marc_est_idEstado)
    VALUES (p_detalle, p_est_idEstado);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_orden` (IN `p_num_orden` VARCHAR(50), IN `p_fecha` DATE, IN `p_tecnico` VARCHAR(100), IN `p_asistente1` VARCHAR(100), IN `p_asistente2` VARCHAR(100), IN `p_tipo_trabajo` VARCHAR(100), IN `p_cliente` VARCHAR(200), IN `p_direccion` TEXT, IN `p_telefono` VARCHAR(20), IN `p_trabajo` TEXT, IN `p_vehiculo` INT)   BEGIN
    INSERT INTO hc_orden (
        ord_codigo, ord_fecha, ord_tecnico, ord_asistente1, ord_asistente2, ord_tipoTrabajo, ord_cliente, ord_direccion, ord_telefono, ord_descripcion, ord_vehiculo_id
    ) VALUES (
        p_num_orden, p_fecha, p_tecnico, p_asistente1, p_asistente2, p_tipo_trabajo, p_cliente, p_direccion, p_telefono, p_trabajo, p_vehiculo
    );
    
    -- Devolver el ID insertado
    SELECT LAST_INSERT_ID() AS orden_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_proveedor` (IN `p_empresa` VARCHAR(255), IN `p_identificacion` VARCHAR(50), IN `p_telefono` VARCHAR(50), IN `p_correo` VARCHAR(100), IN `p_direccion` TEXT, IN `p_contacto_nombre` VARCHAR(100), IN `p_contacto_telefono` VARCHAR(50), IN `p_contacto_correo` VARCHAR(100), IN `p_moneda_preferida` VARCHAR(60), IN `p_condiciones_pago` VARCHAR(100))   BEGIN
  INSERT INTO hc_proveedores (
    prov_empresa,
    prov_identificacion,
    prov_telefono,
    prov_correo,
    prov_direccion,
    prov_contacto_nombre,
    prov_contacto_telefono,
    prov_contacto_correo,
    prov_moneda_preferida,
    prov_condiciones_pago,
    activo,
    fecha_creacion
  ) VALUES (
    p_empresa,
    p_identificacion,
    p_telefono,
    p_correo,
    p_direccion,
    p_contacto_nombre,
    p_contacto_telefono,
    p_contacto_correo,
    p_moneda_preferida,
    p_condiciones_pago,
    21, -- Activo
    NOW()
  );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_reporteOficial` (IN `p_empID` INT, IN `p_motivo` TEXT, IN `p_justi` TEXT, IN `p_nombre` VARCHAR(100), IN `p_estado` INT)   BEGIN
    -- Intentar el INSERT
    INSERT INTO reporte_oficial (
        reof_motivo,
        reof_justificacion,
        reof_emp_id,
        reof_bitacora
    )
    VALUES (
        p_motivo,
        p_justi,
        p_empID,
        CONCAT('Insertado por: ', p_nombre, ', a las: ', NOW())
    );

    -- Si se ejecuta el INSERT, hacer el UPDATE
    IF ROW_COUNT() > 0 THEN
        UPDATE hc_empleados
        SET emp_estado_supervision = p_estado
        WHERE emp_id = p_empID;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_Seguridad` (IN `p_cantidad` INT, IN `p_detalle` TEXT, IN `p_condicion` VARCHAR(20), IN `p_IDempleado` INT, IN `p_IDcategoria` INT, IN `p_IDsubcategoria` INT)   BEGIN
    INSERT INTO hc_seguridad(
        scat_cantidad,
        segd_detalle,
        segd_condicion,
        segd_empl_IDempleado,
        segd_catg_IDcategoria,
        segd_scat_IDsubcategoria
    )
VALUES(
    p_cantidad,
    p_detalle,
    p_condicion,
    p_IDempleado,
    p_IDcategoria,
    p_IDsubcategoria
) ;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_subcategoria` (IN `p_detalle` VARCHAR(60), IN `p_categoriaPadre` INT, IN `p_estado` INT)   BEGIN
    INSERT INTO hc_subcategoria (
        scat_detalle,
        scat_catg_catgPadre,
        scat_est_idEstado
    ) VALUES (
        p_detalle,
        p_categoriaPadre,
        p_estado
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_usuario` (IN `p_cedula` VARCHAR(25), IN `p_acceso` INT, IN `p_username` VARCHAR(50), IN `p_password` VARCHAR(255))   BEGIN
    DECLARE v_emp_id INT DEFAULT NULL;

    -- Obtener ID del empleado según la cédula
    SELECT emp_id INTO v_emp_id
    FROM hc_empleados
    WHERE emp_cedula = p_cedula;

    -- Validar si el empleado existe
    IF v_emp_id IS NOT NULL THEN
        -- Insertar usuario relacionado al empleado
        INSERT INTO hc_usuarios (
            user_emp_id,
            user_name,
            user_password,
            user_nivelAcceso
        ) VALUES (
            v_emp_id,
            p_username,
            p_password,
            p_acceso
        );
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_vehiculo` (IN `p_placa` VARCHAR(20), IN `p_marca` VARCHAR(50), IN `p_modelo` VARCHAR(50), IN `p_anio` INT, IN `p_color` VARCHAR(30), IN `p_tipo` VARCHAR(30), IN `p_chasis` VARCHAR(50), IN `p_motor` VARCHAR(50), IN `p_kilometraje` INT, IN `p_vencimiento_seguro` DATE, IN `p_revision` DATE, IN `p_observaciones` TEXT)   BEGIN
    INSERT INTO hc_vehiculos (
        veh_placa, veh_marca, veh_modelo, veh_anio, veh_color,
        veh_tipo, veh_num_chasis, veh_num_motor, veh_kilometraje,
        veh_fecha_vencimiento_seguro, veh_fecha_revision, veh_observaciones, veh_fecha_registro, veh_estado
    ) VALUES (
        p_placa, p_marca, p_modelo, p_anio, p_color,
        p_tipo, p_chasis, p_motor, p_kilometraje,
        p_vencimiento_seguro, p_revision, p_observaciones, CURDATE(), 1
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_obtiene_usuario` (IN `p_user` VARCHAR(20))   SELECT *
FROM hc_usuarios
INNER JOIN hc_empleados ON hc_empleados.emp_id = hc_usuarios.user_emp_id
INNER JOIN hc_departamento ON hc_departamento.dep_id = hc_empleados.emp_dep_id
WHERE hc_usuarios.user_name = p_user AND hc_usuarios.user_estado = 31$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_sumar_cantidad_equipo` (IN `p_codigo` VARCHAR(50), IN `p_cantidad` INT)   BEGIN
    DECLARE v_id INT;

    -- Obtener el último ID (registro más reciente) con ese código
    SELECT MAX(id) INTO v_id
    FROM tu_tabla_equipos
    WHERE codigo_equipo = p_codigo;

    -- Si existe, actualiza la cantidad
    IF v_id IS NOT NULL THEN
        UPDATE hc_electronicos
        SET hc_electronicos.elec_stok = hc_electronicos.elec_stok + p_cantidad
        WHERE hc_electronicos.elec_id = v_id;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_sumar_cantidad_equipo_eliminar` (IN `p_codigo` VARCHAR(50), IN `p_cantidad` INT)   BEGIN
    DECLARE v_id INT;

    -- Obtener el último elec_id (registro más reciente) con ese código
    SELECT MAX(elec_id) INTO v_id
    FROM hc_electronicos
    WHERE elec_codigo = p_codigo;

    -- Si existe, actualiza la cantidad
    IF v_id IS NOT NULL THEN
        UPDATE hc_electronicos
        SET elec_stok = elec_stok + p_cantidad
        WHERE elec_id = v_id;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_sumar_equipo_inventario` (IN `p_codigo` VARCHAR(150), IN `p_cantidad` INT)   proc: BEGIN
    IF p_codigo IS NULL OR p_codigo = '-' THEN
        LEAVE proc;
    END IF;

    IF EXISTS (
        SELECT 1 FROM hc_electronicos WHERE elec_codigo = p_codigo
    ) THEN
        UPDATE hc_electronicos
        SET elec_stok = elec_stok + p_cantidad
        WHERE elec_codigo = p_codigo;
    ELSE
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Código no encontrado en inventario';
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_updateEmpleado` (IN `p_id` INT, IN `p_nombre` VARCHAR(60), IN `p_apellido` VARCHAR(120), IN `p_cedula` VARCHAR(20), IN `p_telefono` VARCHAR(20), IN `p_correo` VARCHAR(50), IN `p_fecha_ingreso` DATE, IN `p_direccion` TEXT, IN `p_cuenta` TEXT, IN `p_depto` INT, IN `p_rol` INT, IN `p_vacaciones` VARCHAR(25), IN `p_licencias` VARCHAR(255), IN `p_carnet_agente` DATE, IN `p_carnet_arma` DATE, IN `p_psicologico` DATE, IN `p_huellas` DATE, IN `p_foto` VARCHAR(255))   BEGIN
    UPDATE hc_empleados
    SET 
        emp_nombre = p_nombre,
        emp_apellidos = p_apellido,
        emp_cedula = p_cedula,
        emp_telefono = p_telefono,
        emp_correo = p_correo,
        emp_fechaIngreso = p_fecha_ingreso,
        emp_direccion = p_direccion,
        emp_cuenta = p_cuenta,
        emp_dep_id = p_depto,
        emp_rol_id = p_rol,
        emp_vacaciones = p_vacaciones,
        emp_licencias = p_licencias,
        emp_carnetAgente = p_carnet_agente,
        emp_carnetArma = p_carnet_arma,
        emp_testPsicologico = p_psicologico,
        emp_huellas = p_huellas,
        emp_foto = p_foto
    WHERE emp_id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_update_categoria` (IN `p_id` INT, IN `p_detalle` VARCHAR(60), IN `p_estado` INT)   BEGIN
    UPDATE hc_categoria
    SET 
        catg_detalle = p_detalle,
        catg_est_idEstado = p_estado
    WHERE catg_id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_update_colaborador_estado` (IN `p_id` INT, IN `p_situacion` INT, IN `p_usuario` VARCHAR(100), IN `p_observacion` TEXT)   BEGIN
    DECLARE last_id INT;

    -- Insertar observación
    INSERT INTO hc_observaciones_empleados (
        obe_observación,
        obe_fecha,
        obe_usuario
    ) VALUES (
        p_observacion,
        NOW(),
        p_usuario
    );

    -- Obtener el último ID insertado
    SET last_id = LAST_INSERT_ID();

    -- Actualizar empleado con nuevo estado y relación con la observación
    UPDATE hc_empleados
    SET emp_estado = p_situacion,
        emp_obd_id = last_id
    WHERE emp_id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_update_Electronico` (IN `p_id` INT, IN `p_stock` INT, IN `p_detalle` TEXT, IN `p_marca` VARCHAR(100), IN `p_codigo` VARCHAR(100), IN `p_cantMin` INT, IN `p_precioDolar` DECIMAL(10,2), IN `p_porv_IDdolar` INT, IN `p_precio` DECIMAL(10,2), IN `p_porcentaje` DECIMAL(5,2), IN `p_precioTotal` DECIMAL(10,2), IN `p_prov_IDproveedor` INT, IN `p_catg_IDcategoria` INT, IN `p_scat_IDsubcategoria` INT)   BEGIN
    UPDATE hc_electronico
    SET
        elec_stock = p_stock,
        elec_detalle = p_detalle,
        elec_marca = p_marca,
        elec_codigo = p_codigo,
        elec_cantMin = p_cantMin,
        elec_precioDolar = p_precioDolar,
        elec_porv_IDdolar = p_porv_IDdolar,
        elec_precio = p_precio,
        elec_porcentaje = p_porcentaje,
        elec_precioTotal = p_precioTotal,
        elec_prov_IDproveedor = p_prov_IDproveedor,
        elec_catg_IDcategoria = p_catg_IDcategoria,
        elec_scat_IDsubcategoria = p_scat_IDsubcategoria
    WHERE elec_id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_update_empleado` (IN `p_id` INT, IN `p_nombre` VARCHAR(60), IN `p_apellido` VARCHAR(120), IN `p_cedula` VARCHAR(20), IN `p_telefono` VARCHAR(20), IN `p_correo` VARCHAR(50), IN `p_fecha_ingreso` DATE, IN `p_direccion` TEXT, IN `p_cuenta` TEXT, IN `p_depto` INT, IN `p_rol` INT, IN `p_vacaciones` VARCHAR(25), IN `p_licencias` VARCHAR(255), IN `p_carnet_agente` DATE, IN `p_carnet_arma` DATE, IN `p_psicologico` DATE, IN `p_huellas` DATE, IN `p_foto` VARCHAR(255), IN `p_codigo` VARCHAR(60))   BEGIN
    UPDATE hc_empleados
    SET 
        emp_nombre = p_nombre,
        emp_apellidos = p_apellido,
        emp_cedula = p_cedula,
        emp_telefono = p_telefono,
        emp_correo = p_correo,
        emp_fechaIngreso = p_fecha_ingreso,
        emp_direccion = p_direccion,
        emp_cuenta = p_cuenta,
        emp_dep_id = p_depto,
        emp_rol_id = p_rol,
        emp_vacaciones = p_vacaciones,
        emp_licencias = p_licencias,
        emp_carnetAgente = p_carnet_agente,
        emp_carnetArma = p_carnet_arma,
        emp_testPsicologico = p_psicologico,
        emp_huellas = p_huellas,
        emp_foto = p_foto,
        emp_codigo = p_codigo
    WHERE emp_id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_update_equipo` (IN `p_id` INT, IN `p_detalle` TEXT, IN `p_codigo` VARCHAR(50), IN `p_stock` INT, IN `p_limite` INT, IN `p_buffer` INT, IN `p_marca` VARCHAR(100), IN `p_categoria` INT, IN `p_subcategoria` INT, IN `p_proveedor` INT, IN `p_consecutivo` INT, IN `p_compra` DECIMAL(10,2), IN `p_utilidad` DECIMAL(10,2), IN `p_venta` DECIMAL(10,2), IN `p_estado` INT)   BEGIN
    UPDATE hc_electronicos
    SET 
        elec_detalle = p_detalle,
        elec_codigo = p_codigo,
        elec_stok = p_stock,
        elec_cantMin = p_limite,
        elec_buffer = p_buffer,
        elec_marca = p_marca,
        elec_catg_id = p_categoria,
        elec_scat_id = p_subcategoria,
        elec_prov_id = p_proveedor,
        elec_fact_consecutivo = p_consecutivo,
        elec_precio_prov = p_compra,
        elec_utilidad = p_utilidad,
        elec_total = p_venta,
        elec_est_id = p_estado  -- Aquí estamos actualizando el estado
    WHERE elec_id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_update_equiposOrden_noSuma` (IN `p_orden_id` INT, IN `p_equipo_codigo` VARCHAR(100), IN `p_equipo_descripcion` TEXT, IN `p_equipo_cantidad` INT, IN `p_equipo_tipoEntrega` INT)   BEGIN
    -- Insertar nuevo equipo
    INSERT INTO hc_equipos_orden (
        erd_orden_id,
        erd_codigo,
        erd_descripcion,
        erd_cantidad,
        erd_tipo
    )
    VALUES (
        p_orden_id,
        p_equipo_codigo,
        p_equipo_descripcion,
        p_equipo_cantidad,
        p_equipo_tipoEntrega
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_update_estado_usuario` (IN `p_id` INT, IN `p_estado` INT)   BEGIN
    UPDATE hc_usuarios
    SET hc_usuarios.user_estado = p_estado
    WHERE hc_usuarios.user_id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_update_Herramienta` (IN `p_id` INT, IN `p_cantidad` INT, IN `p_detalle` TEXT, IN `p_marca` VARCHAR(100), IN `p_catg_idCategoria` INT, IN `p_scat_idSubcategoria` INT, IN `p_empo_idEmpleado` INT)   BEGIN
    UPDATE hc_campo
    SET
        camp_cantidad = p_cantidad,
        camp_detalle = p_detalle,
        camp_marca = p_marca,
        camp_catg_idCategoria = p_catg_idCategoria,
        camp_scat_idSubcategoria = p_scat_idSubcategoria,
        camp_empo_idEmpleado = p_empo_idEmpleado
    WHERE camp_id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_update_marca` (IN `p_id` INT, IN `p_detalle` VARCHAR(60), IN `p_est_idEstado` INT)   BEGIN
    UPDATE hc_marcas
    SET
        marc_detalle = p_detalle,
        marc_est_idEstado = p_est_idEstado
    WHERE
        marc_id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_update_orden_sinEquipos` (IN `p_orden_id` INT, IN `p_orden_fecha` DATE, IN `p_orden_tecnico` VARCHAR(100), IN `p_orden_asistente1` VARCHAR(100), IN `p_orden_asistente2` VARCHAR(100), IN `p_orden_tipoTrabajo` INT, IN `p_orden_cliente` VARCHAR(100), IN `p_orden_direccion` TEXT, IN `p_orden_telefono` VARCHAR(20), IN `p_orden_descripcion` TEXT, IN `p_orden_vehiculo` INT)   BEGIN
    UPDATE hc_orden
    SET 
        ord_fecha = p_orden_fecha,
        ord_tecnico = p_orden_tecnico,
        ord_asistente1 = p_orden_asistente1,
        ord_asistente2 = p_orden_asistente2,
        ord_tipoTrabajo = p_orden_tipoTrabajo,
        ord_cliente = p_orden_cliente,
        ord_direccion = p_orden_direccion,
        ord_telefono = p_orden_telefono,
        ord_descripcion = p_orden_descripcion,
        ord_vehiculo_id = p_orden_vehiculo
    WHERE ord_id = p_orden_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_update_password` (IN `p_id` INT, IN `p_pass` VARCHAR(255))   BEGIN
    UPDATE hc_usuarios
    SET user_password = p_pass
    WHERE user_id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_update_proveedor` (IN `p_id` INT, IN `p_empresa` VARCHAR(255), IN `p_identificacion` VARCHAR(50), IN `p_telefono` VARCHAR(50), IN `p_correo` VARCHAR(100), IN `p_direccion` TEXT, IN `p_contacto_nombre` VARCHAR(100), IN `p_contacto_telefono` VARCHAR(50), IN `p_contacto_correo` VARCHAR(100), IN `p_moneda_preferida` VARCHAR(60), IN `p_condiciones_pago` VARCHAR(100))   BEGIN
  UPDATE hc_proveedores SET
    prov_empresa = p_empresa,
    prov_identificacion = p_identificacion,
    prov_telefono = p_telefono,
    prov_correo = p_correo,
    prov_direccion = p_direccion,
    prov_contacto_nombre = p_contacto_nombre,
    prov_contacto_telefono = p_contacto_telefono,
    prov_contacto_correo = p_contacto_correo,
    prov_moneda_preferida = p_moneda_preferida,
    prov_condiciones_pago = p_condiciones_pago
  WHERE prov_id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_update_Seguridad` (IN `p_id` INT, IN `p_cantidad` INT, IN `p_IDempleado` INT, IN `p_IDcategoria` INT, IN `p_IDsubcategoria` INT, IN `p_detalle` TEXT, IN `p_condicion` VARCHAR(20))   BEGIN
    UPDATE hc_seguridad
    SET
        scat_cantidad = p_cantidad,
        segd_detalle = p_detalle,
        segd_condicion = p_condicion,
        segd_empl_IDempleado = p_IDempleado,
        segd_catg_IDcategoria = p_IDcategoria,
        segd_scat_IDsubcategoria = p_IDsubcategoria
    WHERE segd_id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_update_subcategoria` (IN `p_id` INT, IN `p_detalle` VARCHAR(60), IN `p_catg_catgPadre` INT, IN `p_est_idEstado` INT)   BEGIN
    UPDATE hc_subcategoria
    SET 
        scat_detalle = p_detalle,
        scat_catg_catgPadre = p_catg_catgPadre,
        scat_est_idEstado = p_est_idEstado
    WHERE 
        scat_id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_update_usuario_acceso` (IN `p_id` INT, IN `p_usuario` VARCHAR(150), IN `p_acceso` INT, IN `p_observacion` TEXT)   BEGIN
    DECLARE last_id INT;

    -- Insertar observación
    INSERT INTO hc_observaciones_empleados (
        obe_observación,
        obe_fecha,
        obe_usuario
    ) VALUES (
        p_observacion,
        NOW(),
        p_usuario
    );

    -- Obtener el último ID insertado
    SET last_id = LAST_INSERT_ID();

    -- Actualizar empleado con nuevo estado y relación con la observación
    UPDATE hc_usuarios
    SET hc_usuarios.user_nivelAcceso = p_acceso,
        hc_usuarios.user_observacion = last_id
    WHERE hc_usuarios.user_id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_update_vehiculo` (IN `p_id` INT, IN `p_placa` VARCHAR(20), IN `p_marca` VARCHAR(50), IN `p_modelo` VARCHAR(50), IN `p_anio` INT, IN `p_tipo` VARCHAR(30), IN `p_chasis` VARCHAR(50), IN `p_motor` VARCHAR(50), IN `p_kilometraje` INT, IN `p_vencimiento_seguro` DATE, IN `p_revision` DATE, IN `p_observaciones` TEXT)   BEGIN
    UPDATE hc_vehiculos SET
        veh_placa = p_placa,
        veh_marca = p_marca,
        veh_modelo = p_modelo,
        veh_anio = p_anio,
        veh_tipo = p_tipo,
        veh_num_chasis = p_chasis,
        veh_num_motor = p_motor,
        veh_kilometraje = p_kilometraje,
        veh_fecha_vencimiento_seguro = p_vencimiento_seguro,
        veh_fecha_revision = p_revision,
        veh_observaciones = p_observaciones
    WHERE veh_id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_verificar_usuario` (IN `p_usuario` VARCHAR(20), IN `p_clave` VARCHAR(20))   BEGIN
    SELECT COUNT(*)
    FROM hc_usuarios
    WHERE user_name = p_usuario AND user_password = p_clave;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `hc_acseso`
--

CREATE TABLE `hc_acseso` (
  `acs_id` int NOT NULL,
  `acs_nombre` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `acs_detalle` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hc_acseso`
--

INSERT INTO `hc_acseso` (`acs_id`, `acs_nombre`, `acs_detalle`) VALUES
(1, 'SuperAdmin', 'El usuario contiene acceso total al sistema'),
(2, 'Administrador', 'Gestión de datos generales y usuarios'),
(3, 'Supervisor', 'Acceso a reportes y demás'),
(4, 'Técnico Instalaciones', 'El usuario tiene acceso a lista de precios y vista de inventario, así como solicitar compras de equipos, además de realizar cotizaciones con precios especiales'),
(5, 'Asistente Instalaciones', 'El usuario tiene acceso a la vista de inventario, precios y cotizaciones con precios bases'),
(6, 'Proveduria', 'Tiene acceso completo a los inventarios');

-- --------------------------------------------------------

--
-- Table structure for table `hc_campo`
--

CREATE TABLE `hc_campo` (
  `camp_id` int NOT NULL,
  `camp_cantidad` int NOT NULL,
  `camp_detalle` text COLLATE utf8mb4_general_ci NOT NULL,
  `camp_marca` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `camp_catg_idCategoria` int NOT NULL,
  `camp_scat_idSubcategoria` int NOT NULL,
  `camp_empo_idEmpleado` int DEFAULT NULL,
  `camp_estado` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hc_campo`
--

INSERT INTO `hc_campo` (`camp_id`, `camp_cantidad`, `camp_detalle`, `camp_marca`, `camp_catg_idCategoria`, `camp_scat_idSubcategoria`, `camp_empo_idEmpleado`, `camp_estado`) VALUES
(7, 3, 'Probador de Red', 'Dahua', 4, 5, 0, 33),
(9, 2, 'Destornillador Plano', 'Inco', 4, 5, 21, 13),
(10, 2, 'Alicate de punta', 'Inco', 4, 5, 21, 13);

-- --------------------------------------------------------

--
-- Table structure for table `hc_categoria`
--

CREATE TABLE `hc_categoria` (
  `catg_id` int NOT NULL,
  `catg_detalle` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `catg_est_idEstado` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hc_categoria`
--

INSERT INTO `hc_categoria` (`catg_id`, `catg_detalle`, `catg_est_idEstado`) VALUES
(1, 'Alarma', 7),
(2, 'Grabación', 7),
(3, 'Redes', 7),
(4, 'Herramientas', 10),
(5, 'Uniformes', 14),
(6, 'Iluminación', 14),
(7, 'Protección', 14),
(18, 'Alimentación', 7),
(19, 'Rotulos', 7);

-- --------------------------------------------------------

--
-- Table structure for table `hc_cotizaciones`
--

CREATE TABLE `hc_cotizaciones` (
  `cot_id` int NOT NULL,
  `cot_codigo` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `cot_vendor` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `cot_cliente` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `cot_telefono` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `cot_fecha1` date NOT NULL,
  `cot_fecha2` date NOT NULL,
  `cot_subtotal` decimal(10,2) NOT NULL,
  `cot_iva` decimal(10,2) DEFAULT NULL,
  `cot_descuento` decimal(10,2) DEFAULT NULL,
  `cot_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hc_cotizaciones`
--

INSERT INTO `hc_cotizaciones` (`cot_id`, `cot_codigo`, `cot_vendor`, `cot_cliente`, `cot_telefono`, `cot_fecha1`, `cot_fecha2`, `cot_subtotal`, `cot_iva`, `cot_descuento`, `cot_total`) VALUES
(14, 'COT-FS-2025-0002', 'Maikel', 'Flor Azofeifa', '8930 - 3866', '2025-07-21', '2025-08-05', 188371.23, 24488.26, 0.00, 212859.49);

-- --------------------------------------------------------

--
-- Table structure for table `hc_coti_equipo`
--

CREATE TABLE `hc_coti_equipo` (
  `cteq_id` int NOT NULL,
  `cteq_detalle` text COLLATE utf8mb4_general_ci NOT NULL,
  `cteq_can` int NOT NULL,
  `cteq_precio` decimal(10,0) NOT NULL,
  `cteq_iva` int DEFAULT NULL,
  `cteq_descuento` int DEFAULT NULL,
  `cteq_subtotal` decimal(10,2) NOT NULL,
  `cteq_sub_iva` decimal(10,2) DEFAULT NULL,
  `cteq_sub_desc` decimal(10,2) DEFAULT NULL,
  `cteq_total_linea` decimal(10,2) NOT NULL,
  `cteq_coti_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hc_coti_equipo`
--

INSERT INTO `hc_coti_equipo` (`cteq_id`, `cteq_detalle`, `cteq_can`, `cteq_precio`, `cteq_iva`, `cteq_descuento`, `cteq_subtotal`, `cteq_sub_iva`, `cteq_sub_desc`, `cteq_total_linea`, `cteq_coti_id`) VALUES
(13, 'Cámara IP Dual 10MP 360°', 1, 49500, 13, 0, 49500.00, 6435.00, 0.00, 55935.00, 14),
(14, 'Cámara Cruiser 4MP Full Color', 1, 45524, 13, 0, 45523.77, 5918.09, 0.00, 51441.86, 14),
(15, 'Caja Plexo 4x4', 2, 1500, 13, 0, 3000.00, 390.00, 0.00, 3390.00, 14),
(16, 'MicroSD 128GB', 2, 7674, 13, 0, 15347.46, 1995.17, 0.00, 17342.63, 14),
(17, 'Actualización de sistema de alarma', 1, 10000, 13, 0, 10000.00, 1300.00, 0.00, 11300.00, 14),
(18, 'Mano de obra', 1, 65000, 13, 0, 65000.00, 8450.00, 0.00, 73450.00, 14);

-- --------------------------------------------------------

--
-- Table structure for table `hc_departamento`
--

CREATE TABLE `hc_departamento` (
  `dep_id` int NOT NULL,
  `dep_detalle` varchar(80) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hc_departamento`
--

INSERT INTO `hc_departamento` (`dep_id`, `dep_detalle`) VALUES
(5, 'Gerencia'),
(6, 'Instalaciones'),
(7, 'Seguridad'),
(8, 'Monitoreo');

-- --------------------------------------------------------

--
-- Table structure for table `hc_electronico`
--

CREATE TABLE `hc_electronico` (
  `elec_id` int NOT NULL,
  `elec_stock` int NOT NULL,
  `elec_detalle` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `elec_marca` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `elec_codigo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `elec_cantMin` int NOT NULL,
  `elec_precioDolar` decimal(10,2) NOT NULL,
  `elec_porv_IDdolar` int NOT NULL,
  `elec_precio` decimal(10,2) NOT NULL,
  `elec_porcentaje` decimal(10,2) NOT NULL,
  `elec_precioTotal` decimal(10,2) NOT NULL,
  `elec_prov_IDproveedor` int NOT NULL,
  `elec_catg_IDcategoria` int NOT NULL,
  `elec_scat_IDsubcategoria` int NOT NULL,
  `elec_est_IDestado` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hc_electronico`
--

INSERT INTO `hc_electronico` (`elec_id`, `elec_stock`, `elec_detalle`, `elec_marca`, `elec_codigo`, `elec_cantMin`, `elec_precioDolar`, `elec_porv_IDdolar`, `elec_precio`, `elec_porcentaje`, `elec_precioTotal`, `elec_prov_IDproveedor`, `elec_catg_IDcategoria`, `elec_scat_IDsubcategoria`, `elec_est_IDestado`) VALUES
(5, 5, '<p>Switch 5 puertos, RB260GS</p>', 'MikroTik', 'CSS06-5G-1S', 2, 40.22, 3, 20319.95, 80.00, 36575.91, 3, 3, 4, 1),
(7, 2, '<p>Videograbador de 8 canales. POE</p>', 'Dahua', 'DH-NVR1108HS-8P-S3/H', 1, 94.25, 4, 47760.25, 50.00, 71640.37, 4, 3, 3, 1),
(8, 3, '<p>Cámara IP Bullet 4MP Full-Color 30MT</p>', 'Dahua', 'DH-IPC-HFW2449S-S-IL', 5, 58.63, 3, 29621.05, 50.00, 44431.57, 3, 3, 2, 1),
(11, 1, '<p>Cámara Analógica 4MP Bullet 30Mt</p>', 'Dahua', 'DH-HAC-1239TL-A-LED', 1, 40.00, 4, 20269.60, 50.00, 30404.40, 4, 2, 2, 1),
(12, 12, '<p>Fuente de Poder de 2 AMP</p>', 'Teklink', 'PS-12VDC2AMP', 15, 20.00, 4, 10134.80, 80.00, 18242.64, 4, 1, 1, 3),
(14, 10, '<p>Fuente de Poder 6 AMP</p>', 'Teklink', 'PS-12VDC6MP', 10, 25.00, 3, 12630.50, 80.00, 22734.90, 3, 1, 2, 2),
(15, 12, '<p>prueba</p>', 'MikroTik', '56+5562625', 5, 10.25, 1, 5335.13, 80.00, 9603.23, 1, 1, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `hc_electronicos`
--

CREATE TABLE `hc_electronicos` (
  `elec_id` int NOT NULL,
  `elec_stok` int NOT NULL,
  `elec_detalle` text COLLATE utf8mb4_general_ci NOT NULL,
  `elec_marca` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `elec_codigo` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `elec_cantMin` int NOT NULL,
  `elec_precio_prov` decimal(10,2) NOT NULL,
  `elec_utilidad` decimal(10,2) NOT NULL,
  `elec_total` decimal(10,2) NOT NULL,
  `elec_prov_id` int NOT NULL,
  `elec_catg_id` int NOT NULL,
  `elec_scat_id` int NOT NULL,
  `elec_est_id` int NOT NULL,
  `elec_fact_consecutivo` int NOT NULL,
  `elec_buffer` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hc_electronicos`
--

INSERT INTO `hc_electronicos` (`elec_id`, `elec_stok`, `elec_detalle`, `elec_marca`, `elec_codigo`, `elec_cantMin`, `elec_precio_prov`, `elec_utilidad`, `elec_total`, `elec_prov_id`, `elec_catg_id`, `elec_scat_id`, `elec_est_id`, `elec_fact_consecutivo`, `elec_buffer`) VALUES
(12, 1, 'Cámara IP 4MP WizSense Bullet', 'Dahua', 'DH-IPC-HFW2449S-S-IL', 3, 27532.97, 45.00, 39922.81, 4, 2, 2, 3, 1232, 2),
(13, 6, 'Cámara HAC 2MP HDCVI Bullet', 'Dahua', 'DH-HAC-HFW1239TLMN-IL-A', 3, 10749.90, 60.00, 17199.84, 4, 2, 2, 1, 7895, 1),
(14, 1, 'Cámara IP 4MP WizSense Domo', 'Dahua', 'DH-IPC-HDW2449Y-S-IL', 3, 30225.53, 38.00, 41711.23, 4, 2, 2, 1, 7452, 1),
(15, 10, 'Cámara HAC 2MP HDCVI Bullet Básica', 'Dahua', 'DH-HAC-B1A21N', 1, 6412.13, 60.00, 10259.41, 4, 2, 2, 1, 5647, 1),
(16, 2, 'Cámara HAC 5MP Domo Básica ', 'Dahua', 'DH-HAC-T2A51N', 1, 7500.00, 64.00, 12300.00, 4, 2, 2, 2, 7891, 1),
(17, 6, 'Cámara HAC 2MP Domo Básica', 'Dahua', 'DH-HAC-T1A21N', 1, 5666.65, 70.00, 9633.30, 4, 2, 2, 1, 9852, 1),
(18, 4, 'Cámara HAC 2MP HDCVI Básica', 'Dahua', 'DH-HAC-T2A21N', 1, 7163.22, 62.00, 11604.42, 4, 2, 2, 1, 1237, 1),
(19, 2, 'Cámara HAC 2MP Full Color Bullet', 'Dahua', 'HD-HAC-HFW1239TN-A-LED', 2, 8086.52, 65.00, 13342.76, 4, 2, 2, 3, 1235, 2),
(20, 3, 'Cámara IP 4MP Full Color Bullet', 'Dahua', 'DH-IPC-HFW1439S1-A-LED', 4, 22315.45, 35.00, 30125.86, 4, 2, 2, 1, 1365, 2),
(21, 4, 'Cámara HAC 2MP Full Color Domo', 'Dahua', 'DH-HAC-HDW1239TN-A-LED', 1, 14159.45, 61.00, 22796.71, 4, 2, 2, 1, 2359, 1),
(22, 1, 'Cámara HAC 2MP Full Color Domo', 'Dahua', 'DH-HAC-HDW1209TLQN-LED', 1, 12478.35, 55.00, 19341.44, 4, 2, 2, 3, 235878, 1),
(23, 3, 'Cámara Dual 10MP 360°', 'Imou', 'IPC-S7XN-10M0WED', 2, 33375.92, 38.00, 46058.77, 4, 2, 2, 2, 1254, 2),
(24, 2, 'Cámara Triple 11MP 360°', 'Imou', 'IPC-S7UN-11M0WED', 3, 41471.39, 31.00, 54327.52, 4, 2, 2, 3, 6059, 1),
(25, 4, 'Cámara Cruiser 4MP Full Color', 'Imou', 'IPC-S4FN', 1, 26568.12, 45.00, 38523.77, 4, 2, 2, 1, 1256, 1),
(26, 2, 'Cámara Smart 2MP Domo', 'Dahua', 'DH-IPC-HDW2841T-S', 1, 14658.25, 54.00, 22573.71, 4, 2, 2, 2, 6548974, 1),
(27, 1, 'Cámara HAC 2MP HDCVI Domo ', 'Dahua', 'DH-HAC-HDW2501TN-Z-A', 1, 12257.00, 60.00, 19611.20, 4, 2, 2, 3, 6546848, 1),
(28, 1, 'Cámara IP 4MP WizMind Domo', 'Dahua', 'DH-IPC-HDBW5441F-AS-E2', 1, 107661.33, 18.00, 127040.37, 4, 2, 2, 3, 3541869, 1),
(29, 3, 'Cámara HAC 2MP HDCVI Domo', 'Dahua', 'DH-HAC-HFW1200TLN-A', 1, 10749.90, 60.00, 17199.84, 4, 2, 2, 1, 3546, 1),
(30, 9, 'Cámara IP 4MP WizSense SMD Bullet', 'Dahua', 'DH-IPC-HFW3441T-AS-P', 1, 70008.78, 30.00, 91011.41, 4, 2, 2, 1, 6651561, 1),
(31, 3, 'MicroSD 256GB', 'Imou', 'ST2-256-S1', 2, 10415.07, 50.00, 15622.60, 4, 2, 2, 1, 6548754, 1),
(32, 5, 'MicroSD 128GB', 'Adata', 'ASUDX128GUI3V30SHA2-RA1', 2, 4487.56, 71.00, 7673.73, 3, 2, 2, 1, 244276, 1),
(33, 4, 'MicroSD 64GB', 'Dahua', 'DHI-TF-C100/64GB', 2, 2054.61, 120.00, 4520.14, 4, 2, 2, 1, 7865564, 1),
(34, 2, 'Cámara 3MP Ranger ', 'Imou', 'IPC-A32EN-L', 1, 15133.06, 55.00, 23456.24, 4, 2, 2, 2, 78351, 1),
(35, 3, 'Cámara 5MP Bombillo', 'Imou', 'IPC-S6DN-50M0WED', 1, 16741.23, 54.00, 25781.49, 4, 2, 2, 1, 6846, 1),
(36, 3, 'Kit de antenas 3km', 'Wi-Tek', 'WI-CPE511H-KIT', 2, 41319.20, 35.00, 55780.92, 4, 3, 16, 2, 78461, 1),
(37, 5, 'MicroSD 128GB ADATA', 'ADATA', '3602035', 3, 4487.56, 65.00, 7404.47, 3, 2, 18, 1, 244276, 1),
(38, 6, 'MicroSD 64GB', 'Dahua', 'TFC10064GB', 1, 2052.05, 120.00, 4514.51, 4, 2, 18, 1, 654656, 1),
(39, 8, 'Caja Plexo 4x4', 'dLux', 'DLX10010070S', 4, 1200.00, 110.00, 2520.00, 2, 2, 19, 1, 171746, 1),
(40, 10, 'Fuente de Poder 6MAP', 'Teklink', 'PS-12VDC6AMP', 4, 4688.44, 90.00, 8908.04, 1, 18, 20, 1, 359966, 1),
(41, 13, 'Fuente de Poder 2AMP', 'Teklink', 'PS-12VDC2AMP', 4, 1763.22, 150.00, 4408.05, 1, 18, 20, 1, 359966, 1),
(42, 1, 'Grabador XVR 8CH', 'Dahua', 'DH-XVR1B08-I', 1, 20773.88, 45.00, 30122.13, 2, 2, 21, 3, 3546, 1),
(43, 0, 'Grabador NVR 8 CH 4K POE', 'Dahua', 'DHI-NVR5208-8P-EI', 1, 172777.88, 11.00, 191783.45, 2, 2, 21, 3, 6548, 1),
(44, 1, 'Grabador NVR 16 CH  POE', 'Dahua', 'DHI-NVR4216-16P-4KS2/L', 1, 241939.70, 8.00, 261294.88, 2, 2, 21, 3, 87545, 1),
(45, 2, 'Grabador NVR 16 CH  POE', 'Dahua', 'DHI-NVR4216-16P-4KS2/L', 1, 241939.70, 8.00, 261294.88, 2, 2, 21, 2, 9846, 1),
(47, 5, 'Bandeja 1U', 'dLux', 'DLXSHELF', 2, 5760.02, 70.00, 9792.03, 2, 2, 19, 1, 173038, 1),
(48, 9, 'Inyector POE', 'dLux', 'LTK-POECABLE LANTEK', 4, 1821.48, 95.00, 3551.89, 2, 2, 19, 1, 12491, 2),
(49, 1, 'IR Panoramic Fisheye', 'Dahua', 'DS-2CD63650E-IVS', 1, 1.00, 1.00, 1.01, 2, 2, 2, 3, 5661, 1),
(50, 2, 'PTZ 4MP', 'Dahua', 'SD49425GBHNR', 1, 188991.64, 12.00, 211670.64, 2, 2, 2, 2, 698513, 1),
(51, 8, 'Camara IP Crusier 2 5MP', 'Imou', 'IPC-GS7EN-5MOWE', 2, 25500.00, 60.00, 40800.00, 2, 2, 2, 1, 46654, 1),
(52, 3, 'Cámara IP Dual 10MP 360°', 'Imou', 'IPC-S7XN-10MOWED', 3, 33334.48, 35.00, 45001.55, 2, 2, 2, 3, 2424555, 1),
(53, 2, 'Cámara IP Triple 11MP 360° ', 'Imou', ' IPC-S7UN-11M0', 1, 41471.39, 33.00, 55156.95, 1, 2, 2, 2, 394578, 1),
(54, 2, 'Cámara IP Renger 2 3MP', 'Imou', 'IPCK2EN3H1W', 1, 15114.26, 55.00, 23427.10, 2, 2, 2, 2, 5562, 1),
(55, 4, 'Cámara IP Bombillo 3MP', 'Imou', 'IPCS6DN3M0WEB', 1, 16720.44, 54.00, 25749.48, 2, 2, 2, 1, 6557, 1),
(56, 2, 'Video Balun', 'Dahua', 'DH-PFM800-E', 9, 972.83, 80.00, 1751.09, 1, 2, 19, 3, 397341, 1),
(57, 2, 'UPS 500W', 'Smarbit', 'SBNB1000', 1, 23428.88, 45.00, 33971.88, 2, 18, 22, 2, 68456, 1),
(58, 0, 'UPS 300W', 'Smarbit', 'SBNB600', 1, 17267.65, 54.00, 26592.18, 2, 18, 22, 3, 5646888, 1),
(59, 6, 'Panel de alarma SP7000+', 'Paradox', 'SP7000+', 2, 35562.10, 38.50, 49253.51, 2, 1, 23, 1, 889955, 1),
(60, 3, 'Panel de alarma SP5500+', 'Paradox', 'SP5500+', 2, 26568.12, 42.00, 37726.73, 2, 1, 23, 1, 165767, 1),
(61, 1, 'Panel de alarma MG5050+', 'Paradox', 'MG5050+', 1, 35536.70, 38.00, 49040.65, 2, 1, 23, 3, 888888, 1),
(62, 9, 'Modulo de Transmisión cableado', 'Paradox', 'IP150+', 4, 23877.41, 45.00, 34622.24, 2, 1, 27, 1, 782000565, 1),
(63, 4, 'Modulo de Transmisión Inalámbrico ', 'Paradox', 'IP180+', 4, 20829.23, 45.00, 30202.38, 2, 1, 27, 3, 54613156, 1),
(64, 9, 'Sensor de movimiento antimascotas cableado', 'Paradox', 'NV5', 4, 5054.90, 70.00, 8593.33, 2, 1, 28, 1, 8896526, 1),
(65, 4, 'Sensor de movimiento digital para interiores, de alta seguridad, antimascotas', 'Paradox', 'DG75+', 1, 9017.53, 60.00, 14428.05, 2, 1, 28, 1, 88965, 1),
(66, 10, 'Sensor movimiento inalámbrico semi exterior antimascotas 40kg', 'Paradox', 'PMD75N', 3, 20631.10, 45.00, 29915.09, 2, 1, 28, 1, 5461657, 1),
(67, 4, 'Transceptor inalámbrico para paneles', 'Paradox', 'RTX3', 2, 23061.23, 45.00, 33438.78, 2, 1, 25, 1, 171616, 1),
(68, 7, 'Botón de emergencia inalámbrico', 'Paradox', 'REM101', 2, 8890.53, 55.00, 13780.32, 2, 1, 30, 1, 894555, 1),
(69, 4, 'Control remoto de 4 botones para alarma Paradox ', 'Paradox', 'REM1', 2, 9500.00, 60.00, 15200.00, 2, 1, 29, 1, 64616566, 1),
(70, 5, 'Teclado de 10 zonas 1 partición Paradox', 'Paradox', 'K636', 3, 10856.60, 60.00, 17370.56, 2, 1, 32, 1, 885214557, 1),
(71, 3, 'Teclado de 10 zonas 2 particiones Paradox', 'Paradox', 'K10V', 1, 12578.82, 58.00, 19874.54, 2, 1, 32, 1, 9848561, 1),
(72, 1, 'Sirena semiexterior inalámbrica Magellan 30W', 'Paradox', 'SR230', 1, 33108.32, 40.00, 46351.65, 2, 1, 33, 3, 966561, 1),
(73, 1, 'Módulo de comunicación Paradox, LTE V8 MQTT', 'Paradox', 'PCS265V8', 1, 38076.85, 37.00, 52165.28, 2, 1, 24, 3, 5465684, 1),
(74, 1, 'Receptor para inalámbrico 20m', 'Paradox', 'RX1', 1, 10414.62, 60.00, 16663.39, 2, 1, 24, 3, 9985245, 1),
(75, 1, 'Modulo de protección de línea telefonica', 'Paradox', '320S', 1, 11500.00, 58.00, 18170.00, 2, 1, 27, 3, 891654, 1),
(76, 1, 'Sensor de movimiento cableado 4 rayos 12m', 'Paradox', 'NV780MX', 1, 40388.39, 35.00, 54524.33, 2, 1, 28, 3, 89456489, 1),
(77, 8, 'Asalto manual NO SS-078Q', 'Enforce', 'SS078', 2, 3886.43, 89.00, 7345.35, 2, 1, 30, 1, 786348, 1),
(78, 1, 'Sensor movimiento Paradox inalámbrico interior antimascotas', 'Paradox', 'PMD2P', 1, 14148.64, 55.00, 21930.39, 2, 1, 28, 3, 8774175, 1),
(79, 1, 'Sensor movimiento Paradox cableado interior antimascotas 2 lentes mironel', 'Paradox', 'NV75M', 1, 18539.90, 45.00, 26882.85, 2, 1, 28, 3, 65478, 1),
(80, 2, 'Detector de humo inalámbrico Paradox para techo', 'otro', 'SD360', 1, 35180.16, 38.50, 48724.52, 2, 1, 28, 2, 63418949, 1),
(81, 1, 'Fuente de alimentación alarma 1.7A Paradox', 'Paradox', 'PS817', 1, 11837.10, 60.00, 18939.36, 2, 1, 20, 3, 96848948, 1),
(82, 1, 'Contacto inalámbrico de 2 zonas de 70mts', 'Paradox', 'DCT10', 1, 15317.10, 55.00, 23741.51, 2, 1, 31, 3, 954154, 1),
(83, 2, 'Sensor fotoeléctrico de 60m, 2 rayos', 'dLux', 'ABT60', 1, 16256.96, 56.00, 25360.86, 2, 1, 28, 2, 9845648, 1),
(84, 35, 'Adaptador de 4 salidas de tornillo 12V 5A', 'dLux', 'DLXCP12054', 1, 5588.33, 70.00, 9500.16, 2, 1, 20, 1, 5646846, 1),
(85, 7, 'Transformador 16V 40VA AC', 'dLux', 'T1640', 3, 3835.63, 95.00, 7479.48, 2, 1, 20, 1, 546849689, 2),
(86, 5, 'Batería de 12V 4A dLux', 'dLux', 'PL4', 3, 3693.38, 125.00, 8310.10, 2, 1, 20, 1, 5454984, 1),
(87, 2, 'Batería de 6V 4.5A dLux', 'dLux', 'PS4.5-6', 1, 2514.75, 100.00, 5029.50, 2, 1, 20, 2, 9848498, 1),
(88, 5, 'Sirena 12V 30W de 2 tonos', 'dLux', 'TS333S', 2, 5054.90, 70.00, 8593.33, 2, 1, 33, 1, 87484, 1),
(89, 3, 'Contacto magnético Dlux', 'dLux', 'PS1523', 1, 574.07, 180.00, 1607.40, 2, 1, 31, 1, 8567696, 1),
(90, 2, 'Contacto magnético de portón o semipesado', 'dLux', 'MCIND', 1, 2474.11, 78.00, 4403.92, 2, 1, 31, 2, 56849687, 1),
(91, 9, 'Detector de movimiento inalámbrico inmune a mascotas color blanco', 'Ajax', '26803.09.WH3', 4, 19310.22, 45.00, 27999.82, 2, 1, 28, 1, 98844949, 1),
(92, 3, 'Detector de movimiento inalámbrico Ajax negro', 'Ajax', '28303.02.BL3', 1, 28195.67, 40.00, 39473.94, 2, 1, 28, 1, 989484, 1),
(93, 7, 'Contacto magnético inalámbrico para puertas y ventanas interior', 'Ajax', '26762.03.WH3', 2, 11938.71, 60.00, 19101.94, 2, 1, 31, 1, 987984, 1),
(94, 4, 'Teclado táctil inalámbrico Ajax', 'Ajax', '21504.12.WH3', 2, 30989.93, 40.00, 43385.90, 2, 1, 32, 1, 8948874, 1),
(95, 1, 'Panel de alarma inalámbrico Ajax Negro', 'Ajax', '44518.40.BL3', 1, 67567.99, 30.00, 87838.39, 2, 1, 23, 3, 984894468, 1),
(96, 4, 'Panel de alarma inalámbrico Ajax', 'Ajax', '92212.259.WH3', 2, 59409.03, 30.00, 77231.74, 2, 1, 23, 1, 8484948, 1),
(97, 3, 'Módulo Repetidor de Señal de Radio Ajax ReX Blanco', 'Ajax', '28309.37.WH3', 2, 39753.35, 35.00, 53667.02, 2, 1, 27, 2, 897898, 1),
(98, 2, 'Sirena inalámbrica Ajax blanca', 'Ajax', '28315.07.WH3', 2, 39306.28, 35.00, 53063.48, 2, 1, 33, 3, 89797468, 1),
(99, 1, 'Detector de incendios inalámbrico con sensores de calor y humo', 'Ajax', '50779.136.WH3', 1, 25950.17, 40.00, 36330.24, 2, 1, 28, 3, 8978474, 1),
(100, 1, 'Control remoto de cuatro botones con indicación de entrega de comando Ajax', 'Ajax', '28313.04.WH3', 1, 9906.59, 60.00, 15850.54, 2, 1, 29, 3, 987878545, 1),
(101, 5, 'Detector de movimiento de cortina Ajax blanco', 'Ajax', '28273.81.WH3', 2, 66551.93, 25.00, 83189.91, 2, 1, 28, 1, 8978748, 1),
(102, 12, 'Balun pasivo 1080 UTP CAT5E-6', 'Dahua', 'PFM800E', 4, 990.66, 89.00, 1872.35, 2, 2, 19, 1, 6884848, 1),
(103, 2, 'VGA to RJ45', 'Dahua', 'PFM710', 1, 12624.55, 60.00, 20199.28, 2, 2, 19, 2, 987895564, 1),
(104, 15, 'Tomas de una ', 'otro', 'Sin código', 2, 500.00, 20.00, 1500.00, 2, 18, 19, 1, 78368, 3),
(105, 9, 'Salidas RJ45 to DVR ', 'Titanium', 'Sin código', 1, 850.00, 80.00, 1530.00, 2, 18, 34, 1, 6886465, 1),
(106, 32, 'Salidas RJ45 de 8MP Etiqueta amarilla', 'otro', 'Sin código', 1, 500.00, 200.00, 1500.00, 2, 18, 34, 1, 8788, 1),
(107, 5, 'Salidas de RJ45 de 5MP ', 'Titanium', 'Sin código', 1, 500.00, 200.00, 1500.00, 2, 18, 34, 1, 98789646, 1),
(108, 21, 'Conectores de Corriente', 'dLux', 'CA161T - CA151T', 9, 522.44, 130.00, 1200.00, 2, 2, 19, 1, 98784, 1),
(109, 18, 'Salida de 4 colas ', 'dLux', 'DLXC614', 5, 705.04, 100.00, 1410.08, 2, 2, 19, 1, 98785456, 1),
(110, 11, 'Salidas de 2 colas', 'Dahua', 'DCFM12', 5, 334.38, 185.00, 952.98, 1, 2, 19, 1, 107582, 1),
(111, 5, 'Regelta de 6 tomas', 'Smarbit', 'SBSS-B6-3U', 2, 4894.02, 70.00, 8319.83, 4, 18, 22, 1, 87878, 1),
(112, 3, 'Cable UTP CAT 6 (Interior)', 'dLux', 'UTPCAT6', 1, 182.86, 40.00, 256.00, 2, 18, 35, 1, 9878, 1),
(113, 3, 'Cable UTP CAT 6 (Intemperie)', 'Teklink', 'CAB-TEK6-OUT', 1, 214.52, 40.00, 300.33, 2, 18, 35, 1, 87646878, 1),
(114, 4, 'Cable UTP CAT 5 (Intemperie)', 'Teklink', 'CAB-5EOUTGTEK', 1, 145.64, 80.00, 262.15, 2, 18, 35, 1, 984565, 1),
(115, 1, 'Cable Coaxial', 'Teklink', '18AWG CCS CM Black PVC', 1, 150.00, 60.00, 240.00, 2, 18, 37, 3, 98789, 1),
(116, 2, 'Cable VGA', 'dLux', '4848', 1, 3104.19, 289.00, 12075.30, 2, 18, 34, 2, 4646552, 1),
(117, 2, 'HDMI to VGA', 'Argom', 'A00174 ARG-CB-0055', 1, 1200.00, 110.00, 2520.00, 2, 3, 34, 2, 4848, 1),
(118, 2, 'DVI-D to HDMI', 'Argom ', 'ARG-CB-1320 A00464', 1, 1500.00, 160.00, 3900.00, 2, 3, 34, 2, 89484, 2),
(119, 3, 'Adaptador POE', 'Ubiqunet', 'POE-48V-05A', 1, 2000.00, 125.00, 4500.00, 2, 3, 34, 1, 778885565, 1),
(120, 4, 'Kit de antenas 3km', 'Wi-Tek', 'WI-CPE511H-KIT', 2, 41319.00, 35.00, 55780.65, 1, 3, 16, 1, 98484, 1),
(121, 2, 'Switch 8 puertos POE', 'Wi-Tek', 'WI-PCES310GF', 1, 15330.53, 55.00, 23762.32, 1, 3, 4, 2, 9849, 1),
(122, 1, 'Switch 4 puertos POE', 'Wi-Tek', 'WI-PS206GF-I', 1, 9198.12, 60.00, 14716.99, 1, 3, 4, 2, 98494, 1),
(123, 3, 'Switch 4 puertos ', 'Dahua', 'PFS30055GTL', 2, 4727.29, 70.00, 8036.39, 2, 3, 4, 2, 98464, 1),
(124, 1, 'Switch 8 puertos', 'Dahua', 'PFS30088GTL', 2, 6548.21, 70.00, 11131.96, 2, 3, 4, 3, 68786, 1),
(125, 2, 'Disco Duro 2 TB', 'Purple', 'HDD2000', 2, 29115.90, 40.00, 40762.26, 4, 2, 18, 3, 87464, 1),
(126, 4, 'Disco Duro 1 TB', 'Purple', 'HDD1000', 3, 22251.30, 45.00, 32264.38, 4, 2, 18, 1, 98763, 1),
(127, 1, 'Switch Cloud DAHUA, PoE de 4 puertos +2 UPLINK', 'Dahua', 'CS40064ET60', 1, 16763.62, 55.00, 25983.61, 2, 3, 4, 3, 98763, 1),
(128, 2, 'Switch RB260GS de 5 puertos Gigabit Ethernet', 'Mikrotik', 'CSS106-5G-1S', 1, 20415.61, 45.00, 29602.63, 4, 3, 4, 2, 98746, 1),
(129, 4, 'Extensor HDMI 50mt ', 'Steren', '208-106', 2, 12000.00, 45.00, 17400.00, 4, 3, 34, 1, 8946, 1),
(130, 4, 'Cable HDMI 5mt', 'XKT eco', 'ZK-50HD-C', 2, 5000.00, 70.00, 8500.00, 5, 2, 19, 1, 854634, 1),
(131, 1, 'Grabador XVR 8CH', 'Dahua', 'DH-XVR1B08-I', 1, 20503.94, 45.00, 29730.71, 1, 2, 21, 3, 8480, 1),
(132, 1, 'Grabador XVR 16CH', 'Dahua', 'DH-XVR1B16 -I', 1, 34628.87, 40.00, 48480.42, 1, 2, 21, 3, 8084, 1),
(133, 0, 'Cámara IMOU Bullet 3mp', 'Imou', 'IPC-K3DN-3H0W', 1, 18043.59, 40.00, 25261.03, 1, 2, 2, 2, 8084, 1),
(134, 2, 'Cable UTP CAT6 ', 'Dahua', 'DH-PFM920I-6U', 1, 173.00, 45.00, 250.85, 1, 18, 35, 2, 8480, 1),
(135, 2, 'Grabador NVR 8CH ', 'Dahua', 'NVR1108HSS3H', 1, 26806.34, 40.00, 37528.88, 2, 2, 21, 2, 588, 1),
(136, 1, 'Grabador NVR 16CH', 'Dahua', 'NVR4216EI', 1, 76878.56, 30.00, 99942.13, 2, 2, 21, 3, 588, 1),
(137, 2, 'Gabinete 6U', 'dLux', ' A6W6D6', 1, 36375.84, 35.00, 49107.38, 2, 2, 19, 2, 588, 1),
(138, 1, 'Gabinete 6U', 'dLux', 'A6W6D6', 1, 36572.40, 35.00, 49372.74, 2, 2, 19, 3, 5887, 1),
(139, 2, 'Gabinete 4U', 'dLux', 'DLX4W6D4', 1, 26544.26, 42.00, 37692.85, 2, 2, 19, 2, 588, 1),
(140, 1, 'UPS 600W', 'HIKVISION ', '304901286', 1, 16228.20, 55.00, 25153.71, 4, 18, 22, 3, 251458, 1);

-- --------------------------------------------------------

--
-- Table structure for table `hc_empleados`
--

CREATE TABLE `hc_empleados` (
  `emp_id` int NOT NULL,
  `emp_nombre` varchar(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `emp_apellidos` varchar(120) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `emp_cedula` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `emp_telefono` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `emp_correo` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `emp_direccion` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `emp_salario` decimal(10,2) NOT NULL,
  `emp_fechaIngreso` date NOT NULL,
  `emp_cuenta` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci,
  `emp_codigo` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `emp_foto` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `emp_carnetAgente` date DEFAULT NULL,
  `emp_carnetArma` date DEFAULT NULL,
  `emp_testPsicologico` date DEFAULT NULL,
  `emp_huellas` date DEFAULT NULL,
  `emp_delta` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `emp_puesto` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `emp_vacaciones` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `emp_licencias` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `emp_obd_id` int DEFAULT NULL,
  `emp_rol_id` int NOT NULL,
  `emp_dep_id` int NOT NULL,
  `emp_estado` int NOT NULL DEFAULT '25',
  `emp_estado_supervision` int NOT NULL DEFAULT '35'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hc_empleados`
--

INSERT INTO `hc_empleados` (`emp_id`, `emp_nombre`, `emp_apellidos`, `emp_cedula`, `emp_telefono`, `emp_correo`, `emp_direccion`, `emp_salario`, `emp_fechaIngreso`, `emp_cuenta`, `emp_codigo`, `emp_foto`, `emp_carnetAgente`, `emp_carnetArma`, `emp_testPsicologico`, `emp_huellas`, `emp_delta`, `emp_puesto`, `emp_vacaciones`, `emp_licencias`, `emp_obd_id`, `emp_rol_id`, `emp_dep_id`, `emp_estado`, `emp_estado_supervision`) VALUES
(13, 'Carlos', 'Ramírez Torres', '1-1234-0567', '8888-1234', 'carlos.ramirez@example.com', 'San José, Costa Rica', 0.00, '2023-05-12', 'Cuenta Banco Nacional', 'FS-CR-2023-0567', 'foto1.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '15', '2', 1, 1, 5, 25, 35),
(18, 'Maikel', 'Cambronero', '208230227', '8370 9711', 'maikel.cambronero22@gmail.com', 'La tigra', 0.00, '2025-04-02', '123589848', 'FS-CR-2025-0227', 'maikel-cambronero.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '02/04/2026 to 16/04/2026', 'B1', 1, 4, 5, 25, 35),
(19, 'Berny', 'Arroyo Corrales', '206880197', '7113 - 3533', 'bernyarroyo@gmail.com', 'Bonanza', 0.00, '2023-02-24', '12556', 'FS-CR-2023-0197', 'berni-montero.jpg', '2027-04-15', '2027-04-15', '2027-03-01', '2026-02-01', NULL, NULL, '01/03/2025 to 15/03/2025', 'B1, A2', 1, 9, 8, 25, 35),
(20, 'Walter Junnior', 'Soto Murgas', '207540180', '8576 - 2749', 'jun14sm@gmail.com', 'Alajuela, Guatuso San Rafael', 0.00, '2024-12-16', '', 'FS-CR-2024-0180', 'sin_img.png', NULL, NULL, NULL, NULL, NULL, NULL, '16/12/2024 to 30/12/2024', 'B1, A2', 2, 10, 8, 26, 35),
(21, 'Joel ', 'Soza Blandon', '155839287710', '6030 - 9154', 'joel@gmail.com', 'San Josesito de Cutris', 0.00, '2023-06-01', NULL, 'FS-CR-2023-7710', 'sin_img.png', NULL, NULL, NULL, NULL, NULL, NULL, '01/06/2025 to 15/06/2025', 'A1', 3, 2, 6, 27, 35),
(22, 'Kervin', 'Castro', '208320220', '87835279', 'kervincastro217@gmail.com', 'Sonafluca', 0.00, '2025-05-29', '123', 'FS-CR-2025-0220', 'kervin-castro.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '21/05/2026 to 28/05/2026', 'B1, A3', 1, 3, 5, 25, 35),
(23, 'prueba', 'prueba', '5615565', '8569 - 0860', 'vfdvfdv@gmail.com', 'Sonafluca', 0.00, '2025-06-10', NULL, 'FS-CR-2025-5565', 'sin_img.png', '2025-06-26', '2025-06-30', '2025-06-30', '2025-06-28', '1235665yrf', 'Mistico, grupo #2', '18/06/2025 to 02/07/2025', 'B1, A2', 1, 6, 7, 25, 35),
(24, 'Azul', 'Carlos', '55889966154d', '62966011', 'maikel.cambronero22@gmail.com', 'Sonafluca', 0.00, '2025-06-19', 'Cuenta Banco Nacional', 'FS-CR-2025-154d', 'sin_img.png', '2025-06-30', '2025-07-01', '2025-07-10', '2025-07-12', '1235665yrf', 'Mistico, grupo #3', '', 'B1', 1, 6, 7, 25, 36),
(25, 'hola', 'hola', '1233', '8896', 'distmmagica@gmail.com', 'Sonafluca', 0.00, '2025-06-17', 'Cuenta Banco Nacional', 'FS-CR-2025-1233', 'sin_img.png', NULL, NULL, NULL, NULL, '', '', '02/07/2025 to 10/07/2025', 'B1, A3', 1, 1, 6, 25, 35);

-- --------------------------------------------------------

--
-- Table structure for table `hc_equipos_orden`
--

CREATE TABLE `hc_equipos_orden` (
  `erd_id` int NOT NULL,
  `erd_codigo` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `erd_descripcion` varchar(250) COLLATE utf8mb4_general_ci NOT NULL,
  `erd_cantidad` int NOT NULL,
  `erd_tipo` int NOT NULL,
  `erd_orden_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hc_equipos_orden`
--

INSERT INTO `hc_equipos_orden` (`erd_id`, `erd_codigo`, `erd_descripcion`, `erd_cantidad`, `erd_tipo`, `erd_orden_id`) VALUES
(9, '1253', 'vduy dscd', 1, 1, 16),
(10, 'SP5500+', 'Panel de alarma', 1, 2, 17),
(11, 'IP180+', 'Modulo de Transmisión', 3, 2, 17),
(12, 'K636', 'Teclado de Alarma Paradox', 1, 2, 18),
(13, 'EP-X300', 'Teclado de Alarma Paradox', 1, 2, 18),
(14, 'K636', 'Teclado de Alarma Paradox', 1, 2, 19),
(15, 'K636', 'Teclado de Alarma Paradox', 1, 2, 20),
(16, 'EP-X300', 'hola mundo', 2, 2, 21),
(17, 'K636', 'Panel de alarma', 1, 2, 21),
(18, 'D-INSP15-256', 'laptop', 1, 3, 21),
(28, 'DH-CS4010-8GT-110', 'Switch de 8p Dahua', 1, 2, 27),
(29, 'WI-PCES310GF', 'Switch de 8p Wi-Teck', 1, 2, 27),
(30, 'PS-12VDC6AMP', 'Transformador 6AMP', 2, 2, 27),
(31, 'DLXC614', 'Salidas de 4 colas', 2, 2, 27),
(32, 'DHI-NVR5208-8P-EI', 'Grabador POE 8CH 4K', 1, 2, 27),
(33, 'HDD1000', 'Disco Duro 1TB', 1, 2, 27),
(34, '-', 'Inyector POE', 6, 2, 27),
(35, 'SDD1000', 'Disco Duro 1TB', 1, 1, 28),
(36, 'DLXC614', 'Salida de 4 colas', 1, 1, 28),
(37, 'PS-12VDC6AMP', 'Transformador 6AMP', 1, 1, 28),
(38, 'DH-PFM800-E', 'Video Balunes', 4, 1, 28),
(39, '-', 'Pareja de Conectores de Corriente', 4, 1, 28),
(40, '-', 'Caja Plexo 10 x 10 x 7.5 CM', 4, 1, 28),
(41, 'DH-HAC-HFW1239TN-A-LED', 'Camara Bullet Ana. Full Color 2MP', 2, 1, 28),
(42, 'DH-HAC-HFW1239TLMN-LED', 'Camara Bullet Ana. Full Color 2MP', 1, 1, 28),
(43, 'DH-HAC-HDW1209TLQN-LED', 'Camara Domo Ana. Full Color 2MP', 1, 1, 28),
(44, '', 'Sirena 2 tonos ', 1, 2, 30),
(45, '', 'Sensores ', 2, 2, 30),
(46, '', 'IP150', 1, 2, 30),
(47, '', 'Transformador ', 2, 2, 30),
(48, '-', 'Cable UTP CAT5', 4, 1, 31),
(49, 'DH-HAC-HFW1239TLMN-IL-A', 'Cámar Ana. 2MP WizSense', 1, 2, 33),
(50, 'DH-PFM800-E', 'Video Balun', 1, 2, 33),
(51, 'PS-12VDC6AMP', 'Transformador 6AMP', 1, 2, 33),
(52, '-', 'Salida 4 colas', 1, 2, 33),
(53, '-', 'Salida 2 colas', 1, 2, 33),
(54, '-', 'Pareja de conectores de corriente', 1, 2, 33),
(55, 'IPC-S7XN-10MOWED', 'Cámara Imou Dual 10mp', 1, 2, 34),
(56, '-', 'Inyector Poe', 1, 2, 34),
(57, '-', 'Salida de 4 colas', 1, 2, 34),
(58, 'PS4-12', 'Bateria de Panel 12V', 1, 1, 34),
(59, 'DH-HAC-HFW1239TLMN-IL-A', 'Cámar Ana. 2MP WizSense', 2, 2, 35),
(60, '-', 'Salida de 4 colas', 1, 2, 35),
(61, '-', 'Salida de 2 colas', 1, 2, 35),
(62, '-', 'Video Balunes', 1, 2, 35),
(63, '-', 'Pareja de conectores de corriente', 1, 2, 35),
(64, '-', 'Bateria de panel de alarme', 1, 2, 37),
(65, '-', 'Cámar Ana. 2MP WizSense', 1, 2, 37),
(66, '-', 'Camara Ana. 2MP Full Color', 1, 2, 37),
(67, '-', 'Video Balunes', 1, 2, 37),
(68, '-', 'Teclado K10', 1, 2, 37),
(69, '-', 'PM75 ', 3, 2, 37),
(70, '-', 'IP150+', 1, 2, 37),
(71, '-', 'Transformador de alarma', 1, 2, 37),
(72, 'K32LCD+', 'Teclado de Alarma 32 Zonas', 1, 2, 41),
(73, '-', 'Transformador 6AMP', 2, 2, 42),
(74, '-', 'Camara IP 4MP WizSense', 3, 2, 42),
(75, '-', 'Inyectores POE', 3, 2, 42),
(76, '-', 'Transformador 2AMP', 2, 2, 42),
(77, '-', 'Salidas 2 colas', 2, 2, 42),
(78, '-', 'MicroSD 128', 1, 1, 42),
(79, 'K636', 'Teclado de Alarma Paradox', 3, 2, 43),
(80, 'K636', 'Teclado de Alarma Paradox', 1, 2, 44),
(81, 'D-INSP15-256', 'Cámara IP 4MP WizSense Dahua', 2, 2, 44),
(82, 'EP-X300', 'Grabador POE de 8CH', 3, 2, 44),
(83, '-', 'Camara IP 4MP', 1, 2, 46),
(84, '-', 'Disco Duro 1TB', 1, 1, 46),
(85, '', ' XVR Dahua 8CH 5M', 1, 1, 47),
(86, '', ' Disco duro 1TB', 1, 1, 47),
(87, '', ' Bullet, IP 4MP, lente 2.8mm, iluminación dual', 1, 1, 47),
(88, '', ' Domo, IP 4MP, lente 2.8mm, iluminación dual 30m', 1, 1, 47),
(89, '', ' Imou Dual IP 10MP, 360°', 1, 1, 47),
(90, '', ' Inyector POE de corriente', 1, 1, 47),
(91, '', 'Cable UTP CAT 5 Intemperie', 1, 1, 47),
(92, 'XVR5108HS-I3', 'Grabador XVR 8CH', 1, 2, 55),
(93, 'HACHFW1239TLMNILA', 'Camaras Ana. 2MP con audio', 3, 1, 56),
(94, 'HACHFW1239TLMNILA', 'Camaras Ana. 2MP con audio', 3, 2, 56),
(95, '-', 'Cajas Plexo 4x4', 4, 1, 56),
(96, 'PFM800E', 'Video Balunes ', 3, 1, 56),
(97, 'PFM800E', 'Video Balunes ', 3, 2, 56),
(98, 'PS12VDC6AMP', 'Fuente de Poder de 6AMP', 1, 1, 56),
(99, 'PS12VDC6AMP', 'Fuente de Poder de 6AMP', 3, 2, 56),
(100, 'PS12VDC2AMP', 'Fuente de Poder de 2AMP', 3, 2, 56),
(101, 'PS12VDC2AMP', 'Fuente de Poder de 2AMP', 1, 1, 56),
(102, '-', 'Conectores de corriente ', 10, 2, 56),
(103, '-', 'Salida de 4 colas ', 5, 2, 56),
(104, 'IPC-S7XN-10MOWED', 'Imou doble lente 10mp', 1, 1, 56),
(105, 'TFC100/128GB', 'MicroSD 128GB', 1, 1, 56),
(106, 'XVR1B16-I', 'Grabador XVR 16CH', 1, 1, 56),
(107, '', 'carrucha cable cat5 Nueva', 1, 2, 56),
(108, '', 'carrucha cable cat5 Usada', 1, 2, 56),
(109, '', 'Caja cable utp cat6 interior ', 155, 2, 56),
(110, '', 'Disco Duro 2TB', 1, 1, 56),
(111, 'hAP ac lite ', 'Router VPN', 3, 3, 57),
(112, 'DHI-NVR5208-8P-EL', 'Grabador NVR 8CH POE 4K ', 3, 2, 58),
(113, 'WI-PCES310GF', 'Switch 8 puertos', 1, 2, 58),
(114, 'DH-IPC-HFW2449S-S-IL', 'Cámara IP 4MP WizSense, tipo domo', 4, 2, 58),
(128, 'IPCHFW1439S1ALED', 'Cámara IP 4MP Full Color Bullet', 3, 2, 68),
(129, 'HACHFW1239TLMNILA', 'Cámar Ana. 2MP HDCVI', 3, 2, 68),
(130, 'PS-12VDC2AMP', 'Transformador de 3AMP', 2, 2, 68),
(131, '-', 'Inyecto POE', 3, 2, 68),
(132, '-', 'Disco Duro 1TB', 1, 2, 68),
(133, 'XVR1B08-I', 'Grabador XVR 8CH', 1, 2, 68),
(134, '-', 'Regletas', 3, 2, 68),
(135, 'NV5', 'Sensor de Movimiento', 5, 2, 69),
(136, 'K636', 'Teclado de Alarma Paradox', 1, 2, 69),
(137, 'PMD75N', 'Sensor inalámbrico', 6, 2, 70),
(138, 'RTX3', 'Transmisor inalámbrico', 1, 2, 70),
(139, 'DH-HAC-HFW1239TLMN-IL-A', 'Cámar Ana. 2MP ', 3, 2, 71),
(140, '-', 'Video Balunes', 5, 2, 71),
(141, '-', 'Conectores de corriente', 10, 2, 71),
(142, 'PS-12VDC6AMP', 'Transformador 6AMP', 3, 2, 71),
(143, '-', 'Salida 4 colas', 3, 2, 71),
(144, '-', 'regeltas', 2, 1, 71),
(145, 'PS-12VDC2AMP', 'Transformador de 3AMP', 1, 2, 72),
(146, 'PS-12VDC2AMP', 'Transformador de 3AMP', 1, 2, 72),
(147, 'PS-12VDC2AMP', 'Transformador de 3AMP', 1, 2, 72),
(148, 'IPCHFW2449SIL', 'Cáamra IP 4MP WizSense', 5, 1, 73),
(149, 'TDC100/128GB', 'MicroSD 128GB', 5, 1, 73),
(150, 'DLXC614', 'Salida de 4 colas', 2, 1, 73),
(151, '-', 'Caja plexo 4x4', 5, 1, 73),
(152, 'PS-12VDC6AMP', 'Fuente de Poder 6AMP', 1, 1, 73),
(153, '-', 'Inyector POE', 6, 1, 73),
(162, 'EP-X300', 'gg.opop', 2, 2, 23),
(163, 'EP-X300', 'Proyector Epson X300, HDMI, 3300 lúmenes', 1, 2, 23),
(164, 'DH-IPC-HFW2449S-S-IL', 'Cámara IP 4MP WizSense, tipo domo', 1, 2, 23),
(165, 'IPC-S7XN-10MOWED', 'Cam Imou Dual 10MP', 1, 2, 74),
(166, '-', 'Inyector POE', 1, 2, 74),
(167, '-', 'MicroSD 128', 1, 2, 74),
(187, 'DH-HAC-HFW1239TN-A-LED', 'Camara Bullet Ana. 2MP Full Color', 2, 2, 75),
(188, 'DH-HAC-HFW1239TLMN-IL-A', 'Camaras Bullet Ana. 2MP HDCVI ', 3, 2, 75),
(189, 'DH-HAC-HDW1239TN-A-LED', 'Camaras Domo Ana. 2MP Full Color', 2, 2, 75),
(190, '-', 'Conectores de corriente ', 15, 2, 75),
(191, 'PS-12VDC6AMP', 'Fuente de Poder 6AMP', 2, 2, 75),
(192, 'DLXC614', 'Salida de 4 colas', 2, 2, 75),
(237, '-', 'Kit de Antenas ', 1, 1, 76),
(277, 'DHPF3005-5GT-L', 'Switch 4 puertos', 1, 1, 79),
(278, 'D-INSP15-256', 'Laptop Dell Inspiron 15, 8GB RAM, 256GB SSD', 1, 1, 79),
(279, 'fv', 'vfd', 1, 1, 79),
(280, '-', 'Extensor HDMI', 1, 1, 81),
(281, '-', 'Disco Duro 1TB', 1, 2, 82),
(282, '-', 'Grbador NVR 8CH', 1, 2, 82),
(349, '-', 'Kit de Antenas Wi-Tek', 1, 2, 84),
(398, '-', 'Cámara IP 4MP WizSense Dahua', 2, 1, 85),
(399, '-', 'Cajas Plexo 4x4', 2, 1, 85),
(418, '-', 'Kit de Antenas Wi-Tek', 1, 1, 89),
(421, 'PL4-12', 'Bateria de panel de alarma', 3, 1, 95),
(422, 'TS-333S', 'Sirena 2 tonos ', 1, 1, 96),
(423, 'K636', 'Teclado de Alarma Paradox', 1, 1, 96),
(424, 'PS4-12', 'Batería de panel de alarma', 1, 1, 96),
(425, 'SP5500+', 'Panel de alarma', 1, 1, 96),
(426, 'IP150+ P2C', 'Modulo de comunicación', 1, 1, 96),
(427, 'NV5', 'Sensor de Movimiento', 4, 1, 96),
(428, '-', 'Imou Dual 10MP', 1, 2, 97),
(429, '-', 'Inyector POE', 1, 2, 97),
(430, '-', 'Fuente de Poder 6AMP', 1, 2, 97),
(431, '-', 'Transformador de Alarma', 1, 2, 98),
(432, '-', 'Bateria de panel de alarma', 1, 2, 98),
(472, 'HAC-HFW1200TLMN-IL-A', 'Cámara análoga 2mp HDCVI', 1, 2, 104),
(473, '-', 'Video balunes', 2, 2, 104),
(474, '-', 'Conector de corriente', 2, 2, 104),
(475, 'DH-HAC-HFW1239TLMN-IL-A', 'Cámara HAC 2MP HDCVI Bullet', 2, 2, 110),
(476, 'DH-IPC-HFW2449S-S-IL', 'Cámara IP 4MP WizSense Bullet', 2, 2, 110),
(477, '-', 'Fuente de poder 6AMP', 2, 2, 110),
(478, '-', 'Fuente de poder 2AMP', 2, 2, 110),
(479, '-', 'Salida de 4 colas', 1, 2, 110),
(480, '-', 'Salida 2 colas', 1, 1, 110),
(481, '-', 'Switch 4 puertos POE', 1, 1, 111),
(484, 'DH-HAC-HFW1239TLMN-IL-A', 'Cámara HAC 2MP HDCVI Bullet', 5, 2, 116),
(485, 'PS-12VDC6AMP', 'Fuente de Poder 6MAP', 3, 2, 116),
(486, 'PS-12VDC2AMP', 'Fuente de Poder 2AMP', 3, 2, 116),
(487, '-', 'UPS 600W Usadas', 1, 1, 83),
(488, '-', 'UPS 500W Usadas', 1, 1, 83),
(489, 'R-UPR1008', 'UPS 800w Usadas', 3, 1, 83),
(490, '-', 'Carrucha de cable utp cat5 Interperie', 20, 2, 83),
(491, '-', 'Caja Plexo 4x4', 5, 1, 83),
(492, 'IPC-HFW2449S-S-IL', 'Cámara IP 4MP WizSense', 4, 2, 83),
(493, '-', 'Disco Duro 4TB', 1, 1, 83),
(494, '-', 'Gabinete 6U', 5, 1, 83),
(495, 'PFS3218-16ET-135', 'Switch 16 puertos POE', 1, 1, 83),
(496, 'PS210H', 'Switch 8 puertos POE', 2, 1, 83),
(497, '-', 'Bandeja Rack', 4, 1, 83),
(498, '-', 'Regleta normal ', 2, 2, 83),
(499, 'DH-IPC-HDW2449T-S-IL', 'Cámara Domo IP 4MP WizSense', 5, 2, 83),
(500, '-', 'Cable UTP CAT5', 50, 1, 83),
(501, '-', 'Cable UTP CAT6', 30, 1, 83),
(502, '-', 'Cable HDMI 15mt ', 1, 1, 99),
(503, '-', 'Switch 4 puertos POE ', 1, 1, 99),
(504, 'ZK-50HD-C', 'HDMI 5MT ', 1, 1, 99),
(505, 'hAP ac lite ', 'Router Mikrotik  ', 5, 1, 99),
(506, 'IMP00075', 'MIKROTIK RB4011IGS+RM ', 1, 1, 99),
(507, '-', 'Cable Coaxial', 1, 2, 117),
(508, 'A6W6D6', 'Gabinete 6U', 1, 2, 118),
(509, 'DLXSHELF', 'Bandeja 1U', 1, 2, 118),
(510, 'DLX10010070S', 'Caja Plexo 4x4', 11, 2, 118),
(511, 'DH-IPC-HFW1439S1-A-LED', 'Cámara IP 4MP Full Color Bullet', 8, 2, 118),
(512, 'DH-HAC-HFW1239TLMN-IL-A', 'Cámara HAC 2MP HDCVI Bullet', 6, 2, 118),
(513, 'PS-12VDC2AMP', 'Fuente de Poder 2AMP', 2, 2, 118),
(514, 'PS-12VDC6AMP', 'Fuente de Poder 6MAP', 2, 2, 118),
(515, 'LTK-POECABLE LANTEK', 'Inyector POE', 9, 1, 118),
(516, '-', 'Switch 8 puertos POE', 1, 2, 118),
(517, '-', 'Switch 4 puertos POE', 1, 2, 118),
(518, '-', 'Switch 8 puertos', 1, 2, 118),
(519, '-', 'Switch 4 puertos', 1, 1, 118),
(520, '-', 'Cable UTP CAT6 255', 1, 2, 118),
(521, '-', 'Kit de Antenas Wi-Tek', 1, 2, 118),
(522, 'A6W6D6', 'Gabinete 6U', 1, 1, 119),
(523, 'DLXSHELF', 'Bandeja 1U', 1, 1, 119),
(524, 'SBNB600', 'UPS 300W', 1, 1, 119),
(539, 'IP150+', 'Modulo de comunicación', 1, 1, 93),
(540, 'SP5500+', 'Panel de alarma', 1, 1, 93),
(541, 'DLXSHELF', 'Bandeja 1U', 1, 1, 115),
(542, 'A6W6D6', 'Gabinete 6U', 1, 1, 115),
(551, 'HDD1000', 'Disco Duro 1TB', 1, 1, 120),
(552, 'NVR1108HS-S3/H', 'Grabador NVR 8CH', 1, 1, 120),
(553, 'DH-IPC-HDW2449Y-S-IL', 'Cámara IP 4MP WizSense Domo', 1, 1, 120),
(554, 'DH-IPC-HFW2449S-S-IL', 'Cámara IP 4MP WizSense Bullet', 3, 1, 120),
(555, 'SBSS-B6-3U', 'Regelta de 6 tomas', 1, 1, 120),
(556, 'WI-PS206GF-I', 'Switch 4 puertos POE', 1, 1, 120),
(557, 'PL4', 'Batería de 12V 4A dLux', 1, 1, 120),
(558, 'DLX10010070S', 'Caja Plexo 4x4', 1, 1, 120),
(559, 'DH-IPC-HDW2449Y-S-IL', 'Cámara IP 4MP WizSense Domo', 3, 1, 125),
(560, 'DH-IPC-HFW1439S1-A-LED', 'Cámara IP 4MP Full Color Bullet', 5, 1, 125),
(561, 'HDD1000', 'Disco Duro 1 TB', 1, 1, 125),
(562, 'DHI-NVR5208-8P-EI', 'Grabador NVR 8 CH 4K POE', 1, 1, 125),
(563, 'ZK-50HD-C', 'Cable HDMI 5mt', 1, 1, 125),
(564, ' IPC-S7UN-11M0', 'Cámara IP Triple 11MP 360° ', 1, 3, 125),
(570, 'DLXSHELF', 'Bandeja 1U', 1, 1, 126),
(571, 'DH-IPC-HFW1439S1-A-LED', 'Cámara IP 4MP Full Color Bullet', 3, 2, 126),
(572, 'DH-IPC-HFW2449S-S-IL', 'Cámara IP 4MP WizSense Bullet', 1, 2, 126),
(573, 'DH-IPC-HDW2449Y-S-IL', 'Cámara IP 4MP WizSense Domo', 1, 2, 126),
(574, '-', 'Switch Dahua POE 16 puertos', 1, 1, 126),
(575, 'DH-IPC-HDW2449Y-S-IL', 'Cámara IP 4MP WizSense Domo', 1, 2, 127),
(576, 'DH-IPC-HFW2449S-S-IL', 'Cámara IP 4MP WizSense Bullet', 1, 2, 127),
(577, 'NVR1108HSS3H', 'Grabador NVR 8CH ', 1, 2, 127),
(578, 'PL4', 'Batería de 12V 4A dLux', 1, 2, 128),
(579, 'TS333S', 'Sirena 12V 30W de 2 tonos', 1, 2, 128),
(580, 'SP5500+', 'Panel de alarma SP5500+', 1, 1, 129),
(581, 'IPC-K3DN-3H0W', 'Cámara IMOU Bullet 3mp', 2, 1, 130),
(582, 'ST2-256-S1', 'MicroSD 256GB', 2, 1, 130),
(583, 'DLX10010070S', 'Caja Plexo 4x4', 2, 1, 130),
(584, 'WI-CPE511H-KIT', 'Kit de antenas 3km', 1, 2, 130),
(585, 'NVR4216EI', 'Grabador NVR 16CH', 1, 2, 131),
(586, 'PS-12VDC6AMP', 'Fuente de Poder 6MAP', 1, 2, 131),
(587, 'PS-12VDC2AMP', 'Fuente de Poder 2AMP', 1, 2, 131),
(588, ' A6W6D6', 'Gabinete 6U', 1, 2, 137),
(589, 'DH-HAC-HFW1239TLMN-IL-A', 'Cámara HAC 2MP HDCVI Bullet', 5, 2, 137),
(590, 'DH-HAC-HDW1239TN-A-LED', 'Cámara HAC 2MP Full Color Domo', 4, 2, 137),
(591, 'DH-IPC-HFW2449S-S-IL', 'Cámara IP 4MP WizSense Bullet', 1, 2, 137),
(592, 'DH-IPC-HFW1439S1-A-LED', 'Cámara IP 4MP Full Color Bullet', 3, 2, 137),
(593, 'DH-XVR1B16 -I', 'Grabador XVR 16CH', 1, 2, 137),
(594, 'HDD1000', 'Disco Duro 1 TB', 1, 2, 137),
(595, 'HDD2000', 'Disco Duro 2 TB', 1, 2, 137);

-- --------------------------------------------------------

--
-- Table structure for table `hc_estados`
--

CREATE TABLE `hc_estados` (
  `est_id` int NOT NULL,
  `est_detalle` varchar(200) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hc_estados`
--

INSERT INTO `hc_estados` (`est_id`, `est_detalle`) VALUES
(1, 'Inventario Suficiente'),
(2, 'Inventario Advertencia'),
(3, 'Inventario Critico'),
(4, 'Agregar'),
(5, 'Editar'),
(6, 'Eliminar'),
(7, 'categoria Electrónico'),
(8, 'subcategoria Electrónico'),
(9, 'marca Electrónico'),
(10, 'categoria Campo'),
(11, 'subcategoria Campo'),
(12, 'marca Campo'),
(13, 'Asignado - Herramienta'),
(14, 'Categoria Seguridad'),
(15, 'Subcategoria Seguridad'),
(16, 'Asiganado - Seguridad'),
(17, 'Categoria - Gestión Inventario'),
(18, 'Subcategoria - Gestión Inventario'),
(19, 'Marcas - Gestion Inventario'),
(20, 'Proveedores - Gestion Inventario'),
(21, 'Busetas - Gestion Inventario'),
(22, 'Proveedor Activo'),
(23, 'Proveedor Inactivo'),
(24, 'Electronicos Desagrupados'),
(25, 'Empleado activo'),
(26, 'Empleado Inactivo'),
(27, 'Empleado Despedido'),
(28, 'Empleado activo'),
(29, 'Empleado Inactivo'),
(30, 'Empleado Despedido'),
(31, 'Usuario Activo'),
(32, 'Usuario Inactivo'),
(33, 'Sin asignar herramienta'),
(34, 'Sin asignar Seguridad'),
(35, 'Oficial Excelente'),
(36, 'Oficial Atención'),
(37, 'Oficial Crítico');

-- --------------------------------------------------------

--
-- Table structure for table `hc_marcas`
--

CREATE TABLE `hc_marcas` (
  `marc_id` int NOT NULL,
  `marc_detalle` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `marc_est_idEstado` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hc_marcas`
--

INSERT INTO `hc_marcas` (`marc_id`, `marc_detalle`, `marc_est_idEstado`) VALUES
(1, 'Teklink', 9),
(2, 'Dahua', 9),
(3, 'Imou', 9),
(4, 'MikroTik', 9),
(5, 'Ajax', 9),
(6, 'Paradox', 9),
(7, 'Wi-Tek', 9),
(8, 'dLux', 9),
(9, 'Inco', 12),
(14, 'MilkWake', 12);

-- --------------------------------------------------------

--
-- Table structure for table `hc_observaciones_empleados`
--

CREATE TABLE `hc_observaciones_empleados` (
  `obe_id` int NOT NULL,
  `obe_observación` text COLLATE utf8mb4_general_ci NOT NULL,
  `obe_fecha` date NOT NULL,
  `obe_usuario` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `obe_emp_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hc_observaciones_empleados`
--

INSERT INTO `hc_observaciones_empleados` (`obe_id`, `obe_observación`, `obe_fecha`, `obe_usuario`, `obe_emp_id`) VALUES
(1, '<p>Prueba&nbsp;</p>', '2025-05-28', '0227_71a', 0),
(2, '<p>Prueba</p>', '2025-05-28', '0227_71a', 0),
(3, '<p>Prueba</p>', '2025-05-28', '0227_71a', 0),
(4, '<p>Acendido</p>', '2025-05-29', '0227_71a', 0),
(5, '<p>Acendido</p>', '2025-05-29', '0227_71a', 0),
(6, '<p>prueba</p>', '2025-05-29', '0227_71a', 0);

-- --------------------------------------------------------

--
-- Table structure for table `hc_orden`
--

CREATE TABLE `hc_orden` (
  `ord_id` int NOT NULL,
  `ord_codigo` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `ord_fecha` date NOT NULL,
  `ord_tecnico` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `ord_asistente1` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `ord_asistente2` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ord_tipoTrabajo` int NOT NULL,
  `ord_cliente` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `ord_direccion` text COLLATE utf8mb4_general_ci,
  `ord_telefono` varchar(25) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ord_descripcion` text COLLATE utf8mb4_general_ci NOT NULL,
  `ord_estado` int NOT NULL DEFAULT '1',
  `ord_vehiculo_id` int NOT NULL DEFAULT '4'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hc_orden`
--

INSERT INTO `hc_orden` (`ord_id`, `ord_codigo`, `ord_fecha`, `ord_tecnico`, `ord_asistente1`, `ord_asistente2`, `ord_tipoTrabajo`, `ord_cliente`, `ord_direccion`, `ord_telefono`, `ord_descripcion`, `ord_estado`, `ord_vehiculo_id`) VALUES
(16, 'HC-INST-2025-0010', '2025-05-14', 'Joel', 'Steven', '', 2, 'Escuela El Tanque', 'Sonafluca', '62966011', '<p>vvc vc</p>', 0, 4),
(17, 'HC-INST-2025-0011', '2025-05-14', 'Joel', 'Steven', 'Carlos', 1, 'Bike Arenal', 'Fortuna', '8897 5396', '<p>Revisión de sistema de grabación</p>', 0, 4),
(18, 'HC-INST-2025-0010', '2025-05-14', 'Joel', 'Steven', '', 1, 'Escuela El Tanque', 'Sonafluca', '62966011', '<p>&nbsp;xxc cx</p>', 0, 4),
(19, 'ORD-2025-0019', '2025-05-15', 'Joel', 'Steven', '', 1, 'Escuela El Tanque', 'Sonafluca', '62966011', '<p>vfdvfd</p>', 0, 4),
(20, 'ORD-2025-0020', '2025-05-15', 'Joel', 'Steven', 'No', 1, 'Escuela El Tanque', 'Sonafluca', '62966011', '<p>prueba para el pdf</p>', 0, 4),
(21, 'ORD-2025-0021', '2025-05-15', 'Joel', 'Steven', '', 2, 'Soluciones Industriales', 'Fortuna', '8795 4568', '<p>Realización de mantenimiento preventivo y correctivo del sistema de alarma y cámaras de seguridad. Incluye revisión de sensores, verificación de conexiones eléctricas, limpieza de lentes de cámaras, actualización de software, y pruebas funcionales para garantizar el correcto funcionamiento y la seguridad del sistema.</p>', 0, 4),
(23, 'ORD-2025-0023', '2025-05-16', 'Joel', 'Steven', '', 1, 'Asociación La Cruz', 'La Cruz', '8761 7666', '<p>Instalación de Cámaras, prueba</p>', 1, 4),
(25, 'ORD-2025-0025', '2025-05-17', 'Joel', 'Steven', '', 2, 'Villas Josipek', 'El Burrito', '8608 5858', '<p>Limpieza general de sistema de alarma</p>', 1, 4),
(26, 'ORD-2025-0026', '2025-05-17', 'Joel', 'Steven', '', 2, 'Villas Josipek', 'El Burrito', '8608 5858', '<p>Limpieza general de sistemas de alarma</p>', 1, 4),
(27, 'ORD-2025-0027', '2025-05-19', 'Joel', 'Steven', '', 3, 'Agrical ', 'La Fortuna', '8642 7552', '<p>La clienta indica que la imagen de las cámaras no se está visualizando</p>', 1, 4),
(28, 'ORD-2025-0028', '2025-05-20', 'Joel', 'Steven', '', 1, 'Mistico Caseta', 'Catarata de La Fortuna', '-', '<p>Instalación de cámaras - para mejorar equipo interno de la empresa&nbsp;</p>', 1, 4),
(29, 'ORD-2025-0029', '2025-05-21', 'Joel', 'Steven', '', 2, 'Mistico Caseta', 'Catarata de La Fortuna', '', '<p>El grabador presenta problemas de configuración, no guarda las grabaciones</p>', 1, 4),
(30, 'ORD-2025-0030', '2025-05-21', 'Joel', 'Steven', '', 2, 'Ania abarca Bonilla', 'San Isidro', '8512 - 2001', '<p>La clienta presenta problemas con la alarma&nbsp;</p>', 1, 4),
(31, 'ORD-2025-0031', '2025-05-21', 'Joel', 'Steven', '', 2, 'Jairo Villalobos', 'San Isidro', '8921 - 4171', '<p>El cliente presentaba problemas de internet, Steven indica que el cable UTP estaba quemado</p>', 1, 4),
(32, 'ORD-2025-0032', '2025-05-22', 'Joel', 'Steven', '', 2, 'Jiro Industriales', 'San Jorge', '8803 - 1062', '<p>El cliente presenta problemas con la alarma y cámaras</p>', 1, 4),
(33, 'ORD-2025-0033', '2025-06-13', 'Joel', 'Steven', '', 2, 'Colono Ferretero de Upala', 'Upala', '-', '<p>La Cámara del parqueo presenta fallas de imagen, limpieza de cámaras</p>', 1, 4),
(34, 'ORD-2025-0034', '2025-06-13', 'Joel', 'Steven', '', 2, 'Colono Agropecuario Upala', 'Upala', '-', '<p>la cámara rotativa del comedor presenta fallas y se requiere el cambio de la batería interna del panel, limpieza de cámaras<br>&nbsp;</p>', 1, 4),
(35, 'ORD-2025-0035', '2025-06-13', 'Joel', 'Steven', '', 2, 'Colono Bijagua', 'Bijagua', '-', '<p>cámara de la entrada de bodegas y materiales sin imagen, revisar la gemela y realizar limpieza de cámaras<br>&nbsp;</p>', 1, 4),
(36, 'ORD-2025-0036', '2025-06-13', 'Joel', 'Steven', '', 2, 'Colono Guatuso', 'Guatuso', '-', '<p>el grabador B presenta problemas de latencia, y realizar limpieza de cámaras</p>', 1, 4),
(37, 'ORD-2025-0037', '2025-06-13', 'Joel', 'Steven', '', 2, 'Colono Santa Clara ', 'Santa Clara', '-', '<p>La alarma falló, y se validará si se revisa una cámara que presenta problemas de imagen&nbsp;</p>', 1, 4),
(38, 'ORD-2025-0038', '2025-05-26', 'Joel', 'Steven', '', 2, 'Colono Santa Clara ', 'Santa Clara', '-', '<p>Reubicación de cámaras por remodelación de edificio.</p>', 1, 4),
(39, 'ORD-2025-0039', '2025-05-26', 'Joel', 'Steven', '', 2, 'Colono Fortuna', 'La Fortuna', '-', '<p>Reubicación de grabadores rack a la pared</p>', 1, 4),
(40, 'ORD-2025-0040', '2025-05-26', 'Joel', 'Steven', '', 2, 'Cabañas La Fortuna', 'La Fortuna', '8354 9094', '<p>No se visualizan las Cámaras</p>', 1, 4),
(41, 'ORD-2025-0041', '2025-05-26', 'Joel', 'Steven', '', 2, 'Bairon Chaves', 'Florencia', '8640 7066', '<p>Programación y Teclado quemado</p>', 1, 4),
(42, 'ORD-2025-0042', '2025-05-27', 'Joel', 'Steven', '', 2, 'Seg El Bosque', 'La Reserva ', '8776 - 7457', '<p>La clienta necesita una revisión de camaras</p>', 1, 4),
(43, 'ORD-2025-0043', '2025-05-28', 'Joel', 'Steven', 'Carlos', 2, 'Escuela El Tanque', 'Sonafluca', '62966011', '<p>prueba</p>', 1, 4),
(44, 'ORD-2025-0044', '2025-05-30', 'Eduardo', 'Steven', 'Joel', 2, 'Escuela El Tanque', 'Sonafluca', '62966011', '<p>Prueba&nbsp;</p>', 1, 4),
(45, 'ORD-2025-0045', '2025-05-30', 'Joel', 'Steven', '', 2, 'Diego Fernández ', 'Sonafluca', '8326 4005', '<p>El cliente solicitó que le quitaran el panel de la pared</p>', 1, 4),
(46, 'ORD-2025-0046', '2025-05-27', 'Joel', 'Steven', '', 2, 'Comunidad el Bosque', 'El Bosque, Peñas Blancas ', '8776 - 7457', '<p>La clienta presenta fallos en las cámaras</p>', 1, 4),
(47, 'ORD-2025-0047', '2025-05-31', 'Joel', 'Steven', '', 1, 'Adrian Piedra', 'El Bosque, Peñas Blancas ', '8930 9504', '<p>El cliente solicitó una instalación de cámaras</p>', 1, 4),
(48, 'ORD-2025-0048', '2025-05-31', 'Joel', 'Steven', '', 2, 'Colono Santa Clara ', 'Santa Clara', '-', '<p>Se solicitó que se quitaran unas cámaras</p>', 1, 4),
(49, 'ORD-2025-0049', '2025-05-31', 'Joel', 'Steven', '', 2, 'Igesia Gosen', 'El Tanque de La Fortuna', '8844 - 8875', '<p>Revisión y enlace de cámaras</p>', 1, 4),
(50, 'ORD-2025-0050', '2025-05-31', 'Joel', 'Steven', '', 2, 'Acueducto el Tanque ', 'El Tanque de La Fortuna', '8708 - 4803', '<p>Revisión de cámaras</p>', 1, 4),
(51, 'ORD-2025-0051', '2025-06-02', 'Joel', 'Steven', '', 2, 'Colono Fortuna', 'La Fortuna', '-', '<p>Se solicitó por parte de Andrés Lara la reubicación de los grabadores del gabinete a la pared.</p>', 1, 4),
(52, 'ORD-2025-0052', '2025-06-02', 'Joel', 'Steven', '', 2, 'Colono Santa Clara ', 'Santa Clara', '-', '<p>Quitar cámaras de la pared</p>', 1, 4),
(53, 'ORD-2025-0053', '2025-06-02', 'Joel', 'Steven', '', 2, 'Colono Chachagua', 'Chachagua', '-', '<p>Se quemó el puerto de HDMI 3</p>', 1, 4),
(54, 'ORD-2025-0054', '2025-06-03', 'Joel', 'Steven', '', 2, 'Super Alex', 'Fortuna', '6405 - 9817', '<p>El cliente indica que la señal del grabador es inconsistente</p>', 1, 4),
(55, 'ORD-2025-0055', '2025-06-04', 'Joel', 'Sin Asistente', '', 2, 'Super Alex', 'El Bosque', '6405 - 9817', '<p>Posiblemente, se requiere el cambio del grabador por fallo de que se había instalado previamente, si el cambio del grabador es requerido este está cubierto por la garantía.</p>', 1, 4),
(56, 'ORD-2025-0056', '2025-06-05', 'Joel', 'Steven', 'Greivin', 1, 'Noelia', 'La Guaria, La Fortuna', '8707 - 9724', '<p>Instalación de cámaras</p>', 1, 4),
(57, 'ORD-2025-0057', '2025-06-05', 'Eduardo', 'Sin Asistente', '', 3, '-', '-', '-', '<p>Programación de routers</p>', 1, 4),
(58, 'ORD-2025-0058', '2025-06-05', 'Joel', 'Steven', 'Carlos', 2, 'Villas Josipek', 'Sonafluca', '62966011', '<p>prueba</p>', 1, 4),
(67, 'ORD-2025-0059', '2025-06-07', 'Joel', 'Steven', '', 2, 'Juan Gabriel Araya', 'San Isidro ', '8840 6288', '<p>Configuración de Aplicación de Alarma</p>', 1, 4),
(68, 'ORD-2025-0068', '2025-06-07', 'Joel', 'Steven', '', 2, 'Nuria', 'Boca Arenal', '8428 7684', '<p>Cámaras sin imagen</p>', 1, 4),
(69, 'ORD-2025-0069', '2025-06-09', 'Joel', 'Steven', '', 2, 'Fabian Finca', 'Los Ángeles', '8946 - 1655', '<p>El cliente informa que tiene 3 zonas abiertas</p>', 1, 4),
(70, 'ORD-2025-0070', '2025-06-09', 'Eduardo', 'Joel', 'Steven', 2, 'Arabigos', 'La Fortuna', '8555 - 8166', '<p>Soventar problemas de alarma</p>', 1, 4),
(71, 'ORD-2025-0071', '2025-06-09', 'Joel', 'Steven', '', 2, 'Montaña Mágica ', 'La Fortuna', '-', '<p>Cámaras sin imagen</p>', 1, 4),
(72, 'ORD-2025-0072', '2025-06-09', 'fg', 'fgbfg', 'bfgb', 1, 'bgfb', 'bfgb', 'fgbgf', '<p>prueba&nbsp;</p>', 1, 4),
(73, 'ORD-2025-0073', '2025-06-10', 'Joel', 'Steven', '', 1, 'Seg Agua Azul', 'Agua Azul ', '-', '<p>Instalación de Cámaras</p>', 1, 4),
(74, 'ORD-2025-0074', '2025-06-11', 'Eduardo', 'Joel', 'Steven', 3, 'Alonso Sibaja', 'La Bruja ', '8571 - 0887', '<p>Revisión de cámaras por fallo de imagen</p>', 1, 4),
(75, 'ORD-2025-0075', '2025-06-11', 'Eduardo', 'Joel', 'Steven', 2, 'Montaña Mágica ', 'La Fortuna', '-', '<p>Fallo de cámaras&nbsp;</p>', 1, 4),
(76, 'ORD-2025-0076', '2025-06-11', 'Eduardo', 'Joel', 'Steven', 2, 'Arenal Hills', 'Los Ángeles', '8657 4812', '<p>Instalación de antenas</p>', 1, 4),
(77, 'ORD-2025-0077', '2025-06-11', 'Eduardo', 'Joel', 'Steven', 3, 'Fabian Finca', 'Los Ángeles', '8946 - 1655', '<p>Revisión de la alarma&nbsp;</p>', 1, 4),
(78, 'ORD-2025-0078', '2025-06-12', 'Eduardo', 'Joel', '', 3, 'Colono La Fortuna', 'La Fortuna', '-', '<p>Cambiar canales de las cámaras en el grabador</p>', 1, 4),
(79, 'ORD-2025-0079', '2025-06-12', 'Eduardo', 'Joel', '', 2, 'Arenal Waterfall', 'La Catarata La Fortuna', '-', '<p>Instalacion de Switch&nbsp;</p>', 1, 4),
(80, 'ORD-2025-0080', '2025-06-14', 'Eduardo', 'Joel', 'Steven', 2, 'David Arias', 'San Isidro de La Fortuna', '8826 - 9575', '<p>Reconfiguración de la IP de las cámaras</p>', 1, 4),
(81, 'ORD-2025-0081', '2025-06-14', 'Joel', 'Steven', '', 2, 'Colono Chachagua', 'Chachagua', '-', '<p>Colocar un extensor HDMI para que se visualice la pantalla donde llega la imagen de las cámaras correctamente</p>', 1, 4),
(82, 'ORD-2025-0082', '2025-06-14', 'Joel', 'Steven', '', 2, 'Asoc. La Cruz', 'La Cruz', '8761 - 7666', '<p>Revisión del grabador&nbsp;</p>', 1, 4),
(83, 'ORD-2025-0083', '2025-06-16', 'Eduardo', 'Joel', 'Steven', 2, 'Tour el Perezoso', 'La Fortuna', '8890 - 0993', '<p>Revisión general de los sistemas, cambio de grabador de 32CH, programación e instalación de routers</p>', 1, 4),
(84, 'ORD-2025-0084', '2025-06-19', 'Eduardo', 'Joel', 'Steven', 2, 'Doña Vivian', 'La Fortuna', '8325 - 5946', '<p>Dar solución a problemas de internet&nbsp;</p>', 1, 4),
(85, 'ORD-2025-0085', '2025-06-20', 'Joel', 'Steven', '', 1, 'Asada Agua Azul', 'Agua Azul, La Fortuna', '8884 - 4464', '<p>Instalar cámaras</p>', 1, 4),
(86, 'ORD-2025-0086', '2025-06-20', 'Eduardo', 'Sin Asistente', '', 2, 'Adrian Piedra', 'El Bosque', '8930 - 9504', '<p>Enlazar cámaras, realizar cotización.</p> \r\n<p>A demás el cliente informa que se pierden o no se graban los últimos minutos de la mayoría de las horas del día</p> ', 1, 5),
(87, 'ORD-2025-0087', '2025-06-20', 'Eduardo', 'Sin Asistente', '', 2, 'Asada Sonafluca', 'Sonafluca', '8644 1765', '<p>Programación&nbsp;</p>', 1, 5),
(88, 'ORD-2025-0088', '2025-06-20', 'Eduardo', 'Sin Asistente', '', 2, 'Colono Tanque', 'El Tanque', '-', '<p>Cotizacion de cámaras&nbsp;</p>', 1, 5),
(89, 'ORD-2025-0089', '2025-06-24', 'Eduardo', 'Joel', '', 1, 'Arenal Hills', 'La Fortuna', '8657 - 4812', '<p>Instalación de Antenas</p>', 1, 4),
(90, 'ORD-2025-0090', '2025-06-25', 'Eduardo', 'Steven', '', 2, 'Bairon Chaves', 'Florencia', '8640 - 7066', '<ol><li>Conectar zona a teclado</li><li>actualizar el tiempo de entrada</li></ol>', 1, 4),
(91, 'ORD-2025-0091', '2025-06-25', 'Eduardo', 'Steven', '', 2, 'Villas Josipek', 'El Burrito', '8608 - 5858', '<p>Actualización de alarmas</p>', 1, 4),
(92, 'ORD-2025-0092', '2025-06-25', 'Eduardo', 'Steven', '', 2, 'Colono Santa Clara ', 'Santa Clara', '-', '<p><strong>COMPRAR TUBO CUADRADO EN 1.8</strong></p>', 1, 4),
(93, 'ORD-2025-0093', '2025-06-26', 'Eduardo', 'Steven', '', 2, 'David Arias', 'San Jorge', '8826 - 9575', '<p>Cambio de panel</p>', 1, 4),
(94, 'ORD-2025-0094', '2025-06-26', 'Eduardo', 'Steven', '', 2, 'Pablo Badilla', 'El Bosque', '6085 - 1434', '<p>Problemas con la configuración de la aplicación</p>', 1, 4),
(95, 'ORD-2025-0095', '2025-06-27', 'Eduardo', 'Sin Asistente', '', 2, 'Arenal Hills', 'Los Ángeles', '8657 - 4812', '<p>Actualización de alarma, e instalación de panel</p>', 1, 4),
(96, 'ORD-2025-0096', '2025-06-30', 'Joel', 'Steven', '', 1, 'Erick Pereira', 'San Isidro', '7206 - 1718', '<p>Instalación de Alarma Paradox, Instalar y configurar aplicación en los celulares indicados por el cliente, dejar configuración prevista para enlazar alarma a la central de monitoreo.</p>', 1, 4),
(97, 'ORD-2025-0097', '2025-06-30', 'Joel', 'Steven', '', 2, 'Pablo Badilla', 'El Bosque', '6085 - 1434', '<p>Revisión de aplicación y cámara</p>', 1, 4),
(98, 'ORD-2025-0098', '2025-06-30', 'Eduardo', 'Joel', '', 2, 'Fabian Bolaños', 'Los Ángeles', '8946 - 1655', '<p>Revisión de panel de alarma</p>', 1, 4),
(99, 'ORD-2025-0099', '2025-07-01', 'Eduardo', 'Joel', 'Steven', 1, 'Perezoso', 'La Fortuna', '-', '<p>Programación, instalación de HDMI y routers. Entrega de trabajo.</p>', 1, 4),
(101, 'ORD-2025-0101', '2025-07-03', 'Joel', 'Steven', '', 2, 'Noelia Lecheria', 'La Guaria', '8707 - 9724', '<p>Reubicación, instalación y visualización de imagen en pantalla</p>', 1, 4),
(102, 'ORD-2025-0102', '2025-07-03', 'Eduardo', 'Sin Asistente', '', 2, 'Colono La Fortuna', 'La Fortuna', '', '<p>Reiniciar cámaras (Área Ingco - Agropecuario) // Cambio de batería de Panel</p>', 1, 3),
(103, 'ORD-2025-0103', '2025-07-03', 'Eduardo', 'Sin Asistente', '', 2, 'Asada Sonafluca', 'Sonafluca', '8644 - 7165', '<p>Programación</p>', 1, 3),
(104, 'ORD-2025-0104', '2025-07-03', 'Eduardo', 'Joel', '', 2, 'Taller Colono', 'Los Ángeles', '8603 - 0388', '<p>Revisión de cámara</p>', 1, 4),
(105, 'ORD-2025-0105', '2025-07-04', 'Eduardo', 'Joel', '', 2, 'Blockera ', 'Los Ángeles', '-', '<p>Enlazar cámara Imou a la central</p>', 1, 4),
(106, 'ORD-2025-0106', '2025-07-04', 'Eduardo', 'Joel', '', 2, 'Christian Mora ', '-', '8380 - 1767', '<p>Cambio de baterías de teclado de alarma Ajax.</p>', 1, 4),
(107, 'ORD-2025-0107', '2025-07-04', 'Eduardo', 'Joel', '', 2, 'Ingrid', 'La Fortuna', '8395 - 1025', '<p>Actualizar alarma de Magdalena, esperar confirmación de oficina para realizar trabajo</p>', 1, 4),
(108, 'ORD-2025-0108', '2025-07-05', 'Eduardo', 'Joel', '', 2, 'Bike Arenal', 'La Fortuna', '7104 - 3315', '<p>Programación de cámaras y finalización del trabajo</p>', 1, 4),
(109, 'ORD-2025-0109', '2025-07-07', 'Eduardo', 'Joel', '', 2, 'Super Alex', 'El Bosque', '6405 - 9817', '<p>Actualización de panel de alarma</p>', 1, 4),
(110, 'ORD-2025-0110', '2025-07-07', 'Eduardo', 'Joel', 'Steven', 2, 'Colono Fortuna ', 'La Fortuna', '-', '<p>Revisión de cámaras</p>', 1, 4),
(111, 'ORD-2025-0111', '2025-07-07', 'Eduardo', 'Joel', 'Steven', 2, 'Mistico Caseta', 'La Catarata La Fortuna', '-', '<p>instalacion de switch</p>', 1, 4),
(112, 'ORD-2025-0112', '2025-07-08', 'Joel', 'Steven', '', 2, 'Moto Bike', 'Florencia', '8845 - 9165', '<p>Revisión de sistema de cámaras</p>', 1, 4),
(113, 'ORD-2025-0113', '2025-07-08', 'Joel', 'Steven', '', 2, 'Colono Santa Clara ', 'Santa Clara', '-', '<p>Reubicación de cámaras</p>', 1, 4),
(114, 'ORD-2025-0114', '2025-07-08', 'Eduardo', 'Steven', '', 2, 'Erick Pereira', 'San Isidro, La Pechuga', '7206 - 1718', '<p>Programación de IP, traer SN para enlazar alarma a la central de monitoreo. (Esperar confirmación de oficina - Cliente aún no confirma -)</p>', 1, 4),
(115, 'ORD-2025-0115', '2025-07-08', 'Joel', 'Steven', '', 2, 'Alberto Tiqucia ', 'La Fortuna', '8589 - 9951', '<ol><li>Instalación de <i>gabinete 6U</i></li><li>Prueba para validar que el <strong>pdf </strong>muestra bien el html</li></ol>', 1, 4),
(116, 'ORD-2025-0116', '2025-07-09', 'Joel', 'Steven', '', 2, 'Colono El Tanque', 'El Tanque, La Fortuna', '8848 - 2337', '<p>Reubicar cámara del grabador “B” de la bodega 2; Revisar cámaras [Caja Ferretería - Área de acabados - Artículos del hogar - Pasillo - Caja Rápida]</p>', 1, 4),
(117, 'ORD-2025-0117', '2025-07-09', 'Joel', 'Steven', '', 2, 'Erick Pereira', 'La Pechuga, San Isidro ', '7206 - 1718', '<p>Programación y reubicación de cable coaxial instalado por coopelesca</p>', 1, 4),
(118, 'ORD-2025-0118', '2025-07-10', 'Eduardo', 'Joel', 'Steven', 2, 'Sergio Chacón ', 'Guatuso', '8335 - 8903', '<p>Seguimiento de Trabajo</p>', 1, 4),
(119, 'ORD-2025-0119', '2025-07-12', 'Joel', 'Steven', '', 2, 'Erick Pereira', 'La Pechuga, San Isidro ', '7206 - 1718', '<p>Reubicación de cable coaxial instalado previamente por Coopelesca, instalación de gabinete, instalación de ups</p>', 1, 4),
(120, 'ORD-2025-0120', '2025-07-14', 'Joel', 'Kervin', '', 1, 'Hermana Marvin ', 'Limon', '-', '<p>Instalación de cámaras</p>', 1, 4),
(121, 'ORD-2025-0121', '2025-07-14', 'Eduardo', 'Steven', '', 2, 'ASADA El Tanque', 'El Tanque', '8708 - 4803', '<p>Problemas de visualización de las cámaras.</p>', 1, 3),
(122, 'ORD-2025-0122', '2025-07-14', 'Eduardo', 'Steven', '', 2, 'Carlos Villas del Bosque', 'San Isidro', '8782 - 0583', '<p>Realizar cotización para cámaras</p>', 1, 3),
(123, 'ORD-2025-0123', '2025-07-14', 'Eduardo', 'Steven', '', 2, 'Asada Sonafluca', 'Sonafluca', '8644 - 7165', '<p>Actualización de alarma</p>', 1, 3),
(124, 'ORD-2025-0124', '2025-07-15', 'Eduardo', 'Steven', '', 2, 'El Perezoso', 'La Fortuna', '8667 - 3842', '<p>Revisión del sistema de cámaras</p>', 1, 4),
(125, 'ORD-2025-0125', '2025-07-15', 'Joel', 'Steven', '', 1, 'Priscilla', 'Mistico', '-', '<p>Instalación de cámaras</p>', 1, 4),
(126, 'ORD-2025-0126', '2025-07-16', 'Eduardo', 'Joel', 'Steven', 2, 'Sergio Chacón ', 'Guatuso', '-', '<p>Continuar trabajos</p>', 1, 4),
(127, 'ORD-2025-0127', '2025-07-19', 'Eduardo', 'Joel', '', 2, 'Mistico, Priscilla', 'La Fortuna ', '-', '<ol><li>Instalación</li><li>Programación</li></ol>', 1, 4),
(128, 'ORD-2025-0128', '2025-07-19', 'Eduardo', 'Joel', '', 2, 'Ana Lucía', 'Peñas Blancas', '8379 - 7192', '<p>Revision de alarma</p>', 1, 4),
(129, 'ORD-2025-0129', '2025-07-21', 'Joel', 'Steven', '', 2, 'Ana Lucía', 'San Isidro, Peñas Blancas', '8379 7192', '<p>Cambio de panel, se indica por <strong>Joel</strong> que en la visita anterior se realizó el cambio de la batería de la alarma.</p>', 1, 4),
(130, 'ORD-2025-0130', '2025-07-21', 'Joel', 'Steven', '', 1, 'Juan José Rodríguez', 'El Tanque de La Fortuna', '8399 - 8820', '<p>Instalación de cámaras tipo bullet de Imou, se solicita que se conecten las cámaras por medio de la <strong>red Wi-Fi</strong></p>', 1, 4),
(131, 'ORD-2025-0131', '2025-07-21', 'Joel', 'Steven', '', 2, 'Res Tiquicia', 'La Fortuna', '8589 - 9951', '<p>El cliente indica que el grabador está presentando problemas, ya que no se ven las cámaras, el mismo indica que apagó y encendió el grabador y se resolvió el grabador temporalmente, pero se volvió a presentar el mismo problema</p>', 1, 4),
(132, 'ORD-2025-0132', '2025-07-21', 'Eduardo', 'Sin Asistente', '', 2, 'Rest. Perezoso ', 'La Fortuna', '8667 - 3842', '<p>Programación</p>', 1, 3),
(133, 'ORD-2025-0133', '2025-07-22', 'Joel', 'Steven', '', 1, 'Marvin Castro', 'Zarcero', '-', '<p>Instalación de cámaras ip</p>', 1, 4),
(134, 'ORD-2025-0134', '2025-07-23', 'Eduardo', 'Steven', '', 2, 'Rest. Perezoso ', 'La Fortuna', '-', '<p>Programación de red</p>', 1, 4),
(135, 'ORD-2025-0135', '2025-07-23', 'Eduardo', 'Sin Asistente', '', 2, 'Colono El Tanque', 'El Tanque de La Fortuna', '-', '<p>Configuración de la aplicación de las cámaras</p>', 1, 4),
(136, 'ORD-2025-0136', '2025-07-23', 'Eduardo', 'Sin Asistente', '', 2, 'ASADA El Tanque', 'El Tanque, La Fortuna', '-', '<p>Configuración de la aplicación de las cámaras</p>', 1, 4),
(137, 'ORD-2025-0137', '2025-07-24', 'Eduardo', 'Joel ', 'Steven', 2, 'Sergio', 'Guatuso ', '8535 - 8903', '<p>Seguimiento de trabajo, 3° semana</p>', 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `hc_proveedor`
--

CREATE TABLE `hc_proveedor` (
  `prov_id` int NOT NULL,
  `prov_nombre` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `prov_empresa` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `prov_direccion` text COLLATE utf8mb4_general_ci NOT NULL,
  `prov_cambio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hc_proveedor`
--

INSERT INTO `hc_proveedor` (`prov_id`, `prov_nombre`, `prov_empresa`, `prov_direccion`, `prov_cambio`) VALUES
(1, 'Juan Pérez', 'Electro S.A.', 'Avenida Central, San José', 520.50),
(2, 'Comercial Repuestos', 'CR Repuestos Ltda.', 'Zona Industrial La Uruca', 540.50),
(3, 'Alberston Escobar', 'Sekunet', 'San José', 505.22),
(4, 'Daniel Altamarino', 'Tectel TECNOLOGIA TELEFONICA S.A', 'Zona Industrial Pavas, Avenida 9', 506.74);

-- --------------------------------------------------------

--
-- Table structure for table `hc_proveedores`
--

CREATE TABLE `hc_proveedores` (
  `prov_id` int NOT NULL,
  `prov_empresa` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `prov_identificacion` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prov_telefono` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prov_correo` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prov_direccion` text COLLATE utf8mb4_general_ci,
  `prov_contacto_nombre` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prov_contacto_telefono` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prov_contacto_correo` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prov_moneda_preferida` varchar(60) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prov_condiciones_pago` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `activo` tinyint DEFAULT '21',
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hc_proveedores`
--

INSERT INTO `hc_proveedores` (`prov_id`, `prov_empresa`, `prov_identificacion`, `prov_telefono`, `prov_correo`, `prov_direccion`, `prov_contacto_nombre`, `prov_contacto_telefono`, `prov_contacto_correo`, `prov_moneda_preferida`, `prov_condiciones_pago`, `activo`, `fecha_creacion`) VALUES
(1, 'TECTEL', '3-101-402758', '8546 4141', '-', '500m Oeste de Oficinas Pizza Hut, Zona Industrial Pavas Av.9', 'Lucía Rojas', '8546 4141', '-', 'colones', 'contado', 21, '2025-05-08 11:29:39'),
(2, 'INTRADE', '3-101-064398-23', '8721 8999', '-', 'San José, Costa Rica, la Uruca 200 metros suroeste del puente Juan Pablo II.', 'Mauricio Alpizar Martinez', '8721 8999', '-', 'colones', 'contado', 22, '2025-05-08 11:51:25'),
(4, 'Eurocomp', '-', '6276 - 8861', '-', ' Heredia. De la estación de tren Santa Rosa, 500 mts Oeste y 800 mts Sur, Heredia, Santo Domingo, 40306', 'Maria Paula', '6276 - 8861', '-', 'colones', 'contado', 21, '2025-07-08 10:06:24'),
(5, 'Sekunet', '68456', '8678 - 3872', '-', 'San José', 'Lusi Gomez', '8678 - 3872', '-', 'colones', 'contado', 21, '2025-07-14 16:02:48'),
(6, 'Lamprosa', '-', '6240 - 1717', '-', 'San José ', 'Monica', '6240 - 1717', '-', 'colones', 'contado', 21, '2025-07-14 16:04:54');

-- --------------------------------------------------------

--
-- Table structure for table `hc_roles`
--

CREATE TABLE `hc_roles` (
  `rol_id` int NOT NULL,
  `rol_detalle` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `rol_dep_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hc_roles`
--

INSERT INTO `hc_roles` (`rol_id`, `rol_detalle`, `rol_dep_id`) VALUES
(1, 'Técnico', 6),
(2, 'Asistente', 6),
(3, 'Gerente general', 5),
(4, 'Gerente contabilidad', 5),
(5, 'Director', 5),
(6, 'Oficial', 7),
(7, 'Monitoreo', 7),
(8, 'Supervisor', 7),
(9, 'Supervisor de Monitoreo', 8),
(10, 'Operador de Monitoreo', 8);

-- --------------------------------------------------------

--
-- Table structure for table `hc_seguridad`
--

CREATE TABLE `hc_seguridad` (
  `segd_id` int NOT NULL,
  `scat_cantidad` int NOT NULL,
  `segd_detalle` text COLLATE utf8mb4_general_ci NOT NULL,
  `segd_condicion` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `segd_empl_IDempleado` int NOT NULL,
  `segd_catg_IDcategoria` int NOT NULL,
  `segd_scat_IDsubcategoria` int NOT NULL,
  `segd_estado` int NOT NULL DEFAULT '34'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hc_seguridad`
--

INSERT INTO `hc_seguridad` (`segd_id`, `scat_cantidad`, `segd_detalle`, `segd_condicion`, `segd_empl_IDempleado`, `segd_catg_IDcategoria`, `segd_scat_IDsubcategoria`, `segd_estado`) VALUES
(2, 10, 'Camiseta [S] maga corta de hombre', 'Nuevo', 0, 5, 7, 34),
(3, 1, 'Chaleco [completo] ', 'Nuevo', 19, 7, 12, 16),
(4, 10, 'Jacket Negra [L]', 'Nuevo', 0, 5, 8, 34),
(5, 3, 'Camiseta [M] maga Larga de hombre', 'Nuevo', 0, 5, 7, 34),
(6, 2, 'Camiseta [M] maga Larga de hombre', 'Nuevo', 23, 5, 7, 16);

-- --------------------------------------------------------

--
-- Table structure for table `hc_subcategoria`
--

CREATE TABLE `hc_subcategoria` (
  `scat_id` int NOT NULL,
  `scat_detalle` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `scat_catg_catgPadre` int NOT NULL,
  `scat_est_idEstado` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hc_subcategoria`
--

INSERT INTO `hc_subcategoria` (`scat_id`, `scat_detalle`, `scat_catg_catgPadre`, `scat_est_idEstado`) VALUES
(1, 'Sensores', 1, 8),
(2, 'Cámaras', 2, 8),
(3, 'Routers', 3, 8),
(4, 'Switches', 3, 8),
(5, 'Destornillador', 4, 11),
(6, 'Martillos', 4, 11),
(7, 'Trabajo', 5, 15),
(8, 'Guarda', 5, 15),
(9, 'Complementos', 5, 15),
(10, 'Linternas', 6, 15),
(11, 'Bastones', 7, 15),
(12, 'Anti-Balas', 7, 15),
(13, 'Subcat prueba 1', 4, 11),
(16, 'Antenas', 3, 8),
(18, 'Almacenamiento', 2, 8),
(19, 'Accesorios', 2, 8),
(20, 'Fuentes', 18, 8),
(21, 'Grabadores', 2, 8),
(22, 'UPS', 18, 8),
(23, 'Paneles ', 1, 8),
(24, 'Modulos de comunicación', 1, 8),
(25, 'Repetidor de alarma ', 1, 8),
(26, 'Adaptador de alarma', 1, 8),
(27, 'Modulos de comunicación', 1, 8),
(28, 'Sensores', 1, 8),
(29, 'Controloes', 1, 8),
(30, 'Botones', 1, 8),
(31, 'Contacto de puerta ', 1, 8),
(32, 'Teclado', 1, 8),
(33, 'Sirenas', 1, 8),
(34, 'Otros', 18, 8),
(35, 'Cable de Red', 18, 8),
(36, 'Cable de Alarma', 18, 8),
(37, 'Cable coaxial', 18, 8),
(38, 'Rotulasción General', 19, 8);

-- --------------------------------------------------------

--
-- Table structure for table `hc_tipolicencia`
--

CREATE TABLE `hc_tipolicencia` (
  `tla_id` int NOT NULL,
  `tla_detalle` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hc_usuarios`
--

CREATE TABLE `hc_usuarios` (
  `user_id` int NOT NULL,
  `user_emp_id` int NOT NULL,
  `user_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `user_password` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `user_nivelAcceso` int NOT NULL,
  `user_observacion` int DEFAULT NULL,
  `user_estado` int NOT NULL DEFAULT '31'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hc_usuarios`
--

INSERT INTO `hc_usuarios` (`user_id`, `user_emp_id`, `user_name`, `user_password`, `user_nivelAcceso`, `user_observacion`, `user_estado`) VALUES
(1, 13, 'admin', 'admin123', 1, 0, 32),
(8, 18, '0227_71a', '$2y$10$/AS9Dijz5ge5DVSP6ZctNekdtuQm5QxXaPC00mfTgPIAXuRwRu51S', 1, 0, 31),
(9, 19, 'nreb_uqc', '$2y$10$dbMfM04vb0SXEMnCtDY8f..VCRi0MN/M2heEWGB/iusbk1Y6cXAZu', 2, 6, 31),
(11, 20, 'tlaw_em3', '$2y$10$dwPyt6DV3dI58JYGAKOKduaIXurtE6cWk/6Za4qqc07ktG7L9dJYu', 3, 0, 31),
(12, 22, 'usr_5a8284', '$2y$10$Vyqib1JN9Ey/s58K0cTF4OYb5z/ITblRgSaN2IRTkdPuierzvQE92', 1, NULL, 31);

-- --------------------------------------------------------

--
-- Table structure for table `hc_vehiculos`
--

CREATE TABLE `hc_vehiculos` (
  `veh_id` int NOT NULL,
  `veh_placa` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `veh_marca` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `veh_modelo` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `veh_anio` int NOT NULL,
  `veh_color` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `veh_tipo` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `veh_num_chasis` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `veh_num_motor` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `veh_kilometraje` int DEFAULT '0',
  `veh_fecha_vencimiento_seguro` date DEFAULT NULL,
  `veh_fecha_revision` date DEFAULT NULL,
  `veh_observaciones` text COLLATE utf8mb4_general_ci,
  `veh_fecha_registro` date DEFAULT NULL,
  `veh_estado` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hc_vehiculos`
--

INSERT INTO `hc_vehiculos` (`veh_id`, `veh_placa`, `veh_marca`, `veh_modelo`, `veh_anio`, `veh_color`, `veh_tipo`, `veh_num_chasis`, `veh_num_motor`, `veh_kilometraje`, `veh_fecha_vencimiento_seguro`, `veh_fecha_revision`, `veh_observaciones`, `veh_fecha_registro`, `veh_estado`) VALUES
(2, 'BPL887', 'nhgnhg', 'nhgnhg', 857, 'nhhg', '2', 'hnhg', 'nghn', 2025, '2024-05-06', '2024-05-06', '<p>bgf</p>', '2025-05-08', 22),
(3, '310695', 'Chevrolet', 'N300 Max', 2018, 'Blanco', '1', 'LZWCCAGA2JE600464', 'LAQUHB0120418', 108946, '2026-01-15', '2025-05-22', '<p>…</p>', '2025-05-08', 21),
(4, '347747', 'Chevrolet', 'N400 MAX', 2024, 'Blanco', '1', '-', '-', 20889, '2026-07-22', '2025-07-10', '<p>Buseta de Instalación</p>', '2025-06-09', 21),
(5, '361167', 'Chevrolet', 'N400 Max', 2024, 'Blanco', '1', '5614', '656', 665156, '2025-07-10', '2025-07-25', '<p>…</p>', '2025-06-20', 21);

-- --------------------------------------------------------

--
-- Table structure for table `reporte_oficial`
--

CREATE TABLE `reporte_oficial` (
  `reof_id` int NOT NULL,
  `reof_motivo` text COLLATE utf8mb4_general_ci NOT NULL,
  `reof_justificacion` text COLLATE utf8mb4_general_ci NOT NULL,
  `reof_emp_id` int NOT NULL,
  `reof_bitacora` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reporte_oficial`
--

INSERT INTO `reporte_oficial` (`reof_id`, `reof_motivo`, `reof_justificacion`, `reof_emp_id`, `reof_bitacora`) VALUES
(1, 'insert de la bd para pruebas', 'insert de la bd para pruebas', 24, 'Insertado por Maikel el 16/6/25 a las 16:24'),
(2, '<p>prueba</p>', '<p>prueba</p>', 24, 'Insertado por: Maikel, a las: 2025-06-19 09:48:02'),
(3, '<p>hola mundo lol</p>', '<p>hola mundo apalusa</p>', 23, 'Editado por: Maikel, a las: 2025-06-19 14:15:33');

-- --------------------------------------------------------

--
-- Table structure for table `vehiculos`
--

CREATE TABLE `vehiculos` (
  `veh_id` int NOT NULL,
  `veh_placa` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `veh_marca` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `veh_modelo` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `veh_anio` int NOT NULL,
  `veh_color` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `veh_tipo` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `veh_num_chasis` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `veh_num_motor` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `veh_kilometraje` int DEFAULT '0',
  `veh_fecha_vencimiento_seguro` date DEFAULT NULL,
  `veh_fecha_revision` date DEFAULT NULL,
  `veh_observaciones` text COLLATE utf8mb4_general_ci,
  `veh_fecha_registro` date DEFAULT NULL,
  `veh_estado` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_campoasignado`
-- (See below for the actual view)
--
CREATE TABLE `v_campoasignado` (
`camp_id` int
,`camp_cantidad` int
,`camp_detalle` text
,`camp_marca` varchar(100)
,`camp_catg_idCategoria` int
,`camp_scat_idSubcategoria` int
,`camp_empo_idEmpleado` int
,`emp_id` int
,`emp_nombre` varchar(60)
,`emp_apellidos` varchar(120)
,`emp_cedula` varchar(20)
,`emp_telefono` varchar(20)
,`emp_correo` varchar(50)
,`emp_direccion` text
,`emp_fechaIngreso` date
,`emp_cuenta` text
,`emp_codigo` varchar(15)
,`emp_foto` varchar(255)
,`emp_carnetAgente` date
,`emp_carnetArma` date
,`emp_testPsicologico` date
,`emp_huellas` date
,`emp_vacaciones` varchar(25)
,`emp_licencias` varchar(255)
,`emp_obd_id` int
,`emp_rol_id` int
,`emp_dep_id` int
,`emp_estado` int
,`catg_id` int
,`catg_detalle` varchar(60)
,`catg_est_idEstado` int
,`scat_id` int
,`scat_detalle` varchar(60)
,`scat_catg_catgPadre` int
,`scat_est_idEstado` int
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_campo_general`
-- (See below for the actual view)
--
CREATE TABLE `v_campo_general` (
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_campo_sinasignar`
-- (See below for the actual view)
--
CREATE TABLE `v_campo_sinasignar` (
`camp_id` int
,`camp_cantidad` int
,`camp_detalle` text
,`camp_marca` varchar(100)
,`camp_catg_idCategoria` int
,`camp_scat_idSubcategoria` int
,`camp_empo_idEmpleado` int
,`catg_id` int
,`catg_detalle` varchar(60)
,`catg_est_idEstado` int
,`scat_id` int
,`scat_detalle` varchar(60)
,`scat_catg_catgPadre` int
,`scat_est_idEstado` int
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_categoria`
-- (See below for the actual view)
--
CREATE TABLE `v_categoria` (
`catg_id` int
,`catg_detalle` varchar(60)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_categoriacampo`
-- (See below for the actual view)
--
CREATE TABLE `v_categoriacampo` (
`catg_id` int
,`catg_detalle` varchar(60)
,`catg_est_idEstado` int
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_categoriaseguridad`
-- (See below for the actual view)
--
CREATE TABLE `v_categoriaseguridad` (
`catg_id` int
,`catg_detalle` varchar(60)
,`catg_est_idEstado` int
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_coti_lastid`
-- (See below for the actual view)
--
CREATE TABLE `v_coti_lastid` (
`last_id` int
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_electronico`
-- (See below for the actual view)
--
CREATE TABLE `v_electronico` (
`elec_id` int
,`elec_stock` int
,`elec_detalle` text
,`elec_marca` varchar(100)
,`elec_codigo` varchar(100)
,`elec_cantMin` int
,`elec_precioDolar` decimal(10,2)
,`elec_porv_IDdolar` int
,`elec_precio` decimal(10,2)
,`elec_porcentaje` decimal(10,2)
,`elec_precioTotal` decimal(10,2)
,`elec_prov_IDproveedor` int
,`elec_catg_IDcategoria` int
,`elec_scat_IDsubcategoria` int
,`prov_id` int
,`prov_nombre` varchar(200)
,`prov_empresa` varchar(200)
,`prov_direccion` text
,`prov_cambio` decimal(10,2)
,`catg_id` int
,`catg_detalle` varchar(60)
,`scat_id` int
,`scat_detalle` varchar(60)
,`scat_catg_catgPadre` int
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_electronicoadvertencia`
-- (See below for the actual view)
--
CREATE TABLE `v_electronicoadvertencia` (
`elec_id` int
,`elec_stock` int
,`elec_detalle` text
,`elec_marca` varchar(100)
,`elec_codigo` varchar(100)
,`elec_cantMin` int
,`elec_precioDolar` decimal(10,2)
,`elec_porv_IDdolar` int
,`elec_precio` decimal(10,2)
,`elec_porcentaje` decimal(10,2)
,`elec_precioTotal` decimal(10,2)
,`elec_prov_IDproveedor` int
,`elec_catg_IDcategoria` int
,`elec_scat_IDsubcategoria` int
,`prov_id` int
,`prov_nombre` varchar(200)
,`prov_empresa` varchar(200)
,`prov_direccion` text
,`prov_cambio` decimal(10,2)
,`catg_id` int
,`catg_detalle` varchar(60)
,`scat_id` int
,`scat_detalle` varchar(60)
,`scat_catg_catgPadre` int
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_electronicocritico`
-- (See below for the actual view)
--
CREATE TABLE `v_electronicocritico` (
`elec_id` int
,`elec_stock` int
,`elec_detalle` text
,`elec_marca` varchar(100)
,`elec_codigo` varchar(100)
,`elec_cantMin` int
,`elec_precioDolar` decimal(10,2)
,`elec_porv_IDdolar` int
,`elec_precio` decimal(10,2)
,`elec_porcentaje` decimal(10,2)
,`elec_precioTotal` decimal(10,2)
,`elec_prov_IDproveedor` int
,`elec_catg_IDcategoria` int
,`elec_scat_IDsubcategoria` int
,`prov_id` int
,`prov_nombre` varchar(200)
,`prov_empresa` varchar(200)
,`prov_direccion` text
,`prov_cambio` decimal(10,2)
,`catg_id` int
,`catg_detalle` varchar(60)
,`scat_id` int
,`scat_detalle` varchar(60)
,`scat_catg_catgPadre` int
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_electronicogeneral`
-- (See below for the actual view)
--
CREATE TABLE `v_electronicogeneral` (
`elec_id` int
,`elec_stock` int
,`elec_detalle` text
,`elec_marca` varchar(100)
,`elec_codigo` varchar(100)
,`elec_cantMin` int
,`elec_precioDolar` decimal(10,2)
,`elec_porv_IDdolar` int
,`elec_precio` decimal(10,2)
,`elec_porcentaje` decimal(10,2)
,`elec_precioTotal` decimal(10,2)
,`elec_prov_IDproveedor` int
,`elec_catg_IDcategoria` int
,`elec_scat_IDsubcategoria` int
,`prov_id` int
,`prov_nombre` varchar(200)
,`prov_empresa` varchar(200)
,`prov_direccion` text
,`prov_cambio` decimal(10,2)
,`catg_id` int
,`catg_detalle` varchar(60)
,`scat_id` int
,`scat_detalle` varchar(60)
,`scat_catg_catgPadre` int
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_electronicos_agrupados`
-- (See below for the actual view)
--
CREATE TABLE `v_electronicos_agrupados` (
`elec_codigo` varchar(50)
,`elec_detalle` text
,`elec_stock` decimal(32,0)
,`elec_cantMin` int
,`elec_buffer` int
,`elec_precio` decimal(14,6)
,`elec_precioTotal` decimal(14,6)
,`catg_detalle` varchar(60)
,`scat_detalle` varchar(60)
,`estado_promedio` int
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_empleadoseguridad`
-- (See below for the actual view)
--
CREATE TABLE `v_empleadoseguridad` (
`emp_id` int
,`emp_nombre` varchar(60)
,`emp_apellidos` varchar(120)
,`emp_cedula` varchar(20)
,`emp_telefono` varchar(20)
,`emp_correo` varchar(50)
,`emp_direccion` text
,`emp_fechaIngreso` date
,`emp_cuenta` text
,`emp_codigo` varchar(15)
,`emp_foto` varchar(255)
,`emp_carnetAgente` date
,`emp_carnetArma` date
,`emp_testPsicologico` date
,`emp_huellas` date
,`emp_vacaciones` varchar(25)
,`emp_licencias` varchar(255)
,`emp_obd_id` int
,`emp_rol_id` int
,`emp_dep_id` int
,`emp_estado` int
,`dep_id` int
,`dep_detalle` varchar(80)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_empleados_general`
-- (See below for the actual view)
--
CREATE TABLE `v_empleados_general` (
`emp_id` int
,`emp_nombre` varchar(60)
,`emp_apellidos` varchar(120)
,`emp_cedula` varchar(20)
,`emp_telefono` varchar(20)
,`emp_correo` varchar(50)
,`emp_direccion` text
,`emp_salario` decimal(10,2)
,`emp_fechaIngreso` date
,`emp_cuenta` text
,`emp_codigo` varchar(15)
,`emp_foto` varchar(255)
,`emp_carnetAgente` date
,`emp_carnetArma` date
,`emp_testPsicologico` date
,`emp_huellas` date
,`emp_delta` varchar(100)
,`emp_puesto` varchar(150)
,`emp_vacaciones` varchar(25)
,`emp_licencias` varchar(255)
,`emp_obd_id` int
,`emp_rol_id` int
,`emp_dep_id` int
,`emp_estado` int
,`dep_id` int
,`dep_detalle` varchar(80)
,`rol_id` int
,`rol_detalle` varchar(150)
,`rol_dep_id` int
,`obe_id` int
,`obe_observación` text
,`obe_fecha` date
,`obe_usuario` varchar(150)
,`obe_emp_id` int
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_empleado_campo`
-- (See below for the actual view)
--
CREATE TABLE `v_empleado_campo` (
`emp_id` int
,`emp_nombre` varchar(60)
,`emp_apellidos` varchar(120)
,`emp_cedula` varchar(20)
,`emp_telefono` varchar(20)
,`emp_correo` varchar(50)
,`emp_direccion` text
,`emp_fechaIngreso` date
,`emp_cuenta` text
,`emp_codigo` varchar(15)
,`emp_foto` varchar(255)
,`emp_carnetAgente` date
,`emp_carnetArma` date
,`emp_testPsicologico` date
,`emp_huellas` date
,`emp_vacaciones` varchar(25)
,`emp_licencias` varchar(255)
,`emp_obd_id` int
,`emp_rol_id` int
,`emp_dep_id` int
,`emp_estado` int
,`dep_id` int
,`dep_detalle` varchar(80)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_equipos_coti`
-- (See below for the actual view)
--
CREATE TABLE `v_equipos_coti` (
`cteq_id` int
,`cteq_detalle` text
,`cteq_can` int
,`cteq_precio` decimal(10,0)
,`cteq_iva` int
,`cteq_descuento` int
,`cteq_subtotal` decimal(10,2)
,`cteq_sub_iva` decimal(10,2)
,`cteq_sub_desc` decimal(10,2)
,`cteq_total_linea` decimal(10,2)
,`cteq_coti_id` int
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_gestion_marca`
-- (See below for the actual view)
--
CREATE TABLE `v_gestion_marca` (
`marc_id` int
,`marc_detalle` varchar(60)
,`marc_est_idEstado` int
,`est_id` int
,`est_detalle` varchar(200)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_gestion_proveedores`
-- (See below for the actual view)
--
CREATE TABLE `v_gestion_proveedores` (
`prov_id` int
,`prov_empresa` varchar(255)
,`prov_identificacion` varchar(50)
,`prov_telefono` varchar(50)
,`prov_correo` varchar(100)
,`prov_direccion` text
,`prov_contacto_nombre` varchar(100)
,`prov_contacto_telefono` varchar(50)
,`prov_contacto_correo` varchar(100)
,`prov_moneda_preferida` varchar(60)
,`prov_condiciones_pago` varchar(100)
,`activo` tinyint
,`fecha_creacion` datetime
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_gestion_vehiculo`
-- (See below for the actual view)
--
CREATE TABLE `v_gestion_vehiculo` (
`veh_id` int
,`veh_placa` varchar(20)
,`veh_marca` varchar(50)
,`veh_modelo` varchar(50)
,`veh_anio` int
,`veh_color` varchar(30)
,`veh_tipo` varchar(30)
,`veh_num_chasis` varchar(50)
,`veh_num_motor` varchar(50)
,`veh_kilometraje` int
,`veh_fecha_vencimiento_seguro` date
,`veh_fecha_revision` date
,`veh_observaciones` text
,`veh_fecha_registro` date
,`veh_estado` int
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_get_categoriagestion`
-- (See below for the actual view)
--
CREATE TABLE `v_get_categoriagestion` (
`catg_id` int
,`catg_detalle` varchar(60)
,`catg_est_idEstado` int
,`est_id` int
,`est_detalle` varchar(200)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_get_colaborador_despedido`
-- (See below for the actual view)
--
CREATE TABLE `v_get_colaborador_despedido` (
`emp_id` int
,`emp_nombre` varchar(60)
,`emp_apellidos` varchar(120)
,`emp_cedula` varchar(20)
,`emp_telefono` varchar(20)
,`emp_correo` varchar(50)
,`emp_direccion` text
,`emp_fechaIngreso` date
,`emp_cuenta` text
,`emp_codigo` varchar(15)
,`emp_foto` varchar(255)
,`emp_carnetAgente` date
,`emp_carnetArma` date
,`emp_testPsicologico` date
,`emp_huellas` date
,`emp_vacaciones` varchar(25)
,`emp_licencias` varchar(255)
,`emp_obd_id` int
,`emp_rol_id` int
,`emp_dep_id` int
,`emp_estado` int
,`dep_id` int
,`dep_detalle` varchar(80)
,`obe_id` int
,`obe_observación` text
,`obe_fecha` date
,`obe_usuario` varchar(150)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_get_colaborador_inactivo`
-- (See below for the actual view)
--
CREATE TABLE `v_get_colaborador_inactivo` (
`emp_id` int
,`emp_nombre` varchar(60)
,`emp_apellidos` varchar(120)
,`emp_cedula` varchar(20)
,`emp_telefono` varchar(20)
,`emp_correo` varchar(50)
,`emp_direccion` text
,`emp_fechaIngreso` date
,`emp_cuenta` text
,`emp_codigo` varchar(15)
,`emp_foto` varchar(255)
,`emp_carnetAgente` date
,`emp_carnetArma` date
,`emp_testPsicologico` date
,`emp_huellas` date
,`emp_vacaciones` varchar(25)
,`emp_licencias` varchar(255)
,`emp_obd_id` int
,`emp_rol_id` int
,`emp_dep_id` int
,`emp_estado` int
,`dep_id` int
,`dep_detalle` varchar(80)
,`obe_id` int
,`obe_observación` text
,`obe_fecha` date
,`obe_usuario` varchar(150)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_get_cotizaciones`
-- (See below for the actual view)
--
CREATE TABLE `v_get_cotizaciones` (
`cot_id` int
,`cot_codigo` varchar(60)
,`cot_vendor` varchar(60)
,`cot_cliente` varchar(100)
,`cot_telefono` varchar(30)
,`cot_fecha1` date
,`cot_fecha2` date
,`cot_subtotal` decimal(10,2)
,`cot_iva` decimal(10,2)
,`cot_descuento` decimal(10,2)
,`cot_total` decimal(10,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_get_departamento`
-- (See below for the actual view)
--
CREATE TABLE `v_get_departamento` (
`dep_id` int
,`dep_detalle` varchar(80)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_get_electronicos_general`
-- (See below for the actual view)
--
CREATE TABLE `v_get_electronicos_general` (
`elec_id` int
,`elec_stok` int
,`elec_detalle` text
,`elec_marca` varchar(100)
,`elec_codigo` varchar(50)
,`elec_cantMin` int
,`elec_precio_prov` decimal(10,2)
,`elec_utilidad` decimal(10,2)
,`elec_total` decimal(10,2)
,`elec_prov_id` int
,`elec_catg_id` int
,`elec_scat_id` int
,`elec_est_id` int
,`elec_fact_consecutivo` int
,`elec_buffer` int
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_get_empleado_activo`
-- (See below for the actual view)
--
CREATE TABLE `v_get_empleado_activo` (
`emp_id` int
,`emp_nombre` varchar(60)
,`emp_apellidos` varchar(120)
,`emp_cedula` varchar(20)
,`emp_telefono` varchar(20)
,`emp_correo` varchar(50)
,`emp_direccion` text
,`emp_salario` decimal(10,2)
,`emp_fechaIngreso` date
,`emp_cuenta` text
,`emp_codigo` varchar(15)
,`emp_foto` varchar(255)
,`emp_carnetAgente` date
,`emp_carnetArma` date
,`emp_testPsicologico` date
,`emp_huellas` date
,`emp_delta` varchar(100)
,`emp_puesto` varchar(150)
,`emp_vacaciones` varchar(25)
,`emp_licencias` varchar(255)
,`emp_obd_id` int
,`emp_rol_id` int
,`emp_dep_id` int
,`emp_estado` int
,`dep_id` int
,`dep_detalle` varchar(80)
,`rol_id` int
,`rol_detalle` varchar(150)
,`rol_dep_id` int
,`obe_id` int
,`obe_observación` text
,`obe_fecha` date
,`obe_usuario` varchar(150)
,`obe_emp_id` int
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_get_equipos_orden`
-- (See below for the actual view)
--
CREATE TABLE `v_get_equipos_orden` (
`erd_id` int
,`erd_codigo` varchar(150)
,`erd_descripcion` varchar(250)
,`erd_cantidad` int
,`erd_tipo` int
,`erd_orden_id` int
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_get_estadosgestion`
-- (See below for the actual view)
--
CREATE TABLE `v_get_estadosgestion` (
`est_id` int
,`est_detalle` varchar(200)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_get_reportes_oficiales_general`
-- (See below for the actual view)
--
CREATE TABLE `v_get_reportes_oficiales_general` (
`emp_id` int
,`nombre` varchar(181)
,`emp_cedula` varchar(20)
,`emp_delta` varchar(100)
,`emp_puesto` varchar(150)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_get_reportes_oficiales_todos`
-- (See below for the actual view)
--
CREATE TABLE `v_get_reportes_oficiales_todos` (
`reof_id` int
,`reof_motivo` text
,`reof_justificacion` text
,`reof_emp_id` int
,`reof_bitacora` varchar(255)
,`emp_id` int
,`emp_nombre` varchar(60)
,`emp_apellidos` varchar(120)
,`emp_cedula` varchar(20)
,`emp_telefono` varchar(20)
,`emp_correo` varchar(50)
,`emp_direccion` text
,`emp_salario` decimal(10,2)
,`emp_fechaIngreso` date
,`emp_cuenta` text
,`emp_codigo` varchar(15)
,`emp_foto` varchar(255)
,`emp_carnetAgente` date
,`emp_carnetArma` date
,`emp_testPsicologico` date
,`emp_huellas` date
,`emp_delta` varchar(100)
,`emp_puesto` varchar(150)
,`emp_vacaciones` varchar(25)
,`emp_licencias` varchar(255)
,`emp_obd_id` int
,`emp_rol_id` int
,`emp_dep_id` int
,`emp_estado` int
,`emp_estado_supervision` int
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_get_rol`
-- (See below for the actual view)
--
CREATE TABLE `v_get_rol` (
`rol_id` int
,`rol_detalle` varchar(150)
,`rol_dep_id` int
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_get_subcategoriagestion`
-- (See below for the actual view)
--
CREATE TABLE `v_get_subcategoriagestion` (
`scat_id` int
,`scat_detalle` varchar(60)
,`scat_catg_catgPadre` int
,`scat_est_idEstado` int
,`categoria_nombre` varchar(60)
,`estado_nombre` varchar(200)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_get_usuario_general`
-- (See below for the actual view)
--
CREATE TABLE `v_get_usuario_general` (
`user_id` int
,`user_emp_id` int
,`user_name` varchar(255)
,`user_password` varchar(255)
,`user_nivelAcceso` int
,`user_estado` int
,`emp_id` int
,`emp_nombre` varchar(60)
,`emp_apellidos` varchar(120)
,`emp_cedula` varchar(20)
,`emp_telefono` varchar(20)
,`emp_correo` varchar(50)
,`emp_direccion` text
,`emp_fechaIngreso` date
,`emp_cuenta` text
,`emp_codigo` varchar(15)
,`emp_foto` varchar(255)
,`emp_carnetAgente` date
,`emp_carnetArma` date
,`emp_testPsicologico` date
,`emp_huellas` date
,`emp_vacaciones` varchar(25)
,`emp_licencias` varchar(255)
,`emp_obd_id` int
,`emp_rol_id` int
,`emp_dep_id` int
,`emp_estado` int
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_get_usuario_inactivo`
-- (See below for the actual view)
--
CREATE TABLE `v_get_usuario_inactivo` (
`user_id` int
,`user_emp_id` int
,`user_name` varchar(255)
,`user_password` varchar(255)
,`user_nivelAcceso` int
,`user_estado` int
,`emp_id` int
,`emp_nombre` varchar(60)
,`emp_apellidos` varchar(120)
,`emp_cedula` varchar(20)
,`emp_telefono` varchar(20)
,`emp_correo` varchar(50)
,`emp_direccion` text
,`emp_fechaIngreso` date
,`emp_cuenta` text
,`emp_codigo` varchar(15)
,`emp_foto` varchar(255)
,`emp_carnetAgente` date
,`emp_carnetArma` date
,`emp_testPsicologico` date
,`emp_huellas` date
,`emp_vacaciones` varchar(25)
,`emp_licencias` varchar(255)
,`emp_obd_id` int
,`emp_rol_id` int
,`emp_dep_id` int
,`emp_estado` int
,`acs_id` int
,`acs_nombre` varchar(60)
,`acs_detalle` text
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_get_vehiculos_busetas`
-- (See below for the actual view)
--
CREATE TABLE `v_get_vehiculos_busetas` (
`veh_id` int
,`veh_placa` varchar(20)
,`veh_marca` varchar(50)
,`veh_modelo` varchar(50)
,`veh_anio` int
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_marcas`
-- (See below for the actual view)
--
CREATE TABLE `v_marcas` (
`marc_id` int
,`marc_detalle` varchar(60)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_marcascampo`
-- (See below for the actual view)
--
CREATE TABLE `v_marcascampo` (
`marc_id` int
,`marc_detalle` varchar(60)
,`marc_est_idEstado` int
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_nivel_acceso`
-- (See below for the actual view)
--
CREATE TABLE `v_nivel_acceso` (
`acs_id` int
,`acs_nombre` varchar(60)
,`acs_detalle` text
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_orden_lastid`
-- (See below for the actual view)
--
CREATE TABLE `v_orden_lastid` (
`lastID` int
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_orden_trabajo`
-- (See below for the actual view)
--
CREATE TABLE `v_orden_trabajo` (
`ord_id` int
,`ord_codigo` varchar(100)
,`ord_fecha` date
,`ord_tecnico` varchar(100)
,`ord_asistente1` varchar(100)
,`ord_asistente2` varchar(100)
,`ord_tipoTrabajo` int
,`ord_cliente` varchar(150)
,`ord_direccion` text
,`ord_telefono` varchar(25)
,`ord_descripcion` text
,`ord_estado` int
,`erd_id` int
,`erd_codigo` varchar(150)
,`erd_descripcion` varchar(250)
,`erd_cantidad` int
,`erd_tipo` int
,`erd_orden_id` int
,`ord_vehiculo_id` int
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_proveedor`
-- (See below for the actual view)
--
CREATE TABLE `v_proveedor` (
`prov_id` int
,`prov_nombre` varchar(200)
,`prov_empresa` varchar(200)
,`prov_direccion` text
,`prov_cambio` decimal(10,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_proveedores`
-- (See below for the actual view)
--
CREATE TABLE `v_proveedores` (
`prov_id` int
,`prov_empresa` varchar(255)
,`prov_identificacion` varchar(50)
,`prov_telefono` varchar(50)
,`prov_correo` varchar(100)
,`prov_direccion` text
,`prov_contacto_nombre` varchar(100)
,`prov_contacto_telefono` varchar(50)
,`prov_contacto_correo` varchar(100)
,`prov_moneda_preferida` varchar(60)
,`prov_condiciones_pago` varchar(100)
,`activo` tinyint
,`fecha_creacion` datetime
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_seguridadcategoria`
-- (See below for the actual view)
--
CREATE TABLE `v_seguridadcategoria` (
`catg_id` int
,`catg_detalle` varchar(60)
,`catg_est_idEstado` int
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_seguridad_equipoasigando`
-- (See below for the actual view)
--
CREATE TABLE `v_seguridad_equipoasigando` (
`segd_id` int
,`scat_cantidad` int
,`segd_detalle` text
,`segd_condicion` varchar(20)
,`segd_empl_IDempleado` int
,`segd_catg_IDcategoria` int
,`segd_scat_IDsubcategoria` int
,`segd_estado` int
,`emp_id` int
,`emp_nombre` varchar(60)
,`emp_apellidos` varchar(120)
,`emp_cedula` varchar(20)
,`emp_telefono` varchar(20)
,`emp_correo` varchar(50)
,`emp_direccion` text
,`emp_fechaIngreso` date
,`emp_cuenta` text
,`emp_codigo` varchar(15)
,`emp_foto` varchar(255)
,`emp_carnetAgente` date
,`emp_carnetArma` date
,`emp_testPsicologico` date
,`emp_huellas` date
,`emp_vacaciones` varchar(25)
,`emp_licencias` varchar(255)
,`emp_obd_id` int
,`emp_rol_id` int
,`emp_dep_id` int
,`emp_estado` int
,`catg_id` int
,`catg_detalle` varchar(60)
,`catg_est_idEstado` int
,`scat_id` int
,`scat_detalle` varchar(60)
,`scat_catg_catgPadre` int
,`scat_est_idEstado` int
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_seguridad_sinasignar`
-- (See below for the actual view)
--
CREATE TABLE `v_seguridad_sinasignar` (
`segd_id` int
,`scat_cantidad` int
,`segd_detalle` text
,`segd_condicion` varchar(20)
,`segd_empl_IDempleado` int
,`segd_catg_IDcategoria` int
,`segd_scat_IDsubcategoria` int
,`catg_id` int
,`catg_detalle` varchar(60)
,`catg_est_idEstado` int
,`scat_id` int
,`scat_detalle` varchar(60)
,`scat_catg_catgPadre` int
,`scat_est_idEstado` int
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_subcategoria`
-- (See below for the actual view)
--
CREATE TABLE `v_subcategoria` (
`scat_id` int
,`scat_detalle` varchar(60)
,`scat_catg_catgPadre` int
,`catg_id` int
,`catg_detalle` varchar(60)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_subcategoriacampo`
-- (See below for the actual view)
--
CREATE TABLE `v_subcategoriacampo` (
`scat_id` int
,`scat_detalle` varchar(60)
,`scat_catg_catgPadre` int
,`catg_id` int
,`catg_detalle` varchar(60)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_subcategoriaseguridad`
-- (See below for the actual view)
--
CREATE TABLE `v_subcategoriaseguridad` (
`scat_id` int
,`scat_detalle` varchar(60)
,`scat_catg_catgPadre` int
,`catg_id` int
,`catg_detalle` varchar(60)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_usuarios_activos`
-- (See below for the actual view)
--
CREATE TABLE `v_usuarios_activos` (
`user_id` int
,`user_emp_id` int
,`user_name` varchar(255)
,`user_password` varchar(255)
,`user_nivelAcceso` int
,`user_estado` int
,`emp_id` int
,`emp_nombre` varchar(60)
,`emp_apellidos` varchar(120)
,`emp_cedula` varchar(20)
,`emp_telefono` varchar(20)
,`emp_correo` varchar(50)
,`emp_direccion` text
,`emp_fechaIngreso` date
,`emp_cuenta` text
,`emp_codigo` varchar(15)
,`emp_foto` varchar(255)
,`emp_carnetAgente` date
,`emp_carnetArma` date
,`emp_testPsicologico` date
,`emp_huellas` date
,`emp_vacaciones` varchar(25)
,`emp_licencias` varchar(255)
,`emp_obd_id` int
,`emp_rol_id` int
,`emp_dep_id` int
,`emp_estado` int
,`acs_id` int
,`acs_nombre` varchar(60)
,`acs_detalle` text
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_vehiculo`
-- (See below for the actual view)
--
CREATE TABLE `v_vehiculo` (
`veh_id` int
,`veh_placa` varchar(20)
,`veh_marca` varchar(50)
,`veh_modelo` varchar(50)
,`veh_anio` int
,`veh_color` varchar(30)
,`veh_tipo` varchar(30)
,`veh_num_chasis` varchar(50)
,`veh_num_motor` varchar(50)
,`veh_kilometraje` int
,`veh_fecha_vencimiento_seguro` date
,`veh_fecha_revision` date
,`veh_observaciones` text
,`veh_fecha_registro` date
,`veh_estado` int
);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hc_acseso`
--
ALTER TABLE `hc_acseso`
  ADD PRIMARY KEY (`acs_id`);

--
-- Indexes for table `hc_campo`
--
ALTER TABLE `hc_campo`
  ADD PRIMARY KEY (`camp_id`);

--
-- Indexes for table `hc_categoria`
--
ALTER TABLE `hc_categoria`
  ADD PRIMARY KEY (`catg_id`);

--
-- Indexes for table `hc_cotizaciones`
--
ALTER TABLE `hc_cotizaciones`
  ADD PRIMARY KEY (`cot_id`);

--
-- Indexes for table `hc_coti_equipo`
--
ALTER TABLE `hc_coti_equipo`
  ADD PRIMARY KEY (`cteq_id`);

--
-- Indexes for table `hc_departamento`
--
ALTER TABLE `hc_departamento`
  ADD PRIMARY KEY (`dep_id`);

--
-- Indexes for table `hc_electronico`
--
ALTER TABLE `hc_electronico`
  ADD PRIMARY KEY (`elec_id`),
  ADD KEY `elec_prov_IDproveedor` (`elec_prov_IDproveedor`,`elec_catg_IDcategoria`,`elec_scat_IDsubcategoria`),
  ADD KEY `elec_catg_IDcategoria` (`elec_catg_IDcategoria`),
  ADD KEY `elec_scat_IDsubcategoria` (`elec_scat_IDsubcategoria`),
  ADD KEY `elec_cambio_IDcambioDolar` (`elec_porv_IDdolar`);

--
-- Indexes for table `hc_electronicos`
--
ALTER TABLE `hc_electronicos`
  ADD PRIMARY KEY (`elec_id`);

--
-- Indexes for table `hc_empleados`
--
ALTER TABLE `hc_empleados`
  ADD PRIMARY KEY (`emp_id`),
  ADD KEY `emp_dep_id` (`emp_dep_id`);

--
-- Indexes for table `hc_equipos_orden`
--
ALTER TABLE `hc_equipos_orden`
  ADD PRIMARY KEY (`erd_id`);

--
-- Indexes for table `hc_estados`
--
ALTER TABLE `hc_estados`
  ADD PRIMARY KEY (`est_id`);

--
-- Indexes for table `hc_marcas`
--
ALTER TABLE `hc_marcas`
  ADD PRIMARY KEY (`marc_id`);

--
-- Indexes for table `hc_observaciones_empleados`
--
ALTER TABLE `hc_observaciones_empleados`
  ADD PRIMARY KEY (`obe_id`);

--
-- Indexes for table `hc_orden`
--
ALTER TABLE `hc_orden`
  ADD PRIMARY KEY (`ord_id`);

--
-- Indexes for table `hc_proveedor`
--
ALTER TABLE `hc_proveedor`
  ADD PRIMARY KEY (`prov_id`);

--
-- Indexes for table `hc_proveedores`
--
ALTER TABLE `hc_proveedores`
  ADD PRIMARY KEY (`prov_id`);

--
-- Indexes for table `hc_roles`
--
ALTER TABLE `hc_roles`
  ADD PRIMARY KEY (`rol_id`);

--
-- Indexes for table `hc_seguridad`
--
ALTER TABLE `hc_seguridad`
  ADD PRIMARY KEY (`segd_id`);

--
-- Indexes for table `hc_subcategoria`
--
ALTER TABLE `hc_subcategoria`
  ADD PRIMARY KEY (`scat_id`),
  ADD KEY `scat_catg_catgPadre` (`scat_catg_catgPadre`);

--
-- Indexes for table `hc_tipolicencia`
--
ALTER TABLE `hc_tipolicencia`
  ADD PRIMARY KEY (`tla_id`);

--
-- Indexes for table `hc_usuarios`
--
ALTER TABLE `hc_usuarios`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `user_emp_id` (`user_emp_id`) USING BTREE;

--
-- Indexes for table `hc_vehiculos`
--
ALTER TABLE `hc_vehiculos`
  ADD PRIMARY KEY (`veh_id`);

--
-- Indexes for table `reporte_oficial`
--
ALTER TABLE `reporte_oficial`
  ADD PRIMARY KEY (`reof_id`);

--
-- Indexes for table `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`veh_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hc_acseso`
--
ALTER TABLE `hc_acseso`
  MODIFY `acs_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `hc_campo`
--
ALTER TABLE `hc_campo`
  MODIFY `camp_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `hc_categoria`
--
ALTER TABLE `hc_categoria`
  MODIFY `catg_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `hc_cotizaciones`
--
ALTER TABLE `hc_cotizaciones`
  MODIFY `cot_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `hc_coti_equipo`
--
ALTER TABLE `hc_coti_equipo`
  MODIFY `cteq_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `hc_departamento`
--
ALTER TABLE `hc_departamento`
  MODIFY `dep_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `hc_electronico`
--
ALTER TABLE `hc_electronico`
  MODIFY `elec_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `hc_electronicos`
--
ALTER TABLE `hc_electronicos`
  MODIFY `elec_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT for table `hc_empleados`
--
ALTER TABLE `hc_empleados`
  MODIFY `emp_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `hc_equipos_orden`
--
ALTER TABLE `hc_equipos_orden`
  MODIFY `erd_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=596;

--
-- AUTO_INCREMENT for table `hc_estados`
--
ALTER TABLE `hc_estados`
  MODIFY `est_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `hc_marcas`
--
ALTER TABLE `hc_marcas`
  MODIFY `marc_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `hc_observaciones_empleados`
--
ALTER TABLE `hc_observaciones_empleados`
  MODIFY `obe_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `hc_orden`
--
ALTER TABLE `hc_orden`
  MODIFY `ord_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- AUTO_INCREMENT for table `hc_proveedor`
--
ALTER TABLE `hc_proveedor`
  MODIFY `prov_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hc_proveedores`
--
ALTER TABLE `hc_proveedores`
  MODIFY `prov_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `hc_roles`
--
ALTER TABLE `hc_roles`
  MODIFY `rol_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `hc_seguridad`
--
ALTER TABLE `hc_seguridad`
  MODIFY `segd_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `hc_subcategoria`
--
ALTER TABLE `hc_subcategoria`
  MODIFY `scat_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `hc_tipolicencia`
--
ALTER TABLE `hc_tipolicencia`
  MODIFY `tla_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hc_usuarios`
--
ALTER TABLE `hc_usuarios`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `hc_vehiculos`
--
ALTER TABLE `hc_vehiculos`
  MODIFY `veh_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reporte_oficial`
--
ALTER TABLE `reporte_oficial`
  MODIFY `reof_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `vehiculos`
--
ALTER TABLE `vehiculos`
  MODIFY `veh_id` int NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------

--
-- Structure for view `v_campoasignado`
--
DROP TABLE IF EXISTS `v_campoasignado`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_campoasignado`  AS SELECT `hc_campo`.`camp_id` AS `camp_id`, `hc_campo`.`camp_cantidad` AS `camp_cantidad`, `hc_campo`.`camp_detalle` AS `camp_detalle`, `hc_campo`.`camp_marca` AS `camp_marca`, `hc_campo`.`camp_catg_idCategoria` AS `camp_catg_idCategoria`, `hc_campo`.`camp_scat_idSubcategoria` AS `camp_scat_idSubcategoria`, `hc_campo`.`camp_empo_idEmpleado` AS `camp_empo_idEmpleado`, `hc_empleados`.`emp_id` AS `emp_id`, `hc_empleados`.`emp_nombre` AS `emp_nombre`, `hc_empleados`.`emp_apellidos` AS `emp_apellidos`, `hc_empleados`.`emp_cedula` AS `emp_cedula`, `hc_empleados`.`emp_telefono` AS `emp_telefono`, `hc_empleados`.`emp_correo` AS `emp_correo`, `hc_empleados`.`emp_direccion` AS `emp_direccion`, `hc_empleados`.`emp_fechaIngreso` AS `emp_fechaIngreso`, `hc_empleados`.`emp_cuenta` AS `emp_cuenta`, `hc_empleados`.`emp_codigo` AS `emp_codigo`, `hc_empleados`.`emp_foto` AS `emp_foto`, `hc_empleados`.`emp_carnetAgente` AS `emp_carnetAgente`, `hc_empleados`.`emp_carnetArma` AS `emp_carnetArma`, `hc_empleados`.`emp_testPsicologico` AS `emp_testPsicologico`, `hc_empleados`.`emp_huellas` AS `emp_huellas`, `hc_empleados`.`emp_vacaciones` AS `emp_vacaciones`, `hc_empleados`.`emp_licencias` AS `emp_licencias`, `hc_empleados`.`emp_obd_id` AS `emp_obd_id`, `hc_empleados`.`emp_rol_id` AS `emp_rol_id`, `hc_empleados`.`emp_dep_id` AS `emp_dep_id`, `hc_empleados`.`emp_estado` AS `emp_estado`, `hc_categoria`.`catg_id` AS `catg_id`, `hc_categoria`.`catg_detalle` AS `catg_detalle`, `hc_categoria`.`catg_est_idEstado` AS `catg_est_idEstado`, `hc_subcategoria`.`scat_id` AS `scat_id`, `hc_subcategoria`.`scat_detalle` AS `scat_detalle`, `hc_subcategoria`.`scat_catg_catgPadre` AS `scat_catg_catgPadre`, `hc_subcategoria`.`scat_est_idEstado` AS `scat_est_idEstado` FROM (((`hc_campo` join `hc_empleados` on((`hc_campo`.`camp_empo_idEmpleado` = `hc_empleados`.`emp_id`))) join `hc_categoria` on((`hc_campo`.`camp_catg_idCategoria` = `hc_categoria`.`catg_id`))) join `hc_subcategoria` on((`hc_campo`.`camp_scat_idSubcategoria` = `hc_subcategoria`.`scat_id`))) WHERE (`hc_campo`.`camp_estado` = 13) ;

-- --------------------------------------------------------

--
-- Structure for view `v_campo_general`
--
DROP TABLE IF EXISTS `v_campo_general`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_campo_general`  AS SELECT `hc_campo`.`camp_id` AS `camp_id`, `hc_campo`.`camp_cantidad` AS `camp_cantidad`, `hc_campo`.`camp_detalle` AS `camp_detalle`, `hc_campo`.`camp_marca` AS `camp_marca`, `hc_campo`.`camp_catg_idCategoria` AS `camp_catg_idCategoria`, `hc_campo`.`camp_scat_idSubcategoria` AS `camp_scat_idSubcategoria`, `hc_campo`.`camp_empo_idEmpleado` AS `camp_empo_idEmpleado`, `hc_empleados`.`emp_id` AS `emp_id`, `hc_empleados`.`emp_nombre` AS `emp_nombre`, `hc_empleados`.`emp_apellidos` AS `emp_apellidos`, `hc_empleados`.`emp_cedula` AS `emp_cedula`, `hc_empleados`.`emp_telefono` AS `emp_telefono`, `hc_empleados`.`emp_correo` AS `emp_correo`, `hc_empleados`.`emp_direccion` AS `emp_direccion`, `hc_empleados`.`emp_fechaIngreso` AS `emp_fechaIngreso`, `hc_empleados`.`emp_cuenta` AS `emp_cuenta`, `hc_empleados`.`emp_codigo` AS `emp_codigo`, `hc_empleados`.`emp_foto` AS `emp_foto`, `hc_empleados`.`emp_agente` AS `emp_agente`, `hc_empleados`.`emp_arma` AS `emp_arma`, `hc_empleados`.`emp_vacaciones` AS `emp_vacaciones`, `hc_empleados`.`emp_lic_id` AS `emp_lic_id`, `hc_empleados`.`emp_obd_id` AS `emp_obd_id`, `hc_empleados`.`emp_rol_id` AS `emp_rol_id`, `hc_empleados`.`emp_dep_id` AS `emp_dep_id`, `hc_categoria`.`catg_id` AS `catg_id`, `hc_categoria`.`catg_detalle` AS `catg_detalle`, `hc_categoria`.`catg_est_idEstado` AS `catg_est_idEstado`, `hc_subcategoria`.`scat_id` AS `scat_id`, `hc_subcategoria`.`scat_detalle` AS `scat_detalle`, `hc_subcategoria`.`scat_catg_catgPadre` AS `scat_catg_catgPadre`, `hc_subcategoria`.`scat_est_idEstado` AS `scat_est_idEstado` FROM (((`hc_campo` join `hc_empleados` on((`hc_empleados`.`emp_id` = `hc_campo`.`camp_empo_idEmpleado`))) join `hc_categoria` on((`hc_categoria`.`catg_id` = `hc_campo`.`camp_catg_idCategoria`))) join `hc_subcategoria` on((`hc_subcategoria`.`scat_id` = `hc_campo`.`camp_scat_idSubcategoria`))) ;

-- --------------------------------------------------------

--
-- Structure for view `v_campo_sinasignar`
--
DROP TABLE IF EXISTS `v_campo_sinasignar`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_campo_sinasignar`  AS SELECT `hc_campo`.`camp_id` AS `camp_id`, `hc_campo`.`camp_cantidad` AS `camp_cantidad`, `hc_campo`.`camp_detalle` AS `camp_detalle`, `hc_campo`.`camp_marca` AS `camp_marca`, `hc_campo`.`camp_catg_idCategoria` AS `camp_catg_idCategoria`, `hc_campo`.`camp_scat_idSubcategoria` AS `camp_scat_idSubcategoria`, `hc_campo`.`camp_empo_idEmpleado` AS `camp_empo_idEmpleado`, `hc_categoria`.`catg_id` AS `catg_id`, `hc_categoria`.`catg_detalle` AS `catg_detalle`, `hc_categoria`.`catg_est_idEstado` AS `catg_est_idEstado`, `hc_subcategoria`.`scat_id` AS `scat_id`, `hc_subcategoria`.`scat_detalle` AS `scat_detalle`, `hc_subcategoria`.`scat_catg_catgPadre` AS `scat_catg_catgPadre`, `hc_subcategoria`.`scat_est_idEstado` AS `scat_est_idEstado` FROM ((`hc_campo` join `hc_categoria` on((`hc_categoria`.`catg_id` = `hc_campo`.`camp_catg_idCategoria`))) join `hc_subcategoria` on((`hc_subcategoria`.`scat_id` = `hc_campo`.`camp_scat_idSubcategoria`))) WHERE (`hc_campo`.`camp_empo_idEmpleado` = 0) ;

-- --------------------------------------------------------

--
-- Structure for view `v_categoria`
--
DROP TABLE IF EXISTS `v_categoria`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_categoria`  AS SELECT `hc_categoria`.`catg_id` AS `catg_id`, `hc_categoria`.`catg_detalle` AS `catg_detalle` FROM `hc_categoria` WHERE (`hc_categoria`.`catg_est_idEstado` = 7) ;

-- --------------------------------------------------------

--
-- Structure for view `v_categoriacampo`
--
DROP TABLE IF EXISTS `v_categoriacampo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_categoriacampo`  AS SELECT `hc_categoria`.`catg_id` AS `catg_id`, `hc_categoria`.`catg_detalle` AS `catg_detalle`, `hc_categoria`.`catg_est_idEstado` AS `catg_est_idEstado` FROM `hc_categoria` WHERE (`hc_categoria`.`catg_est_idEstado` = 10) ;

-- --------------------------------------------------------

--
-- Structure for view `v_categoriaseguridad`
--
DROP TABLE IF EXISTS `v_categoriaseguridad`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_categoriaseguridad`  AS SELECT `hc_categoria`.`catg_id` AS `catg_id`, `hc_categoria`.`catg_detalle` AS `catg_detalle`, `hc_categoria`.`catg_est_idEstado` AS `catg_est_idEstado` FROM `hc_categoria` WHERE (`hc_categoria`.`catg_est_idEstado` = 14) ;

-- --------------------------------------------------------

--
-- Structure for view `v_coti_lastid`
--
DROP TABLE IF EXISTS `v_coti_lastid`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_coti_lastid`  AS SELECT max(`hc_cotizaciones`.`cot_id`) AS `last_id` FROM `hc_cotizaciones` ;

-- --------------------------------------------------------

--
-- Structure for view `v_electronico`
--
DROP TABLE IF EXISTS `v_electronico`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_electronico`  AS SELECT `hc_electronico`.`elec_id` AS `elec_id`, `hc_electronico`.`elec_stock` AS `elec_stock`, `hc_electronico`.`elec_detalle` AS `elec_detalle`, `hc_electronico`.`elec_marca` AS `elec_marca`, `hc_electronico`.`elec_codigo` AS `elec_codigo`, `hc_electronico`.`elec_cantMin` AS `elec_cantMin`, `hc_electronico`.`elec_precioDolar` AS `elec_precioDolar`, `hc_electronico`.`elec_porv_IDdolar` AS `elec_porv_IDdolar`, `hc_electronico`.`elec_precio` AS `elec_precio`, `hc_electronico`.`elec_porcentaje` AS `elec_porcentaje`, `hc_electronico`.`elec_precioTotal` AS `elec_precioTotal`, `hc_electronico`.`elec_prov_IDproveedor` AS `elec_prov_IDproveedor`, `hc_electronico`.`elec_catg_IDcategoria` AS `elec_catg_IDcategoria`, `hc_electronico`.`elec_scat_IDsubcategoria` AS `elec_scat_IDsubcategoria`, `hc_proveedor`.`prov_id` AS `prov_id`, `hc_proveedor`.`prov_nombre` AS `prov_nombre`, `hc_proveedor`.`prov_empresa` AS `prov_empresa`, `hc_proveedor`.`prov_direccion` AS `prov_direccion`, `hc_proveedor`.`prov_cambio` AS `prov_cambio`, `hc_categoria`.`catg_id` AS `catg_id`, `hc_categoria`.`catg_detalle` AS `catg_detalle`, `hc_subcategoria`.`scat_id` AS `scat_id`, `hc_subcategoria`.`scat_detalle` AS `scat_detalle`, `hc_subcategoria`.`scat_catg_catgPadre` AS `scat_catg_catgPadre` FROM (((`hc_electronico` join `hc_proveedor` on((`hc_proveedor`.`prov_id` = `hc_electronico`.`elec_prov_IDproveedor`))) join `hc_categoria` on((`hc_categoria`.`catg_id` = `hc_electronico`.`elec_catg_IDcategoria`))) join `hc_subcategoria` on((`hc_subcategoria`.`scat_id` = `hc_electronico`.`elec_scat_IDsubcategoria`))) WHERE (`hc_electronico`.`elec_est_IDestado` = 1) ;

-- --------------------------------------------------------

--
-- Structure for view `v_electronicoadvertencia`
--
DROP TABLE IF EXISTS `v_electronicoadvertencia`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_electronicoadvertencia`  AS SELECT `hc_electronico`.`elec_id` AS `elec_id`, `hc_electronico`.`elec_stock` AS `elec_stock`, `hc_electronico`.`elec_detalle` AS `elec_detalle`, `hc_electronico`.`elec_marca` AS `elec_marca`, `hc_electronico`.`elec_codigo` AS `elec_codigo`, `hc_electronico`.`elec_cantMin` AS `elec_cantMin`, `hc_electronico`.`elec_precioDolar` AS `elec_precioDolar`, `hc_electronico`.`elec_porv_IDdolar` AS `elec_porv_IDdolar`, `hc_electronico`.`elec_precio` AS `elec_precio`, `hc_electronico`.`elec_porcentaje` AS `elec_porcentaje`, `hc_electronico`.`elec_precioTotal` AS `elec_precioTotal`, `hc_electronico`.`elec_prov_IDproveedor` AS `elec_prov_IDproveedor`, `hc_electronico`.`elec_catg_IDcategoria` AS `elec_catg_IDcategoria`, `hc_electronico`.`elec_scat_IDsubcategoria` AS `elec_scat_IDsubcategoria`, `hc_proveedor`.`prov_id` AS `prov_id`, `hc_proveedor`.`prov_nombre` AS `prov_nombre`, `hc_proveedor`.`prov_empresa` AS `prov_empresa`, `hc_proveedor`.`prov_direccion` AS `prov_direccion`, `hc_proveedor`.`prov_cambio` AS `prov_cambio`, `hc_categoria`.`catg_id` AS `catg_id`, `hc_categoria`.`catg_detalle` AS `catg_detalle`, `hc_subcategoria`.`scat_id` AS `scat_id`, `hc_subcategoria`.`scat_detalle` AS `scat_detalle`, `hc_subcategoria`.`scat_catg_catgPadre` AS `scat_catg_catgPadre` FROM (((`hc_electronico` join `hc_proveedor` on((`hc_proveedor`.`prov_id` = `hc_electronico`.`elec_prov_IDproveedor`))) join `hc_categoria` on((`hc_categoria`.`catg_id` = `hc_electronico`.`elec_catg_IDcategoria`))) join `hc_subcategoria` on((`hc_subcategoria`.`scat_id` = `hc_electronico`.`elec_scat_IDsubcategoria`))) WHERE (`hc_electronico`.`elec_est_IDestado` = 2) ;

-- --------------------------------------------------------

--
-- Structure for view `v_electronicocritico`
--
DROP TABLE IF EXISTS `v_electronicocritico`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_electronicocritico`  AS SELECT `hc_electronico`.`elec_id` AS `elec_id`, `hc_electronico`.`elec_stock` AS `elec_stock`, `hc_electronico`.`elec_detalle` AS `elec_detalle`, `hc_electronico`.`elec_marca` AS `elec_marca`, `hc_electronico`.`elec_codigo` AS `elec_codigo`, `hc_electronico`.`elec_cantMin` AS `elec_cantMin`, `hc_electronico`.`elec_precioDolar` AS `elec_precioDolar`, `hc_electronico`.`elec_porv_IDdolar` AS `elec_porv_IDdolar`, `hc_electronico`.`elec_precio` AS `elec_precio`, `hc_electronico`.`elec_porcentaje` AS `elec_porcentaje`, `hc_electronico`.`elec_precioTotal` AS `elec_precioTotal`, `hc_electronico`.`elec_prov_IDproveedor` AS `elec_prov_IDproveedor`, `hc_electronico`.`elec_catg_IDcategoria` AS `elec_catg_IDcategoria`, `hc_electronico`.`elec_scat_IDsubcategoria` AS `elec_scat_IDsubcategoria`, `hc_proveedor`.`prov_id` AS `prov_id`, `hc_proveedor`.`prov_nombre` AS `prov_nombre`, `hc_proveedor`.`prov_empresa` AS `prov_empresa`, `hc_proveedor`.`prov_direccion` AS `prov_direccion`, `hc_proveedor`.`prov_cambio` AS `prov_cambio`, `hc_categoria`.`catg_id` AS `catg_id`, `hc_categoria`.`catg_detalle` AS `catg_detalle`, `hc_subcategoria`.`scat_id` AS `scat_id`, `hc_subcategoria`.`scat_detalle` AS `scat_detalle`, `hc_subcategoria`.`scat_catg_catgPadre` AS `scat_catg_catgPadre` FROM (((`hc_electronico` join `hc_proveedor` on((`hc_proveedor`.`prov_id` = `hc_electronico`.`elec_prov_IDproveedor`))) join `hc_categoria` on((`hc_categoria`.`catg_id` = `hc_electronico`.`elec_catg_IDcategoria`))) join `hc_subcategoria` on((`hc_subcategoria`.`scat_id` = `hc_electronico`.`elec_scat_IDsubcategoria`))) WHERE (`hc_electronico`.`elec_est_IDestado` = 3) ;

-- --------------------------------------------------------

--
-- Structure for view `v_electronicogeneral`
--
DROP TABLE IF EXISTS `v_electronicogeneral`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_electronicogeneral`  AS SELECT `hc_electronico`.`elec_id` AS `elec_id`, `hc_electronico`.`elec_stock` AS `elec_stock`, `hc_electronico`.`elec_detalle` AS `elec_detalle`, `hc_electronico`.`elec_marca` AS `elec_marca`, `hc_electronico`.`elec_codigo` AS `elec_codigo`, `hc_electronico`.`elec_cantMin` AS `elec_cantMin`, `hc_electronico`.`elec_precioDolar` AS `elec_precioDolar`, `hc_electronico`.`elec_porv_IDdolar` AS `elec_porv_IDdolar`, `hc_electronico`.`elec_precio` AS `elec_precio`, `hc_electronico`.`elec_porcentaje` AS `elec_porcentaje`, `hc_electronico`.`elec_precioTotal` AS `elec_precioTotal`, `hc_electronico`.`elec_prov_IDproveedor` AS `elec_prov_IDproveedor`, `hc_electronico`.`elec_catg_IDcategoria` AS `elec_catg_IDcategoria`, `hc_electronico`.`elec_scat_IDsubcategoria` AS `elec_scat_IDsubcategoria`, `hc_proveedor`.`prov_id` AS `prov_id`, `hc_proveedor`.`prov_nombre` AS `prov_nombre`, `hc_proveedor`.`prov_empresa` AS `prov_empresa`, `hc_proveedor`.`prov_direccion` AS `prov_direccion`, `hc_proveedor`.`prov_cambio` AS `prov_cambio`, `hc_categoria`.`catg_id` AS `catg_id`, `hc_categoria`.`catg_detalle` AS `catg_detalle`, `hc_subcategoria`.`scat_id` AS `scat_id`, `hc_subcategoria`.`scat_detalle` AS `scat_detalle`, `hc_subcategoria`.`scat_catg_catgPadre` AS `scat_catg_catgPadre` FROM (((`hc_electronico` join `hc_proveedor` on((`hc_proveedor`.`prov_id` = `hc_electronico`.`elec_prov_IDproveedor`))) join `hc_categoria` on((`hc_categoria`.`catg_id` = `hc_electronico`.`elec_catg_IDcategoria`))) join `hc_subcategoria` on((`hc_subcategoria`.`scat_id` = `hc_electronico`.`elec_scat_IDsubcategoria`))) ;

-- --------------------------------------------------------

--
-- Structure for view `v_electronicos_agrupados`
--
DROP TABLE IF EXISTS `v_electronicos_agrupados`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_electronicos_agrupados`  AS SELECT `e`.`elec_codigo` AS `elec_codigo`, max(`e`.`elec_detalle`) AS `elec_detalle`, sum(`e`.`elec_stok`) AS `elec_stock`, max(`e`.`elec_cantMin`) AS `elec_cantMin`, max(`e`.`elec_buffer`) AS `elec_buffer`, avg(`e`.`elec_precio_prov`) AS `elec_precio`, avg(`e`.`elec_total`) AS `elec_precioTotal`, max(`c`.`catg_detalle`) AS `catg_detalle`, max(`s`.`scat_detalle`) AS `scat_detalle`, (case when (avg(`e`.`elec_est_id`) < 1.5) then 1 when (avg(`e`.`elec_est_id`) < 2.5) then 2 else 3 end) AS `estado_promedio` FROM ((`hc_electronicos` `e` join `hc_categoria` `c` on((`c`.`catg_id` = `e`.`elec_catg_id`))) join `hc_subcategoria` `s` on((`s`.`scat_id` = `e`.`elec_scat_id`))) GROUP BY `e`.`elec_codigo` ;

-- --------------------------------------------------------

--
-- Structure for view `v_empleadoseguridad`
--
DROP TABLE IF EXISTS `v_empleadoseguridad`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_empleadoseguridad`  AS SELECT `hc_empleados`.`emp_id` AS `emp_id`, `hc_empleados`.`emp_nombre` AS `emp_nombre`, `hc_empleados`.`emp_apellidos` AS `emp_apellidos`, `hc_empleados`.`emp_cedula` AS `emp_cedula`, `hc_empleados`.`emp_telefono` AS `emp_telefono`, `hc_empleados`.`emp_correo` AS `emp_correo`, `hc_empleados`.`emp_direccion` AS `emp_direccion`, `hc_empleados`.`emp_fechaIngreso` AS `emp_fechaIngreso`, `hc_empleados`.`emp_cuenta` AS `emp_cuenta`, `hc_empleados`.`emp_codigo` AS `emp_codigo`, `hc_empleados`.`emp_foto` AS `emp_foto`, `hc_empleados`.`emp_carnetAgente` AS `emp_carnetAgente`, `hc_empleados`.`emp_carnetArma` AS `emp_carnetArma`, `hc_empleados`.`emp_testPsicologico` AS `emp_testPsicologico`, `hc_empleados`.`emp_huellas` AS `emp_huellas`, `hc_empleados`.`emp_vacaciones` AS `emp_vacaciones`, `hc_empleados`.`emp_licencias` AS `emp_licencias`, `hc_empleados`.`emp_obd_id` AS `emp_obd_id`, `hc_empleados`.`emp_rol_id` AS `emp_rol_id`, `hc_empleados`.`emp_dep_id` AS `emp_dep_id`, `hc_empleados`.`emp_estado` AS `emp_estado`, `hc_departamento`.`dep_id` AS `dep_id`, `hc_departamento`.`dep_detalle` AS `dep_detalle` FROM (`hc_empleados` join `hc_departamento` on((`hc_empleados`.`emp_dep_id` = `hc_departamento`.`dep_id`))) WHERE (`hc_empleados`.`emp_dep_id` = 7) ;

-- --------------------------------------------------------

--
-- Structure for view `v_empleados_general`
--
DROP TABLE IF EXISTS `v_empleados_general`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_empleados_general`  AS SELECT `hc_empleados`.`emp_id` AS `emp_id`, `hc_empleados`.`emp_nombre` AS `emp_nombre`, `hc_empleados`.`emp_apellidos` AS `emp_apellidos`, `hc_empleados`.`emp_cedula` AS `emp_cedula`, `hc_empleados`.`emp_telefono` AS `emp_telefono`, `hc_empleados`.`emp_correo` AS `emp_correo`, `hc_empleados`.`emp_direccion` AS `emp_direccion`, `hc_empleados`.`emp_salario` AS `emp_salario`, `hc_empleados`.`emp_fechaIngreso` AS `emp_fechaIngreso`, `hc_empleados`.`emp_cuenta` AS `emp_cuenta`, `hc_empleados`.`emp_codigo` AS `emp_codigo`, `hc_empleados`.`emp_foto` AS `emp_foto`, `hc_empleados`.`emp_carnetAgente` AS `emp_carnetAgente`, `hc_empleados`.`emp_carnetArma` AS `emp_carnetArma`, `hc_empleados`.`emp_testPsicologico` AS `emp_testPsicologico`, `hc_empleados`.`emp_huellas` AS `emp_huellas`, `hc_empleados`.`emp_delta` AS `emp_delta`, `hc_empleados`.`emp_puesto` AS `emp_puesto`, `hc_empleados`.`emp_vacaciones` AS `emp_vacaciones`, `hc_empleados`.`emp_licencias` AS `emp_licencias`, `hc_empleados`.`emp_obd_id` AS `emp_obd_id`, `hc_empleados`.`emp_rol_id` AS `emp_rol_id`, `hc_empleados`.`emp_dep_id` AS `emp_dep_id`, `hc_empleados`.`emp_estado` AS `emp_estado`, `hc_departamento`.`dep_id` AS `dep_id`, `hc_departamento`.`dep_detalle` AS `dep_detalle`, `hc_roles`.`rol_id` AS `rol_id`, `hc_roles`.`rol_detalle` AS `rol_detalle`, `hc_roles`.`rol_dep_id` AS `rol_dep_id`, `hc_observaciones_empleados`.`obe_id` AS `obe_id`, `hc_observaciones_empleados`.`obe_observación` AS `obe_observación`, `hc_observaciones_empleados`.`obe_fecha` AS `obe_fecha`, `hc_observaciones_empleados`.`obe_usuario` AS `obe_usuario`, `hc_observaciones_empleados`.`obe_emp_id` AS `obe_emp_id` FROM (((`hc_empleados` join `hc_departamento` on((`hc_empleados`.`emp_dep_id` = `hc_departamento`.`dep_id`))) join `hc_roles` on((`hc_empleados`.`emp_rol_id` = `hc_roles`.`rol_id`))) left join `hc_observaciones_empleados` on((`hc_empleados`.`emp_obd_id` = `hc_observaciones_empleados`.`obe_id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `v_empleado_campo`
--
DROP TABLE IF EXISTS `v_empleado_campo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_empleado_campo`  AS SELECT `hc_empleados`.`emp_id` AS `emp_id`, `hc_empleados`.`emp_nombre` AS `emp_nombre`, `hc_empleados`.`emp_apellidos` AS `emp_apellidos`, `hc_empleados`.`emp_cedula` AS `emp_cedula`, `hc_empleados`.`emp_telefono` AS `emp_telefono`, `hc_empleados`.`emp_correo` AS `emp_correo`, `hc_empleados`.`emp_direccion` AS `emp_direccion`, `hc_empleados`.`emp_fechaIngreso` AS `emp_fechaIngreso`, `hc_empleados`.`emp_cuenta` AS `emp_cuenta`, `hc_empleados`.`emp_codigo` AS `emp_codigo`, `hc_empleados`.`emp_foto` AS `emp_foto`, `hc_empleados`.`emp_carnetAgente` AS `emp_carnetAgente`, `hc_empleados`.`emp_carnetArma` AS `emp_carnetArma`, `hc_empleados`.`emp_testPsicologico` AS `emp_testPsicologico`, `hc_empleados`.`emp_huellas` AS `emp_huellas`, `hc_empleados`.`emp_vacaciones` AS `emp_vacaciones`, `hc_empleados`.`emp_licencias` AS `emp_licencias`, `hc_empleados`.`emp_obd_id` AS `emp_obd_id`, `hc_empleados`.`emp_rol_id` AS `emp_rol_id`, `hc_empleados`.`emp_dep_id` AS `emp_dep_id`, `hc_empleados`.`emp_estado` AS `emp_estado`, `hc_departamento`.`dep_id` AS `dep_id`, `hc_departamento`.`dep_detalle` AS `dep_detalle` FROM (`hc_empleados` join `hc_departamento` on((`hc_empleados`.`emp_dep_id` = `hc_departamento`.`dep_id`))) WHERE (`hc_empleados`.`emp_dep_id` = 6) ;

-- --------------------------------------------------------

--
-- Structure for view `v_equipos_coti`
--
DROP TABLE IF EXISTS `v_equipos_coti`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_equipos_coti`  AS SELECT `hc_coti_equipo`.`cteq_id` AS `cteq_id`, `hc_coti_equipo`.`cteq_detalle` AS `cteq_detalle`, `hc_coti_equipo`.`cteq_can` AS `cteq_can`, `hc_coti_equipo`.`cteq_precio` AS `cteq_precio`, `hc_coti_equipo`.`cteq_iva` AS `cteq_iva`, `hc_coti_equipo`.`cteq_descuento` AS `cteq_descuento`, `hc_coti_equipo`.`cteq_subtotal` AS `cteq_subtotal`, `hc_coti_equipo`.`cteq_sub_iva` AS `cteq_sub_iva`, `hc_coti_equipo`.`cteq_sub_desc` AS `cteq_sub_desc`, `hc_coti_equipo`.`cteq_total_linea` AS `cteq_total_linea`, `hc_coti_equipo`.`cteq_coti_id` AS `cteq_coti_id` FROM `hc_coti_equipo` ;

-- --------------------------------------------------------

--
-- Structure for view `v_gestion_marca`
--
DROP TABLE IF EXISTS `v_gestion_marca`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_gestion_marca`  AS SELECT `hc_marcas`.`marc_id` AS `marc_id`, `hc_marcas`.`marc_detalle` AS `marc_detalle`, `hc_marcas`.`marc_est_idEstado` AS `marc_est_idEstado`, `hc_estados`.`est_id` AS `est_id`, `hc_estados`.`est_detalle` AS `est_detalle` FROM (`hc_marcas` join `hc_estados` on((`hc_estados`.`est_id` = `hc_marcas`.`marc_est_idEstado`))) ;

-- --------------------------------------------------------

--
-- Structure for view `v_gestion_proveedores`
--
DROP TABLE IF EXISTS `v_gestion_proveedores`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_gestion_proveedores`  AS SELECT `hc_proveedores`.`prov_id` AS `prov_id`, `hc_proveedores`.`prov_empresa` AS `prov_empresa`, `hc_proveedores`.`prov_identificacion` AS `prov_identificacion`, `hc_proveedores`.`prov_telefono` AS `prov_telefono`, `hc_proveedores`.`prov_correo` AS `prov_correo`, `hc_proveedores`.`prov_direccion` AS `prov_direccion`, `hc_proveedores`.`prov_contacto_nombre` AS `prov_contacto_nombre`, `hc_proveedores`.`prov_contacto_telefono` AS `prov_contacto_telefono`, `hc_proveedores`.`prov_contacto_correo` AS `prov_contacto_correo`, `hc_proveedores`.`prov_moneda_preferida` AS `prov_moneda_preferida`, `hc_proveedores`.`prov_condiciones_pago` AS `prov_condiciones_pago`, `hc_proveedores`.`activo` AS `activo`, `hc_proveedores`.`fecha_creacion` AS `fecha_creacion` FROM `hc_proveedores` WHERE (`hc_proveedores`.`activo` = 21) ;

-- --------------------------------------------------------

--
-- Structure for view `v_gestion_vehiculo`
--
DROP TABLE IF EXISTS `v_gestion_vehiculo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_gestion_vehiculo`  AS SELECT `hc_vehiculos`.`veh_id` AS `veh_id`, `hc_vehiculos`.`veh_placa` AS `veh_placa`, `hc_vehiculos`.`veh_marca` AS `veh_marca`, `hc_vehiculos`.`veh_modelo` AS `veh_modelo`, `hc_vehiculos`.`veh_anio` AS `veh_anio`, `hc_vehiculos`.`veh_color` AS `veh_color`, `hc_vehiculos`.`veh_tipo` AS `veh_tipo`, `hc_vehiculos`.`veh_num_chasis` AS `veh_num_chasis`, `hc_vehiculos`.`veh_num_motor` AS `veh_num_motor`, `hc_vehiculos`.`veh_kilometraje` AS `veh_kilometraje`, `hc_vehiculos`.`veh_fecha_vencimiento_seguro` AS `veh_fecha_vencimiento_seguro`, `hc_vehiculos`.`veh_fecha_revision` AS `veh_fecha_revision`, `hc_vehiculos`.`veh_observaciones` AS `veh_observaciones`, `hc_vehiculos`.`veh_fecha_registro` AS `veh_fecha_registro`, `hc_vehiculos`.`veh_estado` AS `veh_estado` FROM `hc_vehiculos` ;

-- --------------------------------------------------------

--
-- Structure for view `v_get_categoriagestion`
--
DROP TABLE IF EXISTS `v_get_categoriagestion`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_get_categoriagestion`  AS SELECT `hc_categoria`.`catg_id` AS `catg_id`, `hc_categoria`.`catg_detalle` AS `catg_detalle`, `hc_categoria`.`catg_est_idEstado` AS `catg_est_idEstado`, `hc_estados`.`est_id` AS `est_id`, `hc_estados`.`est_detalle` AS `est_detalle` FROM (`hc_categoria` join `hc_estados` on((`hc_estados`.`est_id` = `hc_categoria`.`catg_est_idEstado`))) ;

-- --------------------------------------------------------

--
-- Structure for view `v_get_colaborador_despedido`
--
DROP TABLE IF EXISTS `v_get_colaborador_despedido`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_get_colaborador_despedido`  AS SELECT `hc_empleados`.`emp_id` AS `emp_id`, `hc_empleados`.`emp_nombre` AS `emp_nombre`, `hc_empleados`.`emp_apellidos` AS `emp_apellidos`, `hc_empleados`.`emp_cedula` AS `emp_cedula`, `hc_empleados`.`emp_telefono` AS `emp_telefono`, `hc_empleados`.`emp_correo` AS `emp_correo`, `hc_empleados`.`emp_direccion` AS `emp_direccion`, `hc_empleados`.`emp_fechaIngreso` AS `emp_fechaIngreso`, `hc_empleados`.`emp_cuenta` AS `emp_cuenta`, `hc_empleados`.`emp_codigo` AS `emp_codigo`, `hc_empleados`.`emp_foto` AS `emp_foto`, `hc_empleados`.`emp_carnetAgente` AS `emp_carnetAgente`, `hc_empleados`.`emp_carnetArma` AS `emp_carnetArma`, `hc_empleados`.`emp_testPsicologico` AS `emp_testPsicologico`, `hc_empleados`.`emp_huellas` AS `emp_huellas`, `hc_empleados`.`emp_vacaciones` AS `emp_vacaciones`, `hc_empleados`.`emp_licencias` AS `emp_licencias`, `hc_empleados`.`emp_obd_id` AS `emp_obd_id`, `hc_empleados`.`emp_rol_id` AS `emp_rol_id`, `hc_empleados`.`emp_dep_id` AS `emp_dep_id`, `hc_empleados`.`emp_estado` AS `emp_estado`, `hc_departamento`.`dep_id` AS `dep_id`, `hc_departamento`.`dep_detalle` AS `dep_detalle`, `hc_observaciones_empleados`.`obe_id` AS `obe_id`, `hc_observaciones_empleados`.`obe_observación` AS `obe_observación`, `hc_observaciones_empleados`.`obe_fecha` AS `obe_fecha`, `hc_observaciones_empleados`.`obe_usuario` AS `obe_usuario` FROM ((`hc_empleados` join `hc_departamento` on((`hc_empleados`.`emp_dep_id` = `hc_departamento`.`dep_id`))) join `hc_observaciones_empleados` on((`hc_empleados`.`emp_obd_id` = `hc_observaciones_empleados`.`obe_id`))) WHERE (`hc_empleados`.`emp_estado` = 27) ;

-- --------------------------------------------------------

--
-- Structure for view `v_get_colaborador_inactivo`
--
DROP TABLE IF EXISTS `v_get_colaborador_inactivo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_get_colaborador_inactivo`  AS SELECT `hc_empleados`.`emp_id` AS `emp_id`, `hc_empleados`.`emp_nombre` AS `emp_nombre`, `hc_empleados`.`emp_apellidos` AS `emp_apellidos`, `hc_empleados`.`emp_cedula` AS `emp_cedula`, `hc_empleados`.`emp_telefono` AS `emp_telefono`, `hc_empleados`.`emp_correo` AS `emp_correo`, `hc_empleados`.`emp_direccion` AS `emp_direccion`, `hc_empleados`.`emp_fechaIngreso` AS `emp_fechaIngreso`, `hc_empleados`.`emp_cuenta` AS `emp_cuenta`, `hc_empleados`.`emp_codigo` AS `emp_codigo`, `hc_empleados`.`emp_foto` AS `emp_foto`, `hc_empleados`.`emp_carnetAgente` AS `emp_carnetAgente`, `hc_empleados`.`emp_carnetArma` AS `emp_carnetArma`, `hc_empleados`.`emp_testPsicologico` AS `emp_testPsicologico`, `hc_empleados`.`emp_huellas` AS `emp_huellas`, `hc_empleados`.`emp_vacaciones` AS `emp_vacaciones`, `hc_empleados`.`emp_licencias` AS `emp_licencias`, `hc_empleados`.`emp_obd_id` AS `emp_obd_id`, `hc_empleados`.`emp_rol_id` AS `emp_rol_id`, `hc_empleados`.`emp_dep_id` AS `emp_dep_id`, `hc_empleados`.`emp_estado` AS `emp_estado`, `hc_departamento`.`dep_id` AS `dep_id`, `hc_departamento`.`dep_detalle` AS `dep_detalle`, `hc_observaciones_empleados`.`obe_id` AS `obe_id`, `hc_observaciones_empleados`.`obe_observación` AS `obe_observación`, `hc_observaciones_empleados`.`obe_fecha` AS `obe_fecha`, `hc_observaciones_empleados`.`obe_usuario` AS `obe_usuario` FROM ((`hc_empleados` join `hc_departamento` on((`hc_empleados`.`emp_dep_id` = `hc_departamento`.`dep_id`))) join `hc_observaciones_empleados` on((`hc_empleados`.`emp_obd_id` = `hc_observaciones_empleados`.`obe_id`))) WHERE (`hc_empleados`.`emp_estado` = 26) ;

-- --------------------------------------------------------

--
-- Structure for view `v_get_cotizaciones`
--
DROP TABLE IF EXISTS `v_get_cotizaciones`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_get_cotizaciones`  AS SELECT `hc_cotizaciones`.`cot_id` AS `cot_id`, `hc_cotizaciones`.`cot_codigo` AS `cot_codigo`, `hc_cotizaciones`.`cot_vendor` AS `cot_vendor`, `hc_cotizaciones`.`cot_cliente` AS `cot_cliente`, `hc_cotizaciones`.`cot_telefono` AS `cot_telefono`, `hc_cotizaciones`.`cot_fecha1` AS `cot_fecha1`, `hc_cotizaciones`.`cot_fecha2` AS `cot_fecha2`, `hc_cotizaciones`.`cot_subtotal` AS `cot_subtotal`, `hc_cotizaciones`.`cot_iva` AS `cot_iva`, `hc_cotizaciones`.`cot_descuento` AS `cot_descuento`, `hc_cotizaciones`.`cot_total` AS `cot_total` FROM `hc_cotizaciones` ;

-- --------------------------------------------------------

--
-- Structure for view `v_get_departamento`
--
DROP TABLE IF EXISTS `v_get_departamento`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_get_departamento`  AS SELECT `hc_departamento`.`dep_id` AS `dep_id`, `hc_departamento`.`dep_detalle` AS `dep_detalle` FROM `hc_departamento` ;

-- --------------------------------------------------------

--
-- Structure for view `v_get_electronicos_general`
--
DROP TABLE IF EXISTS `v_get_electronicos_general`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_get_electronicos_general`  AS SELECT `hc_electronicos`.`elec_id` AS `elec_id`, `hc_electronicos`.`elec_stok` AS `elec_stok`, `hc_electronicos`.`elec_detalle` AS `elec_detalle`, `hc_electronicos`.`elec_marca` AS `elec_marca`, `hc_electronicos`.`elec_codigo` AS `elec_codigo`, `hc_electronicos`.`elec_cantMin` AS `elec_cantMin`, `hc_electronicos`.`elec_precio_prov` AS `elec_precio_prov`, `hc_electronicos`.`elec_utilidad` AS `elec_utilidad`, `hc_electronicos`.`elec_total` AS `elec_total`, `hc_electronicos`.`elec_prov_id` AS `elec_prov_id`, `hc_electronicos`.`elec_catg_id` AS `elec_catg_id`, `hc_electronicos`.`elec_scat_id` AS `elec_scat_id`, `hc_electronicos`.`elec_est_id` AS `elec_est_id`, `hc_electronicos`.`elec_fact_consecutivo` AS `elec_fact_consecutivo`, `hc_electronicos`.`elec_buffer` AS `elec_buffer` FROM `hc_electronicos` ;

-- --------------------------------------------------------

--
-- Structure for view `v_get_empleado_activo`
--
DROP TABLE IF EXISTS `v_get_empleado_activo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_get_empleado_activo`  AS SELECT `hc_empleados`.`emp_id` AS `emp_id`, `hc_empleados`.`emp_nombre` AS `emp_nombre`, `hc_empleados`.`emp_apellidos` AS `emp_apellidos`, `hc_empleados`.`emp_cedula` AS `emp_cedula`, `hc_empleados`.`emp_telefono` AS `emp_telefono`, `hc_empleados`.`emp_correo` AS `emp_correo`, `hc_empleados`.`emp_direccion` AS `emp_direccion`, `hc_empleados`.`emp_salario` AS `emp_salario`, `hc_empleados`.`emp_fechaIngreso` AS `emp_fechaIngreso`, `hc_empleados`.`emp_cuenta` AS `emp_cuenta`, `hc_empleados`.`emp_codigo` AS `emp_codigo`, `hc_empleados`.`emp_foto` AS `emp_foto`, `hc_empleados`.`emp_carnetAgente` AS `emp_carnetAgente`, `hc_empleados`.`emp_carnetArma` AS `emp_carnetArma`, `hc_empleados`.`emp_testPsicologico` AS `emp_testPsicologico`, `hc_empleados`.`emp_huellas` AS `emp_huellas`, `hc_empleados`.`emp_delta` AS `emp_delta`, `hc_empleados`.`emp_puesto` AS `emp_puesto`, `hc_empleados`.`emp_vacaciones` AS `emp_vacaciones`, `hc_empleados`.`emp_licencias` AS `emp_licencias`, `hc_empleados`.`emp_obd_id` AS `emp_obd_id`, `hc_empleados`.`emp_rol_id` AS `emp_rol_id`, `hc_empleados`.`emp_dep_id` AS `emp_dep_id`, `hc_empleados`.`emp_estado` AS `emp_estado`, `hc_departamento`.`dep_id` AS `dep_id`, `hc_departamento`.`dep_detalle` AS `dep_detalle`, `hc_roles`.`rol_id` AS `rol_id`, `hc_roles`.`rol_detalle` AS `rol_detalle`, `hc_roles`.`rol_dep_id` AS `rol_dep_id`, `hc_observaciones_empleados`.`obe_id` AS `obe_id`, `hc_observaciones_empleados`.`obe_observación` AS `obe_observación`, `hc_observaciones_empleados`.`obe_fecha` AS `obe_fecha`, `hc_observaciones_empleados`.`obe_usuario` AS `obe_usuario`, `hc_observaciones_empleados`.`obe_emp_id` AS `obe_emp_id` FROM (((`hc_empleados` join `hc_departamento` on((`hc_empleados`.`emp_dep_id` = `hc_departamento`.`dep_id`))) join `hc_roles` on((`hc_empleados`.`emp_rol_id` = `hc_roles`.`rol_id`))) left join `hc_observaciones_empleados` on((`hc_empleados`.`emp_obd_id` = `hc_observaciones_empleados`.`obe_id`))) WHERE (`hc_empleados`.`emp_estado` = 25) ;

-- --------------------------------------------------------

--
-- Structure for view `v_get_equipos_orden`
--
DROP TABLE IF EXISTS `v_get_equipos_orden`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_get_equipos_orden`  AS SELECT `hc_equipos_orden`.`erd_id` AS `erd_id`, `hc_equipos_orden`.`erd_codigo` AS `erd_codigo`, `hc_equipos_orden`.`erd_descripcion` AS `erd_descripcion`, `hc_equipos_orden`.`erd_cantidad` AS `erd_cantidad`, `hc_equipos_orden`.`erd_tipo` AS `erd_tipo`, `hc_equipos_orden`.`erd_orden_id` AS `erd_orden_id` FROM `hc_equipos_orden` ;

-- --------------------------------------------------------

--
-- Structure for view `v_get_estadosgestion`
--
DROP TABLE IF EXISTS `v_get_estadosgestion`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_get_estadosgestion`  AS SELECT `hc_estados`.`est_id` AS `est_id`, `hc_estados`.`est_detalle` AS `est_detalle` FROM `hc_estados` ;

-- --------------------------------------------------------

--
-- Structure for view `v_get_reportes_oficiales_general`
--
DROP TABLE IF EXISTS `v_get_reportes_oficiales_general`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_get_reportes_oficiales_general`  AS SELECT `e`.`emp_id` AS `emp_id`, concat(`e`.`emp_nombre`,' ',`e`.`emp_apellidos`) AS `nombre`, `e`.`emp_cedula` AS `emp_cedula`, `e`.`emp_delta` AS `emp_delta`, `e`.`emp_puesto` AS `emp_puesto` FROM (`hc_empleados` `e` join `hc_roles` `r` on((`e`.`emp_rol_id` = `r`.`rol_id`))) WHERE (`e`.`emp_dep_id` = 7) ;

-- --------------------------------------------------------

--
-- Structure for view `v_get_reportes_oficiales_todos`
--
DROP TABLE IF EXISTS `v_get_reportes_oficiales_todos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_get_reportes_oficiales_todos`  AS SELECT `reporte_oficial`.`reof_id` AS `reof_id`, `reporte_oficial`.`reof_motivo` AS `reof_motivo`, `reporte_oficial`.`reof_justificacion` AS `reof_justificacion`, `reporte_oficial`.`reof_emp_id` AS `reof_emp_id`, `reporte_oficial`.`reof_bitacora` AS `reof_bitacora`, `hc_empleados`.`emp_id` AS `emp_id`, `hc_empleados`.`emp_nombre` AS `emp_nombre`, `hc_empleados`.`emp_apellidos` AS `emp_apellidos`, `hc_empleados`.`emp_cedula` AS `emp_cedula`, `hc_empleados`.`emp_telefono` AS `emp_telefono`, `hc_empleados`.`emp_correo` AS `emp_correo`, `hc_empleados`.`emp_direccion` AS `emp_direccion`, `hc_empleados`.`emp_salario` AS `emp_salario`, `hc_empleados`.`emp_fechaIngreso` AS `emp_fechaIngreso`, `hc_empleados`.`emp_cuenta` AS `emp_cuenta`, `hc_empleados`.`emp_codigo` AS `emp_codigo`, `hc_empleados`.`emp_foto` AS `emp_foto`, `hc_empleados`.`emp_carnetAgente` AS `emp_carnetAgente`, `hc_empleados`.`emp_carnetArma` AS `emp_carnetArma`, `hc_empleados`.`emp_testPsicologico` AS `emp_testPsicologico`, `hc_empleados`.`emp_huellas` AS `emp_huellas`, `hc_empleados`.`emp_delta` AS `emp_delta`, `hc_empleados`.`emp_puesto` AS `emp_puesto`, `hc_empleados`.`emp_vacaciones` AS `emp_vacaciones`, `hc_empleados`.`emp_licencias` AS `emp_licencias`, `hc_empleados`.`emp_obd_id` AS `emp_obd_id`, `hc_empleados`.`emp_rol_id` AS `emp_rol_id`, `hc_empleados`.`emp_dep_id` AS `emp_dep_id`, `hc_empleados`.`emp_estado` AS `emp_estado`, `hc_empleados`.`emp_estado_supervision` AS `emp_estado_supervision` FROM (`reporte_oficial` join `hc_empleados` on((`reporte_oficial`.`reof_emp_id` = `hc_empleados`.`emp_id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `v_get_rol`
--
DROP TABLE IF EXISTS `v_get_rol`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_get_rol`  AS SELECT `hc_roles`.`rol_id` AS `rol_id`, `hc_roles`.`rol_detalle` AS `rol_detalle`, `hc_roles`.`rol_dep_id` AS `rol_dep_id` FROM `hc_roles` ;

-- --------------------------------------------------------

--
-- Structure for view `v_get_subcategoriagestion`
--
DROP TABLE IF EXISTS `v_get_subcategoriagestion`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_get_subcategoriagestion`  AS SELECT `hc_subcategoria`.`scat_id` AS `scat_id`, `hc_subcategoria`.`scat_detalle` AS `scat_detalle`, `hc_subcategoria`.`scat_catg_catgPadre` AS `scat_catg_catgPadre`, `hc_subcategoria`.`scat_est_idEstado` AS `scat_est_idEstado`, `hc_categoria`.`catg_detalle` AS `categoria_nombre`, `hc_estados`.`est_detalle` AS `estado_nombre` FROM ((`hc_subcategoria` join `hc_categoria` on((`hc_categoria`.`catg_id` = `hc_subcategoria`.`scat_catg_catgPadre`))) join `hc_estados` on((`hc_estados`.`est_id` = `hc_subcategoria`.`scat_est_idEstado`))) ;

-- --------------------------------------------------------

--
-- Structure for view `v_get_usuario_general`
--
DROP TABLE IF EXISTS `v_get_usuario_general`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_get_usuario_general`  AS SELECT `hc_usuarios`.`user_id` AS `user_id`, `hc_usuarios`.`user_emp_id` AS `user_emp_id`, `hc_usuarios`.`user_name` AS `user_name`, `hc_usuarios`.`user_password` AS `user_password`, `hc_usuarios`.`user_nivelAcceso` AS `user_nivelAcceso`, `hc_usuarios`.`user_estado` AS `user_estado`, `hc_empleados`.`emp_id` AS `emp_id`, `hc_empleados`.`emp_nombre` AS `emp_nombre`, `hc_empleados`.`emp_apellidos` AS `emp_apellidos`, `hc_empleados`.`emp_cedula` AS `emp_cedula`, `hc_empleados`.`emp_telefono` AS `emp_telefono`, `hc_empleados`.`emp_correo` AS `emp_correo`, `hc_empleados`.`emp_direccion` AS `emp_direccion`, `hc_empleados`.`emp_fechaIngreso` AS `emp_fechaIngreso`, `hc_empleados`.`emp_cuenta` AS `emp_cuenta`, `hc_empleados`.`emp_codigo` AS `emp_codigo`, `hc_empleados`.`emp_foto` AS `emp_foto`, `hc_empleados`.`emp_carnetAgente` AS `emp_carnetAgente`, `hc_empleados`.`emp_carnetArma` AS `emp_carnetArma`, `hc_empleados`.`emp_testPsicologico` AS `emp_testPsicologico`, `hc_empleados`.`emp_huellas` AS `emp_huellas`, `hc_empleados`.`emp_vacaciones` AS `emp_vacaciones`, `hc_empleados`.`emp_licencias` AS `emp_licencias`, `hc_empleados`.`emp_obd_id` AS `emp_obd_id`, `hc_empleados`.`emp_rol_id` AS `emp_rol_id`, `hc_empleados`.`emp_dep_id` AS `emp_dep_id`, `hc_empleados`.`emp_estado` AS `emp_estado` FROM (`hc_usuarios` join `hc_empleados` on((`hc_usuarios`.`user_emp_id` = `hc_empleados`.`emp_id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `v_get_usuario_inactivo`
--
DROP TABLE IF EXISTS `v_get_usuario_inactivo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_get_usuario_inactivo`  AS SELECT `hc_usuarios`.`user_id` AS `user_id`, `hc_usuarios`.`user_emp_id` AS `user_emp_id`, `hc_usuarios`.`user_name` AS `user_name`, `hc_usuarios`.`user_password` AS `user_password`, `hc_usuarios`.`user_nivelAcceso` AS `user_nivelAcceso`, `hc_usuarios`.`user_estado` AS `user_estado`, `hc_empleados`.`emp_id` AS `emp_id`, `hc_empleados`.`emp_nombre` AS `emp_nombre`, `hc_empleados`.`emp_apellidos` AS `emp_apellidos`, `hc_empleados`.`emp_cedula` AS `emp_cedula`, `hc_empleados`.`emp_telefono` AS `emp_telefono`, `hc_empleados`.`emp_correo` AS `emp_correo`, `hc_empleados`.`emp_direccion` AS `emp_direccion`, `hc_empleados`.`emp_fechaIngreso` AS `emp_fechaIngreso`, `hc_empleados`.`emp_cuenta` AS `emp_cuenta`, `hc_empleados`.`emp_codigo` AS `emp_codigo`, `hc_empleados`.`emp_foto` AS `emp_foto`, `hc_empleados`.`emp_carnetAgente` AS `emp_carnetAgente`, `hc_empleados`.`emp_carnetArma` AS `emp_carnetArma`, `hc_empleados`.`emp_testPsicologico` AS `emp_testPsicologico`, `hc_empleados`.`emp_huellas` AS `emp_huellas`, `hc_empleados`.`emp_vacaciones` AS `emp_vacaciones`, `hc_empleados`.`emp_licencias` AS `emp_licencias`, `hc_empleados`.`emp_obd_id` AS `emp_obd_id`, `hc_empleados`.`emp_rol_id` AS `emp_rol_id`, `hc_empleados`.`emp_dep_id` AS `emp_dep_id`, `hc_empleados`.`emp_estado` AS `emp_estado`, `hc_acseso`.`acs_id` AS `acs_id`, `hc_acseso`.`acs_nombre` AS `acs_nombre`, `hc_acseso`.`acs_detalle` AS `acs_detalle` FROM ((`hc_usuarios` join `hc_empleados` on((`hc_usuarios`.`user_emp_id` = `hc_empleados`.`emp_id`))) join `hc_acseso` on((`hc_usuarios`.`user_nivelAcceso` = `hc_acseso`.`acs_id`))) WHERE (`hc_usuarios`.`user_estado` = 32) ;

-- --------------------------------------------------------

--
-- Structure for view `v_get_vehiculos_busetas`
--
DROP TABLE IF EXISTS `v_get_vehiculos_busetas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_get_vehiculos_busetas`  AS SELECT `hc_vehiculos`.`veh_id` AS `veh_id`, `hc_vehiculos`.`veh_placa` AS `veh_placa`, `hc_vehiculos`.`veh_marca` AS `veh_marca`, `hc_vehiculos`.`veh_modelo` AS `veh_modelo`, `hc_vehiculos`.`veh_anio` AS `veh_anio` FROM `hc_vehiculos` WHERE (`hc_vehiculos`.`veh_estado` = 21) ;

-- --------------------------------------------------------

--
-- Structure for view `v_marcas`
--
DROP TABLE IF EXISTS `v_marcas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_marcas`  AS SELECT `hc_marcas`.`marc_id` AS `marc_id`, `hc_marcas`.`marc_detalle` AS `marc_detalle` FROM `hc_marcas` WHERE (`hc_marcas`.`marc_est_idEstado` = 9) ;

-- --------------------------------------------------------

--
-- Structure for view `v_marcascampo`
--
DROP TABLE IF EXISTS `v_marcascampo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_marcascampo`  AS SELECT `hc_marcas`.`marc_id` AS `marc_id`, `hc_marcas`.`marc_detalle` AS `marc_detalle`, `hc_marcas`.`marc_est_idEstado` AS `marc_est_idEstado` FROM `hc_marcas` WHERE (`hc_marcas`.`marc_est_idEstado` = 12) ;

-- --------------------------------------------------------

--
-- Structure for view `v_nivel_acceso`
--
DROP TABLE IF EXISTS `v_nivel_acceso`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_nivel_acceso`  AS SELECT `hc_acseso`.`acs_id` AS `acs_id`, `hc_acseso`.`acs_nombre` AS `acs_nombre`, `hc_acseso`.`acs_detalle` AS `acs_detalle` FROM `hc_acseso` ;

-- --------------------------------------------------------

--
-- Structure for view `v_orden_lastid`
--
DROP TABLE IF EXISTS `v_orden_lastid`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_orden_lastid`  AS SELECT max(`hc_orden`.`ord_id`) AS `lastID` FROM `hc_orden` ;

-- --------------------------------------------------------

--
-- Structure for view `v_orden_trabajo`
--
DROP TABLE IF EXISTS `v_orden_trabajo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_orden_trabajo`  AS SELECT `hc_orden`.`ord_id` AS `ord_id`, `hc_orden`.`ord_codigo` AS `ord_codigo`, `hc_orden`.`ord_fecha` AS `ord_fecha`, `hc_orden`.`ord_tecnico` AS `ord_tecnico`, `hc_orden`.`ord_asistente1` AS `ord_asistente1`, `hc_orden`.`ord_asistente2` AS `ord_asistente2`, `hc_orden`.`ord_tipoTrabajo` AS `ord_tipoTrabajo`, `hc_orden`.`ord_cliente` AS `ord_cliente`, `hc_orden`.`ord_direccion` AS `ord_direccion`, `hc_orden`.`ord_telefono` AS `ord_telefono`, `hc_orden`.`ord_descripcion` AS `ord_descripcion`, `hc_orden`.`ord_estado` AS `ord_estado`, `hc_equipos_orden`.`erd_id` AS `erd_id`, `hc_equipos_orden`.`erd_codigo` AS `erd_codigo`, `hc_equipos_orden`.`erd_descripcion` AS `erd_descripcion`, `hc_equipos_orden`.`erd_cantidad` AS `erd_cantidad`, `hc_equipos_orden`.`erd_tipo` AS `erd_tipo`, `hc_equipos_orden`.`erd_orden_id` AS `erd_orden_id`, `hc_orden`.`ord_vehiculo_id` AS `ord_vehiculo_id` FROM (`hc_orden` left join `hc_equipos_orden` on((`hc_orden`.`ord_id` = `hc_equipos_orden`.`erd_orden_id`))) WHERE (`hc_orden`.`ord_estado` = 1) ;

-- --------------------------------------------------------

--
-- Structure for view `v_proveedor`
--
DROP TABLE IF EXISTS `v_proveedor`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_proveedor`  AS SELECT `hc_proveedor`.`prov_id` AS `prov_id`, `hc_proveedor`.`prov_nombre` AS `prov_nombre`, `hc_proveedor`.`prov_empresa` AS `prov_empresa`, `hc_proveedor`.`prov_direccion` AS `prov_direccion`, `hc_proveedor`.`prov_cambio` AS `prov_cambio` FROM `hc_proveedor` ;

-- --------------------------------------------------------

--
-- Structure for view `v_proveedores`
--
DROP TABLE IF EXISTS `v_proveedores`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_proveedores`  AS SELECT `hc_proveedores`.`prov_id` AS `prov_id`, `hc_proveedores`.`prov_empresa` AS `prov_empresa`, `hc_proveedores`.`prov_identificacion` AS `prov_identificacion`, `hc_proveedores`.`prov_telefono` AS `prov_telefono`, `hc_proveedores`.`prov_correo` AS `prov_correo`, `hc_proveedores`.`prov_direccion` AS `prov_direccion`, `hc_proveedores`.`prov_contacto_nombre` AS `prov_contacto_nombre`, `hc_proveedores`.`prov_contacto_telefono` AS `prov_contacto_telefono`, `hc_proveedores`.`prov_contacto_correo` AS `prov_contacto_correo`, `hc_proveedores`.`prov_moneda_preferida` AS `prov_moneda_preferida`, `hc_proveedores`.`prov_condiciones_pago` AS `prov_condiciones_pago`, `hc_proveedores`.`activo` AS `activo`, `hc_proveedores`.`fecha_creacion` AS `fecha_creacion` FROM `hc_proveedores` ;

-- --------------------------------------------------------

--
-- Structure for view `v_seguridadcategoria`
--
DROP TABLE IF EXISTS `v_seguridadcategoria`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_seguridadcategoria`  AS SELECT `hc_categoria`.`catg_id` AS `catg_id`, `hc_categoria`.`catg_detalle` AS `catg_detalle`, `hc_categoria`.`catg_est_idEstado` AS `catg_est_idEstado` FROM `hc_categoria` WHERE (`hc_categoria`.`catg_est_idEstado` = 14) ;

-- --------------------------------------------------------

--
-- Structure for view `v_seguridad_equipoasigando`
--
DROP TABLE IF EXISTS `v_seguridad_equipoasigando`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_seguridad_equipoasigando`  AS SELECT `hc_seguridad`.`segd_id` AS `segd_id`, `hc_seguridad`.`scat_cantidad` AS `scat_cantidad`, `hc_seguridad`.`segd_detalle` AS `segd_detalle`, `hc_seguridad`.`segd_condicion` AS `segd_condicion`, `hc_seguridad`.`segd_empl_IDempleado` AS `segd_empl_IDempleado`, `hc_seguridad`.`segd_catg_IDcategoria` AS `segd_catg_IDcategoria`, `hc_seguridad`.`segd_scat_IDsubcategoria` AS `segd_scat_IDsubcategoria`, `hc_seguridad`.`segd_estado` AS `segd_estado`, `hc_empleados`.`emp_id` AS `emp_id`, `hc_empleados`.`emp_nombre` AS `emp_nombre`, `hc_empleados`.`emp_apellidos` AS `emp_apellidos`, `hc_empleados`.`emp_cedula` AS `emp_cedula`, `hc_empleados`.`emp_telefono` AS `emp_telefono`, `hc_empleados`.`emp_correo` AS `emp_correo`, `hc_empleados`.`emp_direccion` AS `emp_direccion`, `hc_empleados`.`emp_fechaIngreso` AS `emp_fechaIngreso`, `hc_empleados`.`emp_cuenta` AS `emp_cuenta`, `hc_empleados`.`emp_codigo` AS `emp_codigo`, `hc_empleados`.`emp_foto` AS `emp_foto`, `hc_empleados`.`emp_carnetAgente` AS `emp_carnetAgente`, `hc_empleados`.`emp_carnetArma` AS `emp_carnetArma`, `hc_empleados`.`emp_testPsicologico` AS `emp_testPsicologico`, `hc_empleados`.`emp_huellas` AS `emp_huellas`, `hc_empleados`.`emp_vacaciones` AS `emp_vacaciones`, `hc_empleados`.`emp_licencias` AS `emp_licencias`, `hc_empleados`.`emp_obd_id` AS `emp_obd_id`, `hc_empleados`.`emp_rol_id` AS `emp_rol_id`, `hc_empleados`.`emp_dep_id` AS `emp_dep_id`, `hc_empleados`.`emp_estado` AS `emp_estado`, `hc_categoria`.`catg_id` AS `catg_id`, `hc_categoria`.`catg_detalle` AS `catg_detalle`, `hc_categoria`.`catg_est_idEstado` AS `catg_est_idEstado`, `hc_subcategoria`.`scat_id` AS `scat_id`, `hc_subcategoria`.`scat_detalle` AS `scat_detalle`, `hc_subcategoria`.`scat_catg_catgPadre` AS `scat_catg_catgPadre`, `hc_subcategoria`.`scat_est_idEstado` AS `scat_est_idEstado` FROM (((`hc_seguridad` join `hc_empleados` on((`hc_seguridad`.`segd_empl_IDempleado` = `hc_empleados`.`emp_id`))) join `hc_categoria` on((`hc_seguridad`.`segd_catg_IDcategoria` = `hc_categoria`.`catg_id`))) join `hc_subcategoria` on((`hc_seguridad`.`segd_scat_IDsubcategoria` = `hc_subcategoria`.`scat_id`))) WHERE (`hc_seguridad`.`segd_estado` = 16) ;

-- --------------------------------------------------------

--
-- Structure for view `v_seguridad_sinasignar`
--
DROP TABLE IF EXISTS `v_seguridad_sinasignar`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_seguridad_sinasignar`  AS SELECT `hc_seguridad`.`segd_id` AS `segd_id`, `hc_seguridad`.`scat_cantidad` AS `scat_cantidad`, `hc_seguridad`.`segd_detalle` AS `segd_detalle`, `hc_seguridad`.`segd_condicion` AS `segd_condicion`, `hc_seguridad`.`segd_empl_IDempleado` AS `segd_empl_IDempleado`, `hc_seguridad`.`segd_catg_IDcategoria` AS `segd_catg_IDcategoria`, `hc_seguridad`.`segd_scat_IDsubcategoria` AS `segd_scat_IDsubcategoria`, `hc_categoria`.`catg_id` AS `catg_id`, `hc_categoria`.`catg_detalle` AS `catg_detalle`, `hc_categoria`.`catg_est_idEstado` AS `catg_est_idEstado`, `hc_subcategoria`.`scat_id` AS `scat_id`, `hc_subcategoria`.`scat_detalle` AS `scat_detalle`, `hc_subcategoria`.`scat_catg_catgPadre` AS `scat_catg_catgPadre`, `hc_subcategoria`.`scat_est_idEstado` AS `scat_est_idEstado` FROM ((`hc_seguridad` join `hc_categoria` on((`hc_categoria`.`catg_id` = `hc_seguridad`.`segd_catg_IDcategoria`))) join `hc_subcategoria` on((`hc_subcategoria`.`scat_id` = `hc_seguridad`.`segd_scat_IDsubcategoria`))) WHERE (`hc_seguridad`.`segd_estado` = 34) ;

-- --------------------------------------------------------

--
-- Structure for view `v_subcategoria`
--
DROP TABLE IF EXISTS `v_subcategoria`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_subcategoria`  AS SELECT `hc_subcategoria`.`scat_id` AS `scat_id`, `hc_subcategoria`.`scat_detalle` AS `scat_detalle`, `hc_subcategoria`.`scat_catg_catgPadre` AS `scat_catg_catgPadre`, `hc_categoria`.`catg_id` AS `catg_id`, `hc_categoria`.`catg_detalle` AS `catg_detalle` FROM (`hc_subcategoria` join `hc_categoria` on((`hc_subcategoria`.`scat_catg_catgPadre` = `hc_categoria`.`catg_id`))) WHERE (`hc_subcategoria`.`scat_est_idEstado` = 8) ;

-- --------------------------------------------------------

--
-- Structure for view `v_subcategoriacampo`
--
DROP TABLE IF EXISTS `v_subcategoriacampo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_subcategoriacampo`  AS SELECT `hc_subcategoria`.`scat_id` AS `scat_id`, `hc_subcategoria`.`scat_detalle` AS `scat_detalle`, `hc_subcategoria`.`scat_catg_catgPadre` AS `scat_catg_catgPadre`, `hc_categoria`.`catg_id` AS `catg_id`, `hc_categoria`.`catg_detalle` AS `catg_detalle` FROM (`hc_subcategoria` join `hc_categoria` on((`hc_subcategoria`.`scat_catg_catgPadre` = `hc_categoria`.`catg_id`))) WHERE (`hc_subcategoria`.`scat_est_idEstado` = 11) ;

-- --------------------------------------------------------

--
-- Structure for view `v_subcategoriaseguridad`
--
DROP TABLE IF EXISTS `v_subcategoriaseguridad`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_subcategoriaseguridad`  AS SELECT `hc_subcategoria`.`scat_id` AS `scat_id`, `hc_subcategoria`.`scat_detalle` AS `scat_detalle`, `hc_subcategoria`.`scat_catg_catgPadre` AS `scat_catg_catgPadre`, `hc_categoria`.`catg_id` AS `catg_id`, `hc_categoria`.`catg_detalle` AS `catg_detalle` FROM (`hc_subcategoria` join `hc_categoria` on((`hc_subcategoria`.`scat_catg_catgPadre` = `hc_categoria`.`catg_id`))) WHERE (`hc_subcategoria`.`scat_est_idEstado` = 15) ;

-- --------------------------------------------------------

--
-- Structure for view `v_usuarios_activos`
--
DROP TABLE IF EXISTS `v_usuarios_activos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_usuarios_activos`  AS SELECT `hc_usuarios`.`user_id` AS `user_id`, `hc_usuarios`.`user_emp_id` AS `user_emp_id`, `hc_usuarios`.`user_name` AS `user_name`, `hc_usuarios`.`user_password` AS `user_password`, `hc_usuarios`.`user_nivelAcceso` AS `user_nivelAcceso`, `hc_usuarios`.`user_estado` AS `user_estado`, `hc_empleados`.`emp_id` AS `emp_id`, `hc_empleados`.`emp_nombre` AS `emp_nombre`, `hc_empleados`.`emp_apellidos` AS `emp_apellidos`, `hc_empleados`.`emp_cedula` AS `emp_cedula`, `hc_empleados`.`emp_telefono` AS `emp_telefono`, `hc_empleados`.`emp_correo` AS `emp_correo`, `hc_empleados`.`emp_direccion` AS `emp_direccion`, `hc_empleados`.`emp_fechaIngreso` AS `emp_fechaIngreso`, `hc_empleados`.`emp_cuenta` AS `emp_cuenta`, `hc_empleados`.`emp_codigo` AS `emp_codigo`, `hc_empleados`.`emp_foto` AS `emp_foto`, `hc_empleados`.`emp_carnetAgente` AS `emp_carnetAgente`, `hc_empleados`.`emp_carnetArma` AS `emp_carnetArma`, `hc_empleados`.`emp_testPsicologico` AS `emp_testPsicologico`, `hc_empleados`.`emp_huellas` AS `emp_huellas`, `hc_empleados`.`emp_vacaciones` AS `emp_vacaciones`, `hc_empleados`.`emp_licencias` AS `emp_licencias`, `hc_empleados`.`emp_obd_id` AS `emp_obd_id`, `hc_empleados`.`emp_rol_id` AS `emp_rol_id`, `hc_empleados`.`emp_dep_id` AS `emp_dep_id`, `hc_empleados`.`emp_estado` AS `emp_estado`, `hc_acseso`.`acs_id` AS `acs_id`, `hc_acseso`.`acs_nombre` AS `acs_nombre`, `hc_acseso`.`acs_detalle` AS `acs_detalle` FROM ((`hc_usuarios` join `hc_empleados` on((`hc_usuarios`.`user_emp_id` = `hc_empleados`.`emp_id`))) join `hc_acseso` on((`hc_usuarios`.`user_nivelAcceso` = `hc_acseso`.`acs_id`))) WHERE (`hc_usuarios`.`user_estado` = 31) ;

-- --------------------------------------------------------

--
-- Structure for view `v_vehiculo`
--
DROP TABLE IF EXISTS `v_vehiculo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_vehiculo`  AS SELECT `hc_vehiculos`.`veh_id` AS `veh_id`, `hc_vehiculos`.`veh_placa` AS `veh_placa`, `hc_vehiculos`.`veh_marca` AS `veh_marca`, `hc_vehiculos`.`veh_modelo` AS `veh_modelo`, `hc_vehiculos`.`veh_anio` AS `veh_anio`, `hc_vehiculos`.`veh_color` AS `veh_color`, `hc_vehiculos`.`veh_tipo` AS `veh_tipo`, `hc_vehiculos`.`veh_num_chasis` AS `veh_num_chasis`, `hc_vehiculos`.`veh_num_motor` AS `veh_num_motor`, `hc_vehiculos`.`veh_kilometraje` AS `veh_kilometraje`, `hc_vehiculos`.`veh_fecha_vencimiento_seguro` AS `veh_fecha_vencimiento_seguro`, `hc_vehiculos`.`veh_fecha_revision` AS `veh_fecha_revision`, `hc_vehiculos`.`veh_observaciones` AS `veh_observaciones`, `hc_vehiculos`.`veh_fecha_registro` AS `veh_fecha_registro`, `hc_vehiculos`.`veh_estado` AS `veh_estado` FROM `hc_vehiculos` WHERE (`hc_vehiculos`.`veh_estado` = 21) ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hc_electronico`
--
ALTER TABLE `hc_electronico`
  ADD CONSTRAINT `hc_electronico_ibfk_1` FOREIGN KEY (`elec_catg_IDcategoria`) REFERENCES `hc_categoria` (`catg_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `hc_electronico_ibfk_2` FOREIGN KEY (`elec_prov_IDproveedor`) REFERENCES `hc_proveedor` (`prov_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `hc_electronico_ibfk_3` FOREIGN KEY (`elec_scat_IDsubcategoria`) REFERENCES `hc_subcategoria` (`scat_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `hc_empleados`
--
ALTER TABLE `hc_empleados`
  ADD CONSTRAINT `hc_empleados_ibfk_1` FOREIGN KEY (`emp_dep_id`) REFERENCES `hc_departamento` (`dep_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `hc_usuarios`
--
ALTER TABLE `hc_usuarios`
  ADD CONSTRAINT `hc_usuarios_ibfk_1` FOREIGN KEY (`user_emp_id`) REFERENCES `hc_empleados` (`emp_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

<?php
require_once __DIR__ . '/../models/reportSalidasModel.php';

class ordenConroller
{

    /**
     * FUNCIONES PARA LEER
     */

    public function get_orden_trabajo()
    {
        $ordenModel = new ordenModel();
        $electronico = $ordenModel->get_orden_trabajo();

        if ($electronico != 'error') {
            return $electronico;
        } else {
            return 'error';
        }
    }

    public function getLastID()
    {
        $ordenModel = new ordenModel();
        $lastID = $ordenModel->getLastID();

        if ($lastID >= 0) {
            return $lastID;
        } else {
            return 'error';
        }
    }

    public function get_orden_codigo($orden)
    {
        $ordenModel = new ordenModel();
        $orden = $ordenModel->get_orden_codigo($orden);

        if ($orden != 'error') {
            return $orden;
        } else {
            return 'error';
        }
    }

    public function get_buseta()
    {
        $ordenModel = new ordenModel();
        $orden = $ordenModel->get_buseta();

        if ($orden != 'error') {
            return $orden;
        } else {
            return 'error';
        }
    }

    public function update_equipos_orden_id_integrar($id)
    {
        $ordenModel = new ordenModel();
        $orden = $ordenModel->update_equipos_orden_id_integrar($id);

        if ($orden == 'success') {
            return "success";
        } else {
            return 'error';
        }
    }

    public function get_equipos_orden($id)
    {
        $ordenModel = new ordenModel();
        $orden = $ordenModel->get_equipos_orden($id);

        if (!empty($orden)) {
            return $orden;
        } else {
            return [];
        }
    }

    /**
     * FUNCIONES PARA AGREGAR
     */

    public function addOrden($orden, $fecha, $tecnico, $asistente1, $asistente2, $tipoTrabajo, $cliente, $direccion, $telefono, $descripcion, $equipos, $vehiculo)
    {
        
        $ordenModel = new ordenModel();
        $add = $ordenModel->addOrden($orden, $fecha, $tecnico, $asistente1, $asistente2, $tipoTrabajo, $cliente, $direccion, $telefono, $descripcion, $equipos, $vehiculo);

        if ($add === "success") {
            return "success";
        } else {
            return "error";
        }
    }


    /**
     * FUNCIONES PARA EDITAR
     */
    /*
     public function updateOrden($id, $orden, $fecha, $tecnico, $asistente1, $asistente2, $tipoTrabajo, $cliente, $direccion, $telefono, $descripcion, $equipos)
     {
        $ordenModel = new ordenModel();
        $update = $ordenModel->updateOrden($id, $orden, $fecha, $tecnico, $asistente1, $asistente2, $tipoTrabajo, $cliente, $direccion, $telefono, $descripcion, $equipos);

        if ($update === "success") {
            return "success";
        } else {
            return "error";
        }
     }
        */

    public function update_orden_sinEquipo($id, $fecha, $tecnico, $asistente1, $asistente2, $tipoTrabajo, $cliente, $direccion, $telefono, $descripcion, $vehiculo)
    {
        $ordenModel = new ordenModel();
        $update = $ordenModel->update_orden_sinEquipo($id, $fecha, $tecnico, $asistente1, $asistente2, $tipoTrabajo, $cliente, $direccion, $telefono, $descripcion, $vehiculo);
        if ($update === "success") {
            return "success";
        } else {
            return "error";
        }
    }


    /**
     * FUNCIONES PARA ELIMINAR
     */

    public function deleteEquipo($id)
    {
        $ordenModel = new ordenModel();
        $delete = $ordenModel->deleteEquipo($id);

        if ($delete === "success") {
            return "success";
        } else {
            return "error";
        }
    }

    public function delete_equipos_orden_id($id)
    {
        $ordenModel = new ordenModel();
        $orden = $ordenModel->delete_equipos_orden_id($id);

        if ($orden == 'success') {
            return "success";
        } else {
            return 'error';
        }
    }

    /**
     * OTRAS FUNCIONES
     */

    public function valida_equipo_instalacion($id)
    {
        $ordenModel = new ordenModel();
        $validate = $ordenModel->valida_equipo_instalacion($id);

        if ($validate) {
            return $validate;
        }
    }

    public function valida_equipos($id, $fecha, $tecnico, $asistente1, $asistente2, $tipoTrabajo, $cliente, $direccion, $telefono, $descripcion, $vehiculo, $equipos_enviar)
    {
        $ordenModel = new ordenModel();
        $validate = $ordenModel->valida_equipos($id, $fecha, $tecnico, $asistente1, $asistente2, $tipoTrabajo, $cliente, $direccion, $telefono, $descripcion, $vehiculo, $equipos_enviar);

        if ($validate == 'success') {
            return "success";
        } else {
            return 'error';
        }
    }
}

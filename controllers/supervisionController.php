<?php
require_once __DIR__ . '../../models/supervisionModel.php';

class supervisionController
{
    public function get_oficiales_agrupados($estado)
    {
        $controller = new supervisionModel();
        $oficiales = $controller->get_oficiales_agrupados($estado);

        if ($oficiales != 'error') {
            return $oficiales;
        } else {
            return 'error';
        }
    }

    public function get_reportes($estado)
    {
        $controller = new supervisionModel();
        $oficiales = $controller->get_reportes($estado);

        if ($oficiales != 'error') {
            return $oficiales;
        } else {
            return 'error';
        }
    }

    public function get_oficiales_general()
    {
        $controller = new supervisionModel();
        $oficiales = $controller->get_oficiales_general();

        if ($oficiales != 'error') {
            return $oficiales;
        } else {
            return 'error';
        }
    }

    public function get_reportes_general($id)
    {
        $controller = new supervisionModel();
        $oficiales = $controller->get_reportes_general($id);

        if ($oficiales != 'error') {
            return $oficiales;
        } else {
            return 'error';
        }
    }

    public function get_reportes_todos()
    {
        $controller = new supervisionModel();
        $oficiales = $controller->get_reportes_todos();

        if ($oficiales != 'error') {
            return $oficiales;
        } else {
            return 'error';
        }
    }

    /**
     * FUNCIONES PARA AGREGAR
     */

    public function addComentario($id, $motivo, $justificacion, $nombre, $estado)
    {
        $controller = new supervisionModel();
        $oficiales = $controller->addComentario($id, $motivo, $justificacion, $nombre, $estado);

        if ($oficiales != 'error') {
            return 'success';
        } else {
            return 'error';
        }
    }

    /**
     * FUNCIONES PARA EDITAR
     */
    public function update_reporte_oficial($id, $id_reporte, $motivo, $justificacion, $estado, $nombre)
    {
        $controller = new supervisionModel();
        $oficiales = $controller->update_reporte_oficial($id, $id_reporte, $motivo, $justificacion, $estado, $nombre);

        if ($oficiales != 'error') {
            return 'success';
        } else {
            return 'error';
        }
    }

    /**
     * FUNCIONES PARA ELIMINAR
     */

    public function delete_reporte_oficial($id)
    {
        $controller = new supervisionModel();
        $oficiales = $controller->delete_reporte_oficial($id);

        if ($oficiales != 'error') {
            return 'success';
        } else {
            return 'error';
        }
    }
}

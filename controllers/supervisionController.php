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
        }else{
            return 'error';
        }
    }

    public function get_reportes($estado)
    {
        $controller = new supervisionModel();
        $oficiales = $controller->get_reportes($estado);

        if ($oficiales != 'error') {
            return $oficiales;
        }else{
            return 'error';
        }
    }
}

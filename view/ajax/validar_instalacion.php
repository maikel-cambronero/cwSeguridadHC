<?php
require_once '../../controllers/reportSalidasControllers.php';

$orden_id = $_POST['orden_id'] ?? 0;
$controller = new ordenConroller();

echo $controller->valida_equipo_instalacion($orden_id); // devuelve 'instalacion' o 'sin_instalacion'
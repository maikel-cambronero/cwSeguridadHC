<?php

include_once '../../controllers/usuariosController.php';
require_once __DIR__ . '../../../dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

$controller = new usuariosController();

if (isset($_GET['id'])) {
    $emp_id = $_GET['id'];
    $empleados = $controller->get_empleadoID($emp_id);



    $empleado = $empleados[0]; // Tu arreglo obtenido

    $foto = '../../assets/images/empleado/' . $empleado['emp_foto'];

    $img = base64_encode(file_get_contents('../../assets/images/empleado/' . $empleado['emp_foto']));
    $type = pathinfo('../../assets/images/empleado/' . $empleado['emp_foto'], PATHINFO_EXTENSION);
    $foto = 'data:image/' . $type . ';base64,' . $img;

$html = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            margin: 0 30px;
        }
        .titulo {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin: 30px 0 20px;
        }
        .foto {
            width: 100px;
            height: 120px;
            object-fit: cover;
            border: 1px solid #000;
        }
        table.perfil {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .info-derecha {
            padding-left: 15px;
            font-size: 14px;
            vertical-align: top;
        }
        .seccion {
            margin-bottom: 20px;
        }
        .seccion .label {
            font-weight: bold;
            font-size: 14px;
            background-color: #f0f0f0;
            padding: 6px 10px;
            border-left: 4px solid #007bff;
            margin-bottom: 8px;
        }
        .campo {
            margin-bottom: 4px;
            padding-left: 10px;
        }
        .campo strong {
            width: 160px;
            display: inline-block;
        }
    </style>
</head>
<body>

    <div class="titulo">Expediente del Colaborador</div>

    <table class="perfil">
        <tr>
            <td width="110">
                <img src="' . $foto . '" class="foto">
            </td>
            <td class="info-derecha">
                <div class="campo"><strong>Nombre:</strong> ' . $empleado['emp_nombre'] . ' ' . $empleado['emp_apellidos'] . '</div>
                <div class="campo"><strong>Cédula:</strong> ' . $empleado['emp_cedula'] . '</div>
                <div class="campo"><strong>Puesto:</strong> ' . $empleado['rol_detalle'] . '</div>
                <div class="campo"><strong>Departamento:</strong> ' . $empleado['dep_detalle'] . '</div>
            </td>
        </tr>
    </table>

    <div class="seccion">
        <div class="label">Información Personal</div>
        <div class="campo"><strong>Correo:</strong> ' . $empleado['emp_correo'] . '</div>
        <div class="campo"><strong>Teléfono:</strong> ' . $empleado['emp_telefono'] . '</div>
        <div class="campo"><strong>Dirección:</strong> ' . $empleado['emp_direccion'] . '</div>
        <div class="campo"><strong>Licencias:</strong> ' . $empleado['emp_licencias'] . '</div>
    </div>

    <div class="seccion">
        <div class="label">Detalles Laborales</div>
        <div class="campo"><strong>Fecha de Ingreso:</strong> ' . $empleado['emp_fechaIngreso'] . '</div>
        <div class="campo"><strong>Salario:</strong>' . html_entity_decode('&#8353;') . number_format($empleado['emp_salario'], 2) . '</div>
        <div class="campo"><strong>Cuenta:</strong> ' . $empleado['emp_cuenta'] . '</div>
        <div class="campo"><strong>Código Interno:</strong> ' . $empleado['emp_codigo'] . '</div>
    </div>';

    // Mostrar solo si alguno de estos campos tiene valor
    if (!empty($empleado['emp_carnetAgente']) || !empty($empleado['emp_carnetArma']) || !empty($empleado['emp_testPsicologico']) || !empty($empleado['emp_huellas'])) {
        $html .= '
        <div class="seccion">
            <div class="label">Documentación</div>';
        if (!empty($empleado['emp_carnetAgente'])) {
            $html .= '<div class="campo"><strong>Carnet de Agente:</strong> ' . $empleado['emp_carnetAgente'] . '</div>';
        }
        if (!empty($empleado['emp_carnetArma'])) {
            $html .= '<div class="campo"><strong>Carnet de Arma:</strong> ' . $empleado['emp_carnetArma'] . '</div>';
        }
        if (!empty($empleado['emp_testPsicologico'])) {
            $html .= '<div class="campo"><strong>Test Psicológico:</strong> ' . $empleado['emp_testPsicologico'] . '</div>';
        }
        if (!empty($empleado['emp_huellas'])) {
            $html .= '<div class="campo"><strong>Huellas:</strong> ' . $empleado['emp_huellas'] . '</div>';
        }
        $html .= '</div>';
    }

    $html .= '
        <div class="seccion">
            <div class="label">Otros</div>
        <div class="campo"><strong>Vacaciones:</strong> ' . $empleado['emp_vacaciones'] . '</div>
 
        <br>

        <div class="seccion">
            <div class="label">Información Institucional</div>
            <div class="campo"><strong>Empresa:</strong> Fortuna Segura HC S.A.</div>
            <div class="campo"><strong>Cédula Jurídica:</strong> 3-101-470014</div>
            <div class="campo"><strong>Teléfono de Oficina:</strong> 6296-6011</div>
            <div class="campo"><strong>Correo Electrónico:</strong> mcastro@seguridadhc.com</div>
            <div class="campo"><strong>Dirección Física:</strong> La Fortuna, Costa Rica</div>
        </div>

</body>
</html>
';

    //


    $options = new Options();
    $options->set('isRemoteEnabled', true); // Necesario para cargar imágenes

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream('expediente_colaborador.pdf', ['Attachment' => false]);
}

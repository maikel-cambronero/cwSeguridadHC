<?php

include_once '../../controllers/reportSalidasControllers.php';
require_once __DIR__ . '../../../dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

$controller = new ordenConroller();

if (isset($_GET['orden'])) {
    $numOrden = $_GET['orden'];
    $ordenes = $controller->get_orden_codigo($numOrden);

    $equipos = [];
    $orden = null;
    foreach ($ordenes as $index => $fila) {
        if ($index === 0) {
            $orden = [
                'codigo' => $fila['ord_codigo'],
                'fecha' => $fila['ord_fecha'],
                'tecnico' => $fila['ord_tecnico'],
                'asistente1' => $fila['ord_asistente1'],
                'asistente2' => $fila['ord_asistente2'],
                'tipo_trabajo' => $fila['ord_tipoTrabajo'],
                'cliente' => $fila['ord_cliente'],
                'direccion' => $fila['ord_direccion'],
                'telefono' => $fila['ord_telefono'],
                'descripcion' => $fila['ord_descripcion'],
                'vehiculo' => $fila['veh_placa'],
                'modelo' => $fila['veh_modelo']
            ];
        }
    }

    if (!$orden) {
        exit("Orden no encontrada.");
    }

    function getTipoTrabajo($tipo)
    {
        switch ($tipo) {
            case '1':
                return 'Instalación';
            case '2':
                return 'Mantenimiento';
            case '3':
                return 'Revisión';
            default:
                return 'Desconocido';
        }
    }

    $meses = [
        1 => 'enero',
        2 => 'febrero',
        3 => 'marzo',
        4 => 'abril',
        5 => 'mayo',
        6 => 'junio',
        7 => 'julio',
        8 => 'agosto',
        9 => 'septiembre',
        10 => 'octubre',
        11 => 'noviembre',
        12 => 'diciembre'
    ];

    $fechaObj = new DateTime($orden['fecha']);
    $dia = $fechaObj->format('j');
    $mes = $meses[(int)$fechaObj->format('n')];
    $anio = $fechaObj->format('Y');
    $fechaFinal = "$dia de $mes del $anio";

    date_default_timezone_set('America/Costa_Rica');
    $logo = base64_encode(file_get_contents('../../assets/images/logo.png'));
    $type = pathinfo('../../assets/images/logo.png', PATHINFO_EXTENSION);
    $src = 'data:image/' . $type . ';base64,' . $logo;

    $html = '
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8" />
        <style>
            body { 
                font-family: Arial, sans-serif; 
                font-size: 14px; margin: 20px; 
            }

            header { 
                display: flex; 
                justify-content: space-between; 
                align-items: center; 
                margin-bottom: 20px; 
            }

            .empresa { 
                font-weight: bold; 
                font-size: 16px; 
                line-height: 1.2; 
            }

            h1 { 
                text-align: center; 
                text-decoration: underline; 
                font-weight: bold; 
                margin-bottom: 15px; 
                font-size: 18px; 
            }

            .info-orden { 
                text-align: right; 
                margin-bottom: 15px; 
                font-weight: bold; 
            }

            hr { 
                border: none; 
                border-top: 1px solid #aaa; 
                margin: 15px 0; 
            }

            table { 
                width: 100%; 
                border-collapse: collapse; 
                margin-bottom: 15px; 
            }

            th, td { 
                border: 1px solid #aaa; 
                padding: 6px; 
                text-align: center; }
                th { 
                background-color: #eee; 
            }

            .descripcion { 
                border: 1px solid #aaa; 
                padding: 8px; 
                margin-bottom: 14px; 
            }

            .recordatorios { 
                border: 1px solid #000; 
                padding: 8px; 
                font-size: 12px; 
            }

            .firmas {
                width: 100%;
                margin-top: 50px;
                font-size: 12px;
                text-align: center;
            }

            .firma-bloque {
                display: inline-block;
                width: 45%;
                vertical-align: top;
                text-align: center;
                margin: 0 2%;
            }

            .linea-firma {
                border-bottom: 1px solid #000;
                width: 100%;
                height: 20px;
                margin-bottom: 5px;
            }

            .firma-info {
                font-size: 11px;
            }
        </style>
    </head>
    <body>

        <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div class="empresa" style="font-weight: bold; font-size: 14px; line-height: 1.4;">
                FORTUNA SEGURA HC S.A<br/>
                 <span style="font-weight: normal; font-size: 11px;">
            3-101-470014<br/>
            Alajuela, La Fortuna, Sonafluca
        </span>
            </div>
            <div style="text-align: right;">
                <img src="' . $src . '" width="100" style="width: 100px; vertical-align: middle;" />
            </div>
        </header>

        <h1>ORDEN DE TRABAJO</h1>

        <div class="info-orden">
            N° Orden: ' . htmlspecialchars($orden['codigo']) . '<br/>
            Fecha: ' . htmlspecialchars($fechaFinal) . '
        </div>

        <hr/>

        <table>
            <tr>
                <th>Técnico</th>
                <th>1° Asistente</th>';
                if (!empty($orden['asistente2'])) {
                $html .= '<th>2° Asistente</th>';
                }
                $html .= '<th>Tipo de Trabajo</th>
            </tr>
            <tr>
                <td>' . htmlspecialchars($orden['tecnico']) . '</td>
                <td>' . htmlspecialchars($orden['asistente1']) . '</td>';
                if (!empty($orden['asistente2'])) {
                    $html .= '<td>' . htmlspecialchars($orden['asistente2']) . '</td>';
                }
                $html .= '<td>' . htmlspecialchars(getTipoTrabajo($orden['tipo_trabajo'])) . '</td>
            </tr>
        </table>

        <table>
            <tr>
                <th>Cliente</th>
                <th>Dirección</th>
                <th>Teléfono</th>
            </tr>
            <tr>
                <td>' . htmlspecialchars($orden['cliente']) . '</td>
                <td>' . htmlspecialchars($orden['direccion']) . '</td>
                <td>' . htmlspecialchars($orden['telefono']) . '</td>
            </tr>
        </table>

        <table>
            <tr>
                <th>Vehículo Asignado</th>
                <th>Hora de Emisión</th>
            </tr>
            <tr>
                <td>' . 'Model: ' . htmlspecialchars($orden['modelo']) . ' - ' . 'Placa: ' . htmlspecialchars($orden['vehiculo']) . '</td>
                <td>' . date('h:i A') . '</td>
            </tr>
        </table>

        <div class="descripcion">
            <strong>Descripción del trabajo:</strong><br/>
            ' . $orden['descripcion'] . '
        </div>

        <hr/>

        <div class="recordatorios">
            <strong>Recordatorios:</strong>
            <ol>
                <li>Informar cualquier problema imprevisto.</li>
                <li>Llenar la boleta al concluir el trabajo.</li>
                <li>Si se solicita trabajo adicional no cotizado, debe informarse a oficina y al cliente del costo adicional.</li>
                <li>Confirmar presencia del cliente para configurar la app; si requiere segunda visita, puede tener costo adicional.</li>
                <li>Si el encargado no está presente, anotar: - Encargado no presente - enviar fotografía al cliente de las boletas.</li>
            </ol>
        </div>

       <div class="firmas">
            <div class="firma-bloque">
                <strong>Firma Recibido: __________________</strong>
                <br><br>
                <strong>Hora: __________</strong>
            </div>
            <div class="firma-bloque">
                <strong>Firma Cliente: __________________</strong>
                <br><br>
                <strong>Hora: __________</strong>
            </div>
        </div>

        <footer style="text-align:center; font-size:10px; margin-top:30px;">
            Página 1
        </footer>
    </body>
    </html>
    ';

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream($orden['codigo'] . ' - ' . $orden['cliente'] . '.pdf', ['Attachment' => false]);
}

<?php

include_once '../../controllers/cotizacionController.php';
require_once __DIR__ . '../../../dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

$controller = new cotizacionesController();

if (isset($_GET['coti'])) {
    $numCoti = $_GET['coti'];
    $cotizacion = $controller->get_coti_codigo($numCoti);

    foreach ($cotizacion as $index => $fila) {
        if ($index === 0) {
            $coti = [
                'id' => $fila['cot_id'],
                'codigo' => $fila['cot_codigo'],
                'vendor' => $fila['cot_vendor'],
                'cliente' => $fila['cot_cliente'],
                'tell' => $fila['cot_telefono'],
                'fecha1' => $fila['cot_fecha1'],
                'fecha2' => $fila['cot_fecha2'],
                'subtotal' => $fila['cot_subtotal'],
                'iva' => $fila['cot_iva'],
                'desc' => $fila['cot_descuento'],
                'total' => $fila['cot_total']
            ];
        }
    }

    if (is_array($cotizacion) && isset($cotizacion[0]['cot_id'])) {
        $equipos = $controller->get_equiposCoti_codigo($cotizacion[0]['cot_id']);
    } else {
        echo "Error: no se pudo obtener la cotización correctamente.";
        exit;
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

    $fecha1 = new DateTime($coti['fecha1']);
    $dia1 = $fecha1->format('j');
    $mes1 = $meses[(int)$fecha1->format('n')];
    $anio1 = $fecha1->format('Y');
    $fechaFinal1 = "$dia1 de $mes1 del $anio1";

    $fecha2 = new DateTime($coti['fecha2']);
    $dia2 = $fecha2->format('j');
    $mes2 = $meses[(int)$fecha2->format('n')];
    $anio2 = $fecha2->format('Y');
    $fechaFinal2 = "$dia2 de $mes2 del $anio2";

    function mostrarIVA($equipos)
    {
        foreach ($equipos as $item) {
            if (!empty($item['cteq_sub_iva']) && floatval($item['cteq_sub_iva']) > 0) return true;
        }
        return false;
    }

    function mostrarDescuento($equipos)
    {
        foreach ($equipos as $item) {
            if (!empty($item['cteq_sub_desc']) && floatval($item['cteq_sub_desc']) > 0) return true;
        }
        return false;
    }

    $mostrarIVA = mostrarIVA($equipos);
    $mostrarDescuento = mostrarDescuento($equipos);

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
                        font-size: 14px; 
                        margin: 30px;
                        color: #333;
                    }
                    header { 
                        display: flex; 
                        justify-content: space-between; 
                        align-items: center; 
                        margin-bottom: 30px; 
                    }
                    .empresa { 
                        font-weight: bold; 
                        font-size: 16px;    
                        line-height: 1.4; 
                    }
                    h1 { 
                        text-align: center; 
                        text-decoration: underline; 
                        font-size: 22px;
                        margin-bottom: 15px;
                        color: #040404ff;
                    }
                    .info-orden { 
                        text-align: left; 
                        margin-bottom: 15px; 
                        font-weight: bold;
                        margin-bottom: 25px;  
                    }
                    hr { 
                        border: none; 
                        border-top: 1px solid #aaa; 
                        margin: 15px 0; 
                    }
                    table { 
                        width: 100%; 
                        border-collapse: collapse; 
                        margin-bottom: 25px; 
                    }
                    th, td { 
                        border: 1px solid #ccc;
                        padding: 8px;
                        font-size: 13px; 
                        text-align: center; 
                    }
                    th { 
                        background-color: #f0f4f8;
                        text-align: center; 
                    }
                    td {
                        text-align: center;
                    }
                    .observaciones { 
                        border: 1px solid #007BFF; 
                        padding: 12px;
                        background-color: #f0f8ff;
                        font-size: 12px;
                    }
                    .total { 
                        font-weight: bold;
                        text-align: right;
                        background-color: #f9f9f9; 
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

            <h1>Cotización</h1>

            <div class="info-orden" style="font-family: Arial, sans-serif; font-size: 14px; margin-bottom: 20px;">
                <strong>N° Cotización:</strong> ' . htmlspecialchars($coti['codigo']) . '<br/>
                <strong>Fecha de emisión:</strong>  ' . $fechaFinal1 . ' <br/>
                <strong>Fecha de validez:</strong>  ' . $fechaFinal2 . ' 
            </div>

            <hr/>

            <table>
                <tr>
                    <th>Atiende</th>
                    <th>Cliente</th>
                    <th>Teléfono</th>
                </tr>
                <tr>
                    <td>' . htmlspecialchars($coti['vendor']) . '</td>
                    <td>' . htmlspecialchars($coti['cliente']) . '</td>
                    <td>' . htmlspecialchars($coti['tell']) . '</td>
                </tr>
            </table>

            <table>
                <tr>
                    <th>Ítem</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Precio</th>';

    if ($mostrarIVA) $html .= '<th>IVA</th>';
    if ($mostrarDescuento) $html .= '<th>Descuento</th>';

    $html .= '<th>Total de Línea</th></tr>';

    $i = 1;
    foreach ($equipos as $item) {
        $html .= '<tr>
        <td>' . $i++ . '</td>
        <td>' . htmlspecialchars($item['cteq_detalle']) . '</td>
        <td>' . $item['cteq_can'] . '</td>
        <td>' . number_format($item['cteq_precio'], 2) . '</td>';

        if ($mostrarIVA) {
            $iva = floatval($item['cteq_sub_iva']);
            $html .= '<td>' . ($iva > 0 ? number_format($iva, 2) : '-') . '</td>';
        }

        if ($mostrarDescuento) {
            $desc = floatval($item['cteq_sub_desc']);
            $html .= '<td>' . ($desc > 0 ? number_format($desc, 2) : '-') . '</td>';
        }

        $html .= '<td>' . number_format($item['cteq_total_linea'], 2) . '</td></tr>';
    }

    $colspan = 4 + ($mostrarIVA ? 1 : 0) + ($mostrarDescuento ? 1 : 0);

    $html .= '
    <tr><td colspan="' . $colspan . '" class="total">Subtotal</td><td>' . number_format($coti['subtotal'], 2) . '</td></tr>';

    if ($mostrarIVA) {
        $html .= '<tr><td colspan="' . $colspan . '" class="total">IVA</td><td>' . number_format($coti['iva'], 2) . '</td></tr>';
    }
    if ($mostrarDescuento) {
        $html .= '<tr><td colspan="' . $colspan . '" class="total">Descuento</td><td>' . number_format($coti['desc'], 2) . '</td></tr>';
    }

    $html .= '<tr><td colspan="' . $colspan . '" class="total">Total General</td><td>' . number_format($coti['total'], 2) . '</td></tr>
</table>

<div class="observaciones">
    <strong>Condiciones Generales:</strong><br/>
    • Esta cotización es válida hasta la fecha indicada.<br/>
    • Los precios están sujetos a cambio sin previo aviso.<br/>
</div>

<h1 style="margin-top: 40px;">¡Gracias por confiar en nosotros!</h1>';

    $html .= '<div style="page-break-before: always;"></div>';

    $html .= '
<pagebreak />

<div style="page-break-before: always; font-family: Arial, sans-serif; font-size: 12px; line-height: 1.6; color: #333;">

    <h2 style="text-align: center; font-size: 16px; font-weight: bold; margin-bottom: 15px;">
        CONDICIONES DE VENTA, GARANTÍA E INSTALACIÓN
    </h2>

    <p>
        En nuestra empresa nos comprometemos a ofrecer soluciones integrales en seguridad electrónica, garantizando equipos de alta calidad, instalaciones profesionales y un acompañamiento técnico responsable.
        Las siguientes condiciones establecen los lineamientos bajo los cuales se realiza la venta, instalación y garantía de nuestros productos, con el objetivo de asegurar el correcto funcionamiento de los sistemas y la satisfacción del cliente.
    </p>

    <h3 style="margin-top: 15px;">1. Alcance del Servicio</h3>
    <p>
        La venta de equipos está sujeta a la contratación del servicio completo de instalación profesional ofrecido por la empresa.
        <strong>No se realiza venta directa de equipos al público en general sin instalación</strong>, salvo en casos excepcionales previamente autorizados y validados por la administración.
        Esta política tiene como finalidad garantizar la correcta puesta en marcha del sistema, la validez de la garantía y el cumplimiento de los estándares técnicos requeridos.
    </p>

    <h3 style="margin-top: 15px;">2. Garantía de los Equipos</h3>
    <strong>Cobertura:</strong>
    <ul>
        <li>Todos los equipos cuentan con una garantía de doce (12) meses desde la fecha de compra.</li>
        <li>La garantía cubre únicamente defectos de fabricación (fallos internos o componentes defectuosos).</li>
    </ul>

    <strong>Exclusiones:</strong>
    <ul>
        <li>Daños por uso inadecuado, golpes, caídas o manipulación no autorizada.</li>
        <li>Instalaciones hechas por personal no certificado o externo a la empresa.</li>
        <li>Sobretensiones eléctricas, humedad, incendios, desastres naturales o causas externas.</li>
        <li>Desgaste natural de accesorios o consumibles.</li>
    </ul>

    <strong>Procedimiento para Reclamo:</strong>
    <ul>
        <li>Presentar factura o comprobante original de compra.</li>
        <li>La empresa realizará una inspección técnica para confirmar la falla.</li>
    </ul>

    <strong>Si procede la garantía:</strong>
    <ul>
        <li>Durante los primeros tres (3) meses: reparación o reemplazo sin costo.</li>
        <li>Posteriormente: soporte según tipo de falla y disponibilidad de repuestos.</li>
    </ul>

    <h3 style="margin-top: 15px;">3. Garantía de Instalación</h3>

    <strong>Cobertura:</strong>
    <ul>
        <li>La instalación tiene una garantía de dos (2) meses desde su realización.</li>
        <li>Aplica solo a fallas relacionadas directamente con la instalación (conexiones defectuosas, errores de montaje).</li>
    </ul>

    <strong>Exclusiones:</strong>
    <ul>
        <li>Intervenciones de terceros no autorizados.</li>
        <li>Daños por sobrecargas, humedad, golpes o problemas eléctricos externos.</li>
        <li>Incompatibilidades no informadas previamente.</li>
    </ul>

    <strong>Revisión Técnica:</strong>
    <ul>
        <li>Durante el primer mes, revisión sin costo si se detecta un fallo atribuible a la instalación.</li>
        <li>Si la falla es parte de la instalación original, se repara sin cargo.</li>
        <li>Si es ajena, se ofrecerá presupuesto para su reparación.</li>
    </ul>

    <strong>Condiciones para validar la garantía:</strong>
    <ul>
        <li>Instalación debe ser realizada por técnicos certificados de la empresa.</li>
        <li>Intervención no autorizada anula automáticamente la garantía.</li>
        <li>Esta garantía cubre solo mano de obra, no incluye componentes (salvo garantía del fabricante).</li>
    </ul>

    <h3 style="margin-top: 15px;">4. Consideraciones Adicionales</h3>

    <strong>Estabilidad del Servicio de Internet:</strong>
    <ul>
        <li>Algunos equipos requieren conexión a internet estable.</li>
        <li>No nos responsabilizamos por fallas causadas por conexión inestable o deficiente.</li>
    </ul>

    <strong>Consumo de Datos:</strong>
    <p>
        Si los equipos usan Wi-Fi o red móvil, el cliente debe contar con un plan adecuado para garantizar su funcionamiento.
    </p>

    <strong>Configuración de Aplicaciones:</strong>
    <ul>
        <li>El cliente debe estar presente el día de la instalación para la configuración de apps móviles.</li>
        <li>Una visita adicional para configurar puede generar un costo extra.</li>
    </ul>

    <h3 style="margin-top: 15px;">5. Aceptación de Condiciones</h3>
    <p>
        La contratación de los servicios implica la aceptación expresa de estas condiciones.
        Estas pueden actualizarse sin previo aviso según políticas internas de la empresa.
    </p>

</div>


</body>
</html>';

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream($coti['codigo'] . ' - ' . $coti['cliente'] . '.pdf', ['Attachment' => false]);
}

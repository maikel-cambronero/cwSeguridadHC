<?php

include_once '../../controllers/reportSalidasControllers.php';
require('../../fpdf/fpdf.php');

$controller = new ordenConroller();

if (isset($_GET['orden'])) {
    $numOrden = $_GET['orden'];
    $ordenes = $controller->get_orden_codigo($numOrden);

    $equipos = [];
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
                'descripcion' => $fila['ord_descripcion']
            ];
        }
    }

    class PDF extends FPDF
    {
        public $orden;

        function __construct($orden)
        {
            parent::__construct();
            $this->orden = $orden;
        }

        function Header()
        {
            $this->Image('../../assets/images/logo.png', 165, 10, 30); // mueve el logo a la derecha (X = 165)

            // Datos de la empresa a la izquierda
            $this->SetXY(10, 10);
            $this->SetFont('Arial', 'B', 11);
            $this->Cell(0, 5, 'FORTUNA SEGURA HC S.A', 0, 1, 'L'); // Nombre de la empresa en negrita

            $this->SetFont('Arial', 'B', 10);
            $this->Cell(0, 5, '3-101-470014', 0, 1, 'L');

            $this->SetFont('Arial', '', 10);
            $this->Cell(0, 5, mb_convert_encoding('Alajuela, La Fortuna, Sonafluca', 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');

            $this->Ln(25); // Espacio después del encabezado
        }

        function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, mb_convert_encoding('Página', 'ISO-8859-1', 'UTF-8') . $this->PageNo(), 0, 0, 'C');
        }
    }

    // Crear PDF
    $pdf = new PDF($orden);
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12);

    // Título de orden
    $pdf->SetFont('Arial', 'BU', 16); // B = negrita, U = subrayado
    $pdf->Cell(0, 10, mb_convert_encoding('ORDEN DE TRABAJO', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
    $pdf->Ln(3); // Espacio después del título

    $codOrden = $orden['codigo'];
    $fecha = new DateTime($orden['fecha']);

    // Fecha en español manual
    $meses = array(
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
    );
    $dia = $fecha->format('j');
    $mes = $meses[(int)$fecha->format('n')];
    $anio = $fecha->format('Y');
    $fechaFinal = "$dia de $mes del $anio";

    $pdf->SetFont('Arial', '', 10);

    // Número de orden
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetXY(130, $pdf->GetY());
    $pdf->Cell(60, 6, mb_convert_encoding('N° Orden: ', 'ISO-8859-1', 'UTF-8') . $codOrden, 0, 1, 'R');

    // Fecha
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetXY(130, $pdf->GetY());
    $pdf->Cell(60, 6, mb_convert_encoding('Fecha: ', 'ISO-8859-1', 'UTF-8') . mb_convert_encoding($fechaFinal, 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');

    $pdf->Ln(4); // Espacio

    // Línea horizontal
    $y = $pdf->GetY(); // Posición vertical actual
    $pdf->Line(10, $y, 200, $y); // Línea de lado a lado

    $pdf->Ln(5); // Espacio

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

    $margenIzquierdo = 25; // Centrado para tabla de 160 mm
    $pdf->SetX($margenIzquierdo); // Mueve la posición horizontal

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetLineWidth(0.1);
    $pdf->SetDrawColor(180, 180, 180); // gris claro
    $alturaCelda = 7;

    if (!empty($orden['asistente2'])) {
        $colWidth = 40;

        // Encabezados mb_convert_encoding($fechaFinal, 'ISO-8859-1', 'UTF-8')
        $pdf->Cell($colWidth, $alturaCelda, mb_convert_encoding('Técnico', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
        $pdf->Cell($colWidth, $alturaCelda, mb_convert_encoding('1° Asistente', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
        $pdf->Cell($colWidth, $alturaCelda, mb_convert_encoding('2° Asistente', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
        $pdf->Cell($colWidth, $alturaCelda, 'Tipo de Trabajo', 1, 1, 'C');

        $pdf->SetX($margenIzquierdo); // Asegura alineación en la siguiente fila
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell($colWidth, $alturaCelda, $orden['tecnico'], 1, 0, 'C');
        $pdf->Cell($colWidth, $alturaCelda, $orden['asistente1'], 1, 0, 'C');
        $pdf->Cell($colWidth, $alturaCelda, $orden['asistente2'], 1, 0, 'C');
        $pdf->Cell($colWidth, $alturaCelda, mb_convert_encoding(getTipoTrabajo($orden['tipo_trabajo']), 'ISO-8859-1', 'UTF-8'), 1, 1, 'C');
    } else {
        $colWidth = 53.33;

        // Encabezados
        $pdf->Cell($colWidth, $alturaCelda, mb_convert_encoding('Técnico', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
        $pdf->Cell($colWidth, $alturaCelda, 'Asistente', 1, 0, 'C');
        $pdf->Cell($colWidth, $alturaCelda, 'Tipo de Trabajo', 1, 1, 'C');

        $pdf->SetX($margenIzquierdo);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell($colWidth, $alturaCelda, $orden['tecnico'], 1, 0, 'C');
        $pdf->Cell($colWidth, $alturaCelda, $orden['asistente1'], 1, 0, 'C');
        $pdf->Cell($colWidth, $alturaCelda, mb_convert_encoding(getTipoTrabajo($orden['tipo_trabajo']), 'ISO-8859-1', 'UTF-8'), 1, 1, 'C');
    }

    $pdf->Ln(5); // Espacio

    // Calculamos el ancho total de la página menos márgenes


    /// Obtener el ancho total de la página
    $pageWidth = $pdf->GetPageWidth();

    // Obtener el margen izquierdo actual (generalmente es el margen izquierdo)
    $leftMargin = $pdf->GetX();

    // Asumir que el margen derecho es igual al izquierdo
    $rightMargin = $leftMargin;

    // Calcular el ancho útil (ancho de página menos márgenes)
    $usableWidth = $pageWidth - $leftMargin - $rightMargin;

    // Dividir el ancho útil en 3 columnas iguales
    $colWidth = $usableWidth / 3;

    // Encabezados
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell($colWidth, 10, mb_convert_encoding('Cliente', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
    $pdf->Cell($colWidth, 10, mb_convert_encoding('Dirección', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
    $pdf->Cell($colWidth, 10, mb_convert_encoding('Teléfono', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C'); // Salto de línea

    // Fila de datos
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell($colWidth, 10, mb_convert_encoding($orden['cliente'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
    $pdf->Cell($colWidth, 10, mb_convert_encoding($orden['direccion'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
    $pdf->Cell($colWidth, 10, mb_convert_encoding($orden['telefono'], 'ISO-8859-1', 'UTF-8'), 1, 1, 'C');
    $pdf->Ln(5); // Espacio

    // Línea horizontal
    $y = $pdf->GetY(); // Posición vertical actual
    $pdf->Line(10, $y, 200, $y); // Línea de lado a lado

    $pdf->Ln(4); // Espacio

    // Limpiar etiquetas HTML y entidades especiales
    $descripcionLimpia = html_entity_decode(strip_tags($orden['descripcion']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $descripcionLimpia = str_replace("\xc2\xa0", ' ', $descripcionLimpia); // Reemplaza espacios duros

    // Imprimir descripción en el PDF
    $pdf->Ln(8); // Espacio
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, $alturaCelda, mb_convert_encoding('Descripción del trabajo', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C');
    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 7, mb_convert_encoding($descripcionLimpia, 'ISO-8859-1', 'UTF-8'), 1);

    // Línea horizontal
    $y = $pdf->GetY(); // Posición vertical actual
    $pdf->Line(10, $y, 200, $y); // Línea de lado a lado

    $pdf->Ln(5); // Espacio

    // Título Recordatorios (con borde)
    $pdf->Ln(5); // Espacio antes
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 8, mb_convert_encoding('Recordatorios', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C');

    $pdf->SetFont('Arial', '', 10);
    $recordatorios = [
        '1. Informar cualquier problema imprevisto.',
        '2. Llenar la boleta al concluir el trabajo.',
        '3. Si se solicita trabajo adicional no cotizado, debe informarse a oficina y al cliente del costo adicional.',
        '4. Confirmar presencia del cliente para configurar la app; si requiere segunda visita, puede tener costo adicional.',
        '5. Si el encargado no está presente, anotar: - Ecargado no presente - enviar fotografía al cliente de las boletas.'
    ];

    $cellHeight = 6;
    $cellWidth = 0;

    foreach ($recordatorios as $linea) {
        $pdf->Cell($cellWidth, $cellHeight, mb_convert_encoding($linea, 'ISO-8859-1', 'UTF-8'), 1, 1, 'L');
    }

    /**
     * DOS FIRMAS: Recibido y Finalización
     */

    $espacioMinFirma = 25; // espacio que necesita la firma (línea + texto)
    $espacioSuperior = 15; // espacio visual arriba de la firma si hay espacio suficiente
    $posYActual = $pdf->GetY();
    $pageHeight = $pdf->GetPageHeight();
    $bottomMargin = 15; // margen inferior

    // Calcular espacio restante en la página
    $espacioRestante = $pageHeight - $posYActual - $bottomMargin;

    // Si no hay espacio suficiente, ir a nueva página
    if ($espacioRestante < ($espacioMinFirma + $espacioSuperior)) {
        $pdf->AddPage();
    } else {
        // Si hay buen espacio, agregar un margen visual arriba de las firmas
        $pdf->Ln($espacioRestante - $espacioMinFirma);
    }

    // Configuración
    $lineWidth = 60;
    $pageWidth = $pdf->GetPageWidth();
    $yFirma = $pdf->GetY();
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetLineWidth(0.1);

    // Posiciones X para ambas firmas
    $margin = 20; // margen lateral
    $xLeft = $margin;
    $xRight = $pageWidth - $margin - $lineWidth;

    // Dibujar líneas de firma
    $pdf->Line($xLeft, $yFirma, $xLeft + $lineWidth, $yFirma);     // Firma izquierda
    $pdf->Line($xRight, $yFirma, $xRight + $lineWidth, $yFirma);   // Firma derecha

    // Texto debajo de cada línea
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetY($yFirma + 3);

    $pdf->SetXY($xLeft, $pdf->GetY());
    $pdf->Cell($lineWidth, 6, mb_convert_encoding('Firma y Cédula del Técnico', 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');

    $pdf->SetXY($xRight, $pdf->GetY());
    $pdf->Cell($lineWidth, 6, mb_convert_encoding('Firma y Cédula del Encargado', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

    $pdf->Output('I', $codOrden . " - " . mb_convert_encoding($orden['cliente'], 'ISO-8859-1', 'UTF-8') . '.pdf'); // Mostrar PDF
}

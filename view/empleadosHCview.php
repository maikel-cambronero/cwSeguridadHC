<?php
require_once '../routes/rutas.php';
session_start();

// Verificación de sesión
if (!isset($_SESSION['usuario'])) {
    header('Location: ' . BASE_PATH . '/index.php');
    exit;
}

// Verificación de nivel de acceso
if (($_SESSION['nivel_acceso'] != 1 && $_SESSION['nivel_acceso'] != 6)) {
?>
    <style>
        #container {
            display: none;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            title: 'Acceso denegado',
            text: 'Permisos insuficientes.',
            icon: 'error',
            timer: 2000,
            timerProgressBar: true,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            backdrop: 'rgba(0, 0, 0, 0.9)'
        }).then(() => {
            window.location.href = '<?= BASE_PATH ?>/dashboard.php';
        });
    </script>
    <?php
    exit; // Para asegurarte que no siga cargando contenido
}

// Incluir el header
require_once 'layout/header.php';
require_once '../controllers/usuariosController.php';

$controller = new usuariosController();

$colaboradores = $controller->get_empleado();
$roles = $controller->get_rol();
$colaboradores_general = $controller->get_empleados();



function formatoFecha($fecha)
{
    if (empty($fecha)) return null;
    $fechaObj = DateTime::createFromFormat('d/m/Y', $fecha);
    return $fechaObj ? $fechaObj->format('Y-m-d') : null;
}

if (isset($_POST['nuevo'])) {
    $errores = [];

    $camposValidar = [
        'name' => 'Nombre',
        'apellido' => 'Apellido',
        'cedula' => 'Cédula',
        'telefono' => 'teléfono',
        'email' => 'Correo',
        'fecha_ingreso' => 'Fecha de Ingreso',
        'direccion' => 'Dirección',
        'salario' => 'Salario',
        'depto' => 'Departamento',
        'rol' => 'Rol'
    ];

    foreach ($camposValidar as $campo => $nombreCampo) {
        if (empty($_POST[$campo])) {
            $errores[] = $nombreCampo;
        }
    }

    if (empty($errores)) {

        $nombre = $_POST['name'];
        $apellido = $_POST['apellido'];
        $cedula = $_POST['cedula'];
        $telefono = $_POST['telefono'];
        $correo = $_POST['email'];
        $fecha_ingreso = formatoFecha($_POST['fecha_ingreso']);
        $direccion = $_POST['direccion'];
        $salario = $_POST['salario'];
        $cuenta = $_POST['cuenta'];
        $depto = $_POST['depto'];
        $rol = $_POST['rol'];
        $vacaciones = $_POST['fecha_vacaciones'];
        $licencias = $_POST['licencias'];
        $carnet_agente  = formatoFecha($_POST['fecha_agente']);
        $carnet_armas   = formatoFecha($_POST['fecha_arma']);
        $psicologico    = formatoFecha($_POST['fecha_psicologico']);
        $huellas        = formatoFecha($_POST['fecha_huellas']);
        $delta = $_POST['delta'];
        $puesto = $_POST['puesto'];

        // Crear nombre de imagen
        $nombreImagen = strtolower(str_replace(' ', '-', $nombre . '-' . $apellido));
        $nombreArchivoFinal = "sin_img.png"; // Por defecto

        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
            $permitido = ['image/jpg', 'image/jpeg', 'image/png', 'image/webp']; // corregido
            $tipo = $_FILES['foto']['type'];

            if (in_array($tipo, $permitido)) {
                $origen = $_FILES['foto']['tmp_name'];
                $info = pathinfo($_FILES['foto']['name']);
                $ext = strtolower($info['extension']);

                $nombreArchivoFinal = "$nombreImagen.$ext"; // Este es el que se guarda en BD
                $destino = "../assets/images/empleado/" . $nombreArchivoFinal;

                // Crear imagen desde archivo
                switch ($ext) {
                    case 'jpg':
                    case 'jpeg':
                        $original = imagecreatefromjpeg($origen);
                        break;
                    case 'png':
                        $original = imagecreatefrompng($origen);
                        break;
                    case 'webp':
                        $original = imagecreatefromwebp($origen);
                        break;
                    default:
                        $original = null;
                        $nombreArchivoFinal = "sin_img.png"; // Por si acaso
                }

                if ($original) {
                    $widthOriginal = imagesx($original);
                    $heightOriginal = imagesy($original);

                    $nuevaImagen = imagecreatetruecolor(300, 300);

                    // Redimensionar
                    imagecopyresampled($nuevaImagen, $original, 0, 0, 0, 0, 300, 300, $widthOriginal, $heightOriginal);

                    // Guardar imagen
                    switch ($ext) {
                        case 'jpg':
                        case 'jpeg':
                            imagejpeg($nuevaImagen, $destino, 90);
                            break;
                        case 'png':
                            imagepng($nuevaImagen, $destino);
                            break;
                        case 'webp':
                            imagewebp($nuevaImagen, $destino);
                            break;
                    }

                    imagedestroy($original);
                    imagedestroy($nuevaImagen);
                } else {
                    $nombreArchivoFinal = "sin_img.png";
                }
            }
        }

        // Ahora enviar a la base de datos SOLO el nombre del archivo
        $addColab = $controller->addColab($nombre, $apellido, $cedula, $telefono, $correo, $fecha_ingreso, $direccion, $salario, $cuenta, $depto, $rol, $vacaciones, $licencias, $carnet_agente, $carnet_armas, $psicologico, $huellas, $nombreArchivoFinal, $delta, $puesto);

        if ($addColab == 'success') {
?>
            <script>
                let errores = <?= json_encode(implode("\n", $errores)); ?>;
                swal.fire({
                    title: 'Felecicdades',
                    text: 'El colaborador fue registraso satisfactoriamente',
                    icon: 'success',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/empleadosHC.php';
                    }
                })
            </script>
        <?php
        } else {
        ?>
            <script>
                let errores = <?= json_encode(implode("\n", $errores)); ?>;
                swal.fire({
                    title: 'Error',
                    text: 'No se pudo registrar el colaborador.',
                    icon: 'error',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/empleadosHC.php';
                    }
                })
            </script>
        <?php
        }
    } else {
        ?>
        <script>
            let errores = <?= json_encode(implode("\n", $errores)); ?>;
            swal.fire({
                title: 'Error',
                text: 'Los siguientes campos son requeridos: \n ' + errores,
                icon: 'error',
                confirmButtonText: 'Volver',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= BASE_PATH ?>/empleadosHC.php';
                }
            })
        </script>
        <?php
    }
}

if (isset($_POST['editar'])) {
    $errores = [];
    $camposValidar = [
        'name' => 'Nombre',
        'apellido' => 'Apellido',
        'cedula' => 'Cédula',
        'telefono' => 'Teléfono',
        'email' => 'Correo',
        'fecha_ingreso' => 'Fecha de Ingreso',
        'direccion' => 'Dirección',
        'salario' => 'Salario',
        'depto' => 'Departamento',
        'rol' => 'Rol'
    ];

    foreach ($camposValidar as $campo => $nombreCampo) {
        if (empty($_POST[$campo])) {
            $errores[] = $nombreCampo;
        }
    }

    if (empty($errores)) {
        $id = $_POST['id'];
        $nombre = $_POST['name'];
        $apellido = $_POST['apellido'];
        $cedula = $_POST['cedula'];
        $telefono = $_POST['telefono'];
        $correo = $_POST['email'];
        $fecha_ingreso = formatoFecha($_POST['fecha_ingreso']);
        $direccion = $_POST['direccion'];
        $cuenta = $_POST['cuenta'];
        $depto = $_POST['depto'];
        $rol = $_POST['rol'];
        $vacaciones = $_POST['fecha_vacaciones'];
        $licencias = $_POST['licencias'];
        $carnet_agente  = formatoFecha($_POST['fecha_agente']);
        $carnet_armas   = formatoFecha($_POST['fecha_arma']);
        $psicologico    = formatoFecha($_POST['fecha_psicologico']);
        $huellas        = formatoFecha($_POST['fecha_huellas']);

        $imagenAnterior = $_POST['imagen_actual'];
        $nombreImagen = strtolower(str_replace(' ', '-', $nombre . '-' . $apellido));
        $nombreArchivoFinal = $imagenAnterior; // Por defecto se conserva la anterior

        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
            $permitido = ['image/jpg', 'image/jpeg', 'image/png', 'image/webp'];
            $tipo = $_FILES['foto']['type'];

            if (in_array($tipo, $permitido)) {
                $origen = $_FILES['foto']['tmp_name'];
                $info = pathinfo($_FILES['foto']['name']);
                $ext = strtolower($info['extension']);

                $nombreArchivoFinal = "$nombreImagen.$ext";
                $destino = "../assets/images/empleado/" . $nombreArchivoFinal;

                // Eliminar imagen anterior si no es la predeterminada
                $rutaAnterior = "../assets/images/empleado/" . $imagenAnterior;
                if (file_exists($rutaAnterior) && $imagenAnterior !== 'sin_img.png') {
                    unlink($rutaAnterior);
                }

                // Procesar imagen
                switch ($ext) {
                    case 'jpg':
                    case 'jpeg':
                        $original = imagecreatefromjpeg($origen);
                        break;
                    case 'png':
                        $original = imagecreatefrompng($origen);
                        break;
                    case 'webp':
                        $original = imagecreatefromwebp($origen);
                        break;
                    default:
                        $original = null;
                        $nombreArchivoFinal = $imagenAnterior;
                }

                if ($original) {
                    $widthOriginal = imagesx($original);
                    $heightOriginal = imagesy($original);
                    $nuevaImagen = imagecreatetruecolor(300, 300);
                    imagecopyresampled($nuevaImagen, $original, 0, 0, 0, 0, 300, 300, $widthOriginal, $heightOriginal);

                    switch ($ext) {
                        case 'jpg':
                        case 'jpeg':
                            imagejpeg($nuevaImagen, $destino, 90);
                            break;
                        case 'png':
                            imagepng($nuevaImagen, $destino);
                            break;
                        case 'webp':
                            imagewebp($nuevaImagen, $destino);
                            break;
                    }

                    imagedestroy($original);
                    imagedestroy($nuevaImagen);
                } else {
                    $nombreArchivoFinal = $imagenAnterior;
                }
            }
        }

        // Guardar en base de datos
        $addColab = $controller->updateColab($id, $nombre, $apellido, $cedula, $telefono, $correo, $fecha_ingreso, $direccion, $cuenta, $depto, $rol, $vacaciones, $licencias, $carnet_agente, $carnet_armas, $psicologico, $huellas, $nombreArchivoFinal);

        if ($addColab == 'success') {
        ?>
            <script>
                swal.fire({
                    title: '¡Felicidades!',
                    text: 'El colaborador fue editado satisfactoriamente.',
                    icon: 'success',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/empleadosHC.php';
                    }
                })
            </script>
        <?php
        } else {
        ?>
            <script>
                swal.fire({
                    title: 'Error',
                    text: 'No se pudo editar el colaborador.',
                    icon: 'error',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/empleadosHC.php';
                    }
                })
            </script>
        <?php
        }
    } else {
        ?>
        <script>
            let errores = <?= json_encode(implode("\n", $errores)); ?>;
            swal.fire({
                title: 'Error',
                text: 'Los siguientes campos son requeridos:\n' + errores,
                icon: 'error',
                confirmButtonText: 'Volver',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= BASE_PATH ?>/empleadosHC.php';
                }
            })
        </script>
        <?php
    }
}

if (isset($_POST['situacion'])) {
    if (!empty($_POST['estado'] && $_POST['observaciones'])) {
        $id = $_POST['id'];
        $situacion = $_POST['estado'];
        $observacion = $_POST['observaciones'];
        $usuario = $_SESSION['usuario'];

        $colab = $controller->updateEstado($id, $situacion, $usuario, $observacion);

        if ($colab == 'success') {
        ?>
            <script>
                swal.fire({
                    title: 'Felecicdades',
                    text: 'La situcación del colaborador fue editada satisfactoriamente',
                    icon: 'success',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/empleadosHC.php';
                    }
                })
            </script>
        <?php
        } else {
        ?>
            <script>
                swal.fire({
                    title: 'Error',
                    text: 'No se pudo realizar la solicitud.',
                    icon: 'error',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/empleadosHC.php';
                    }
                })
            </script>
        <?php
        }
    } else {
        ?>
        <script>
            swal.fire({
                title: 'Error',
                text: 'Es necesario colocar el estado del colaborador',
                icon: 'error',
                confirmButtonText: 'Volver',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= BASE_PATH ?>/empleadosHC.php';
                }
            })
        </script>
<?php
    }
}
?>

<!-- ! Main -->
<main class="main users chart-page" id="skip-target">
    <div class="container">

        <h2 class="main-title text-center">Colaboradores HC</h2>

        <div class="row stat-cards">
            <div class="col-md-2 col-xl-3">
                <!-- El estado 4 indica que es para agregar -->
                <button class="emp_nuevo" data-bs-toggle="modal" data-bs-target="#modalNuevaColaborador" data-estado-agregar="4" style="border: none; background: none;">
                    <article class="stat-cards-item">
                        <div class="icono_nuevo">
                            <i data-feather="plus" style="color: white;"></i>
                        </div>
                        <div class="stat-cards-info">
                            <p class="stat-cards-info__num m-2">Nuevo Colaborador</p>
                        </div>
                    </article>
                </button>
            </div>
        </div>

        <hr class="line mt-1 mb-2 pb-2">

        <!-- Tabla para las herramientas -->
        <div id="botones-filtro" class="btn-group" role="group" aria-label="Basic mixed styles example">
            <a href="<?= BASE_PATH ?>/empleadosHC.php" class="emp_activos btn btn-success" id="emp_activos">Acivos</a>
            <button type="button" class="emp_inactivo btn btn-secondary" id="emp_inactivo" data-estado-ina="29">Inactivos</button>
            <button type="button" class="emp_despedido btn btn-danger" id="emp_despedido" data-estado-desp="30">Egresado</button>
        </div>

        <div class="tabla-inventarios" id="tabla-inventarios">

            <h6 class="indicador m-2 p-2"><b><i>Colaboradores Activos</i></b></h6>

            <div class="row mb-3">
                <!-- Buscador al centro -->
                <div class="col-md-2 text-center contenedor-busqueda" id="contenedor-busqueda"></div>

                <!-- Botones + length en la misma columna -->
                <div class="col-md-2 text-end contenedor-botones">
                    <div id="contenedor-botones"></div>
                </div>
            </div>

            <table id="tablaTodos" class="display mt-2" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Colaborador</th>
                        <th>Teléfono</th>
                        <th>Ingreso</th>
                        <th>Departamento</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (is_array($colaboradores)): ?>
                        <?php foreach ($colaboradores as $colab): ?>
                            <tr>
                                <td data-label="Código"><?= $colab['emp_codigo'] ?></td>
                                <td data-label="Colaborador">
                                    <div>
                                        <span style="color: blue;"><?= $colab['emp_cedula'] ?></span>
                                        <p><?= $colab['emp_nombre'] . " " . $colab['emp_apellidos'] ?></p>
                                    </div>
                                </td>
                                <td data-label="Teléfono"><?= $colab['emp_telefono'] ?></td>
                                <td data-label="Ingreso"><?= date('d/m/Y', strtotime($colab['emp_fechaIngreso'])) ?></td>
                                <td data-label="Departamento"><?= $colab['dep_detalle'] ?></td>
                                <td data-label="Rol"><?= $colab['rol_detalle'] ?></td>

                                <td data-label="Acciones">
                                    <span class="p-relative">
                                        <button class="dropdown-btn transparent-btn" type="button" title="More info">
                                            <div class="sr-only">More info</div>
                                            <i data-feather="more-horizontal" aria-hidden="true"></i>
                                        </button>
                                        <ul class="users-item-dropdown dropdown pt-1">
                                            <li>
                                                <a class="emp_ver" href="ajax/generar_hoja_vida.php?id=<?= $colab['emp_id'] ?>" target="_blank">Expediente</a>
                                            </li>
                                            <li>
                                                <a class="emp_editar" href="#" data-bs-toggle="modal" data-bs-target="#modalEditar" data-estado-editar="5" data-editar-id="<?= $colab['emp_id'] ?>">Editar</a>
                                            </li>
                                            <li>
                                                <a class="emp_situacion" href="#" data-bs-toggle="modal" data-bs-target="#modalSituacion" data-estado-situacion="6" data-situacion-id="<?= $colab['emp_id'] ?>">Situación</a>
                                            </li>
                                        </ul>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No hay herramientas para mostrar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>

            <div class="row mt-3">
                <div class="col-md-6 contenedor-info" id="contenedor-info"></div>
                <div class="col-md-6 text-end contenedor-paginacion" id="contenedor-paginacion"></div>
            </div>
        </div>

    </div>

    <!-- Inicio Modal Nuevo -->
    <div class="modal fade" id="modalNuevaColaborador" tabindex="-1" aria-labelledby="modalNuevaColaboradorLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">

                <div class="modal-header p-2">
                    <h6 class="modal-title" id="modalNuevaColaboradorLabel"><b>Ingresar un Nuevo Colaborador</b></h6>
                    <button type="button" class="btn-close p-1 me-2 mt-1" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div id="formulario-nuevo">

                </div>
            </div>
        </div>
    </div>
    <!-- Fin Modal Nuevo -->


    <!-- Inicio Modal Ver -->
    <div class="modal fade" id="modalVerColaborador" tabindex="-1" aria-labelledby="modalVerColaboradorLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">

                <div class="modal-header p-2">
                    <h6 class="modal-title" id="modalVerColaboradorLabel"><b>Infromación del Colaborador</b></h6>
                    <button type="button" class="btn-close p-1 me-2 mt-1" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div id="formulario-ver">

                </div>
            </div>
        </div>
    </div>
    <!-- Fin Modal Ver -->

    <!-- Inicio Modal Editar -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">

                <div class="modal-header p-2">
                    <h6 class="modal-title" id="modalNuevoProductoLabel"><b>Editar Informaciónn del Colaborador</b></h6>
                    <button type="button" class="btn-close p-1 me-2 mt-1" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div id="formulario-editar">

                </div>

            </div>
        </div>
    </div>
    <!-- Fin Modal Editar -->

    <!-- Inicio Modal Situacion -->
    <div class="modal fade" id="modalSituacion" tabindex="-1" aria-labelledby="modalSituacionLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header py-1 px-2">
                    <h6 class="modal-title" id="modalSituacionLabel"><b>Cambiar Situación del Colaborador</b></h6>
                    <button type="button" class="btn-close p-1 me-2 mt-1" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div id="formulario-situacion">

                </div>
            </div>
        </div>
    </div>
    <!-- Inicio Modal Situacion -->


</main>

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- Botones -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

<!-- Exportación -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<!-- Código JavaScript -->
<script>
    $(document).ready(function() {

        const tabla = $('#tablaTodos').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
                paginate: {
                    previous: '<', // reemplaza "Anterior" por "<"
                    next: '>' // reemplaza "Siguiente" por ">"
                }
            },
            dom: '<"wrapper"Bfrtip>',
            buttons: [{
                    extend: 'copy',
                    text: 'Copiar'
                },
                {
                    extend: 'excel',
                    text: 'Excel'
                },
                {
                    extend: 'pdf',
                    text: 'PDF'
                }
            ],
            pageLength: 10,
        });

        // Mover componentes a sus contenedores
        tabla.buttons().container().appendTo('#contenedor-botones');
        $('#tablaTodos_filter').appendTo('#contenedor-busqueda');
        $('#tablaTodos_info').appendTo('#contenedor-info');
        $('#tablaTodos_paginate').appendTo('#contenedor-paginacion');
    });
</script>

<!-- Petición Ajax para la tabla Colaborador Inactivo -->
<script>
    // Configurar los botones para hacer la petición AJAX
    document.querySelector('.emp_inactivo').addEventListener('click', function() {
        const estado = this.getAttribute('data-estado-ina');
        const contenedor = document.getElementById('tabla-inventarios');

        //Muestra "Cargando..." mientras obtiene el contenido
        contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

        fetch('ajax/tablas-colaborador.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'estado=' + encodeURIComponent(estado)
            })
            .then(res => res.text())
            .then(data => {
                contenedor.innerHTML = data;

                const tabla = $('#tablaInactivo').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
                        paginate: {
                            previous: '<', // reemplaza "Anterior" por "<"
                            next: '>' // reemplaza "Siguiente" por ">"
                        }
                    },
                    dom: '<"wrapper"Bfrtip>',
                    buttons: [{
                            extend: 'copy',
                            text: 'Copiar'
                        },
                        {
                            extend: 'excel',
                            text: 'Excel'
                        },
                        {
                            extend: 'pdf',
                            text: 'PDF'
                        }
                    ],
                    pageLength: 10,
                    columnDefs: [{
                        targets: [0, -1],
                        orderable: false,
                        searchable: false
                    }],
                    drawCallback: function() {
                        // Re-inicializa tooltips, dropdowns o eventos cuando cambia la página
                        $('[data-bs-toggle="dropdown"]').dropdown();
                    }
                });

                // Mover componentes a sus contenedores
                tabla.buttons().container().appendTo('#contenedor-botones');
                $('#tablaInactivo_filter').appendTo('#contenedor-busqueda');
                $('#tablaInactivo_info').appendTo('#contenedor-info');
                $('#tablaInactivo_paginate').appendTo('#contenedor-paginacion');

            })
            .catch(err => {
                contenedor.innerHTML = '<div class="text-danger">Error al cargar la tabla.</div>';
                console.error('Error AJAX:', err);
            });
    });
</script>

<!-- Petición Ajax para la tabla Colaborador Despedido -->
<script>
    // Configurar los botones para hacer la petición AJAX
    document.querySelector('.emp_despedido').addEventListener('click', function() {
        const estado = this.getAttribute('data-estado-desp');
        const contenedor = document.getElementById('tabla-inventarios');

        //Muestra "Cargando..." mientras obtiene el contenido
        contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

        fetch('ajax/tablas-colaborador.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'estado=' + encodeURIComponent(estado)
            })
            .then(res => res.text())
            .then(data => {
                contenedor.innerHTML = data;

                const tabla = $('#tablaDespedido').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
                        paginate: {
                            previous: '<', // reemplaza "Anterior" por "<"
                            next: '>' // reemplaza "Siguiente" por ">"
                        }
                    },
                    dom: '<"wrapper"Bfrtip>',
                    buttons: [{
                            extend: 'copy',
                            text: 'Copiar'
                        },
                        {
                            extend: 'excel',
                            text: 'Excel'
                        },
                        {
                            extend: 'pdf',
                            text: 'PDF'
                        }
                    ],
                    pageLength: 10,
                    columnDefs: [{
                        targets: [0, -1],
                        orderable: false,
                        searchable: false
                    }],
                    drawCallback: function() {
                        // Re-inicializa tooltips, dropdowns o eventos cuando cambia la página
                        $('[data-bs-toggle="dropdown"]').dropdown();
                    }
                });

                // Mover componentes a sus contenedores
                tabla.buttons().container().appendTo('#contenedor-botones');
                $('#tablaDespedido_filter').appendTo('#contenedor-busqueda');
                $('#tablaDespedido_info').appendTo('#contenedor-info');
                $('#tablaDespedido_paginate').appendTo('#contenedor-paginacion');

            })
            .catch(err => {
                contenedor.innerHTML = '<div class="text-danger">Error al cargar la tabla.</div>';
                console.error('Error AJAX:', err);
            });
    });
</script>

<!-- Petición Ajax Nuevo -->
<script>
    const roles = <?= json_encode($roles) ?>;

    // Petición a Formulario Nuevo Producto.
    document.querySelector('.emp_nuevo').addEventListener('click', function() {
        const estado = this.getAttribute('data-estado-agregar');
        const contenedor = document.getElementById('formulario-nuevo');

        //Muestra "Cargando..." mientras obtiene el contenido
        contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

        fetch('ajax/formularios-colaborador.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'estado=' + encodeURIComponent(estado)
            })
            .then(res => res.text())
            .then(data => {
                contenedor.innerHTML = data;
                fechas();
                asignarEventosRoles();
            })
            .catch(err => {
                contenedor.innerHTML = '<div class="text-danger">Error al cargar el formulario.</div>';
                console.error('Error AJAX:', err);
            });
    });

    function fechas() {
        flatpickr("#fecha_ingreso", {
            dateFormat: "d/m/Y"
        });
        flatpickr("#fecha_psicologico", {
            dateFormat: "d/m/Y"
        });
        flatpickr("#fecha_arma", {
            dateFormat: "d/m/Y"
        });
        flatpickr("#fecha_agente", {
            dateFormat: "d/m/Y"
        });
        flatpickr("#fecha_huellas", {
            dateFormat: "d/m/Y"
        });
        flatpickr("#fecha_vacaciones", {
            mode: "range",
            dateFormat: "d/m/Y"
        });
    }

    function asignarEventosRoles() {
        const deptoSelect = document.getElementById('depto');
        const rolSelect = document.getElementById('rol');
        const fechasSeguridad = document.getElementById('fechas-seguridad');

        if (!deptoSelect || !rolSelect) {
            console.warn("No se encontró alguno de los elementos: #depto o #rol");
            return;
        }

        deptoSelect.addEventListener('change', function() {
            const deptoId = this.value;

            // Mostrar u ocultar fechas si es Seguridad
            if (fechasSeguridad) {
                if (deptoId === '7') {
                    fechasSeguridad.classList.remove('d-none');
                } else {
                    fechasSeguridad.classList.add('d-none');
                }
            }

            // Limpiar y llenar select de roles
            rolSelect.innerHTML = '<option value="">Seleccione</option>';
            if (deptoId === '') return;

            const rolesFiltrados = roles.filter(rol => rol.rol_dep_id == deptoId);
            rolesFiltrados.forEach(rol => {
                const option = document.createElement('option');
                option.value = rol.rol_id;
                option.textContent = rol.rol_detalle;
                rolSelect.appendChild(option);
            });
        });
    }
</script>

<!-- Petición Ajax Editar Colaborador -->
<script>
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.emp_editar');
        if (btn) {
            const estado = btn.getAttribute('data-estado-editar');
            const empID = btn.getAttribute('data-editar-id');
            const contenedor = document.getElementById('formulario-editar');

            contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

            fetch('ajax/formularios-colaborador.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'estado=' + encodeURIComponent(estado)
                })
                .then(res => res.text())
                .then(data => {
                    contenedor.innerHTML = data;

                    // Ejecutar funciones adicionales si existen
                    if (typeof fechas === 'function') fechas();
                    if (typeof asignarEventosRoles === 'function') asignarEventosRoles();

                    // Cargar inputs después de que el DOM se actualice
                    setTimeout(() => {
                        if (typeof cargarInputs === 'function') cargarInputs(empID);
                    }, 50);
                })
                .catch(err => {
                    contenedor.innerHTML = '<div class="text-danger">Error al cargar el formulario.</div>';
                    console.error('Error AJAX:', err);
                });
        }
    });

    function fechas() {
        flatpickr("#fecha_ingreso", {
            dateFormat: "d/m/Y"
        });
        flatpickr("#fecha_psicologico", {
            dateFormat: "d/m/Y"
        });
        flatpickr("#fecha_arma", {
            dateFormat: "d/m/Y"
        });
        flatpickr("#fecha_agente", {
            dateFormat: "d/m/Y"
        });
        flatpickr("#fecha_huellas", {
            dateFormat: "d/m/Y"
        });
        flatpickr("#fecha_vacaciones", {
            mode: "range",
            dateFormat: "d/m/Y"
        });
    }

    function asignarEventosRoles() {
        const deptoSelect = document.getElementById('depto');
        const rolSelect = document.getElementById('rol');
        const fechasSeguridad = document.getElementById('fechas-seguridad');

        if (!deptoSelect || !rolSelect) {
            console.warn("No se encontró alguno de los elementos: #depto o #rol");
            return;
        }

        deptoSelect.addEventListener('change', function() {
            const deptoId = this.value;

            // Mostrar u ocultar fechas si es Seguridad
            if (fechasSeguridad) {
                if (deptoId === '7') {
                    fechasSeguridad.classList.remove('d-none');
                } else {
                    fechasSeguridad.classList.add('d-none');
                }
            }

            // Limpiar y llenar select de roles
            rolSelect.innerHTML = '<option value="">Seleccione</option>';
            if (deptoId === '') return;

            const rolesFiltrados = roles.filter(rol => rol.rol_dep_id == deptoId);
            rolesFiltrados.forEach(rol => {
                const option = document.createElement('option');
                option.value = rol.rol_id;
                option.textContent = rol.rol_detalle;
                rolSelect.appendChild(option);
            });
        });
    }

    function formatearFechaDMY(fechaISO) {
        if (!fechaISO) return '';
        const [anio, mes, dia] = fechaISO.split("-");
        return `${dia}/${mes}/${anio}`;
    }

    function cargarInputs(empID) {
        const colaboradores = <?= json_encode($colaboradores_general); ?>;

        const colaborador = colaboradores.find(p => p.emp_id == empID);

        if (!colaborador) {
            console.error('No se encontró al colaborador: ', empID);
            return;
        }

        document.getElementById('id').value = colaborador.emp_id;
        document.getElementById('imagen_actual').value = colaborador.emp_foto;
        document.getElementById('name').value = colaborador.emp_nombre;
        document.getElementById('apellido').value = colaborador.emp_apellidos;
        document.getElementById('cedula').value = colaborador.emp_cedula;
        document.getElementById('telefono').value = colaborador.emp_telefono;
        document.getElementById('email').value = colaborador.emp_correo;
        document.getElementById('fecha_ingreso').value = formatearFechaDMY(colaborador.emp_fechaIngreso);
        document.getElementById('direccion').value = colaborador.emp_direccion;
        document.getElementById('salario').value = colaborador.emp_salario;
        document.getElementById('cuenta').value = colaborador.emp_cuenta;
        document.getElementById('fecha_vacaciones').value = colaborador.emp_vacaciones;
        document.getElementById('licencias').value = colaborador.emp_licencias;

        // Setear el departamento y disparar el evento para cargar roles y fechas
        const deptoSelect = document.getElementById('depto');
        deptoSelect.value = colaborador.emp_dep_id;
        deptoSelect.dispatchEvent(new Event('change'));

        // Esperar un momento para que los roles se carguen antes de asignar el valor
        setTimeout(() => {
            document.getElementById('rol').value = colaborador.emp_rol_id;
        }, 100);

        
        document.getElementById('fecha_agente').value = formatearFechaDMY(colaborador.emp_carnetAgente);
        document.getElementById('fecha_arma').value = formatearFechaDMY(colaborador.emp_carnetArma);
        document.getElementById('fecha_psicologico').value = formatearFechaDMY(colaborador.emp_testPsicologico);
        document.getElementById('fecha_huellas').value = formatearFechaDMY(colaborador.emp_huellas);
        document.getElementById('delta').value = colaborador.emp_delta;
        document.getElementById('puesto').value = colaborador.emp_puesto;
    }
</script>

<!-- Petición Ajax Situacion Colaborador -->
<script>
    document.addEventListener('click', function(e) {
        // Verificamos si el elemento clickeado es un botón de eliminación
        if (e.target.closest('.emp_situacion')) {
            const btn = e.target.closest('.emp_situacion'); // El botón de eliminación que fue clickeado
            const estado = btn.getAttribute('data-estado-situacion');
            const id = btn.getAttribute('data-situacion-id');
            const contenedor = document.getElementById('formulario-situacion');

            // Mostrar mensaje de carga
            contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

            // Hacer petición AJAX para cargar el formulario de eliminación
            fetch('ajax/formularios-colaborador.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'estado=' + encodeURIComponent(estado)
                })
                .then(res => res.text())
                .then(data => {
                    // Insertar el contenido recibido en el contenedor
                    contenedor.innerHTML = data;

                    cargardatos(id);

                    ClassicEditor
                        .create(document.querySelector('#observaciones'))
                        .catch(error => {
                            console.error(error);
                        });

                    // Mostrar el modal de situacion
                    const modalSituacionElement = document.getElementById('modalSituacion');
                    const modal = bootstrap.Modal.getOrCreateInstance(modalSituacionElement);
                    modal.show();
                })
                .catch(err => {
                    contenedor.innerHTML = '<div class="text-danger">Error al cargar el formulario de eliminar.</div>';
                    console.error('Error AJAX:', err);
                });
        }
    });


    function formatearFechaDMY(fechaISO) {
        if (!fechaISO) return '';
        const [anio, mes, dia] = fechaISO.split("-");
        return `${dia}/${mes}/${anio}`;
    }

    function cargardatos(id) {
        const colaboradores = <?= json_encode($colaboradores_general); ?>;

        const colaborador = colaboradores.find(p => p.emp_id == id);

        if (!colaborador) {
            console.error('No se encontró al colaborador: ', id);
            return;
        }

        document.getElementById('id').value = colaborador.emp_id;
        document.getElementById('name').value = colaborador.emp_nombre;
        document.getElementById('apellido').value = colaborador.emp_apellidos;
        document.getElementById('cedula').value = colaborador.emp_cedula;
        document.getElementById('telefono').value = colaborador.emp_telefono;
        document.getElementById('fecha_ingreso_situacion').value = formatearFechaDMY(colaborador.emp_fechaIngreso);
        document.getElementById('estado').value = colaborador.emp_estado;
    }
</script>


<?php
// Incluir el footer
require_once 'layout/footer.php';
?>
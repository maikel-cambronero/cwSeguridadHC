<?php
require_once '../routes/rutas.php';

session_start();

require_once 'layout/header.php';
require_once '../controllers/supervisionController.php';

// Verificación de sesión
if (!isset($_SESSION['usuario'])) {
    header('Location: ' . BASE_PATH . '/index.php');
    exit;
}

// Verificación de nivel de acceso
if (($_SESSION['nivel_acceso'] != 1 && $_SESSION['nivel_acceso'] != 2)) {
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


// Verificación de nivel de acceso
if (($_SESSION['nivel_acceso'] != 1 && $_SESSION['nivel_acceso'] != 3)) {
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

$estado = isset($_GET['estado']) ? intval($_GET['estado']) : 35;

$controller_super = new supervisionController();
$reportes = $controller_super->get_reportes($estado);
$oficiales = $controller_super->get_oficiales_general();
$reportes_generales = $controller_super->get_reportes_todos();


function limpiarNumero($valor)
{
    // Reemplaza cualquier tipo de espacio (normal o duro) y cambia coma por punto decimal
    $valor = preg_replace('/[^\d,\.]/u', '', $valor); // Elimina todo lo que no sea dígito, coma o punto
    $valor = str_replace(',', '.', $valor); // Reemplaza coma por punto
    return floatval($valor);
}



require_once '../controllers/supervisionController.php';

$estado = isset($_GET['estado']) ? intval($_GET['estado']) : 1; // Por defecto 1 = Óptimo

$controller = new supervisionController();

if (isset($_POST['nuevo_comentario'])) {
    $errores = [];
    $camposValidar = [
        'cedula'      => 'Cédula',
        'nombre'       => 'Nombre',
        'delta'        => 'Delta',
        'estado'       => 'Estado',
        'puesto'       => 'Puesto',
        'motivo'       => 'Motivo',
        'justi'        => 'Justificación'
    ];

    foreach ($camposValidar as $campo => $nombreCampo) {
        if (empty($_POST[$campo])) {
            $errores[] = $nombreCampo;
        }
    }

    if (empty($errores)) {
        $id = $_POST['id'];
        $motivo = $_POST['motivo'];
        $justificacion = $_POST['justi'];
        $estado = $_POST['estado'];

        $addComentario = $controller_super->addComentario($id, $motivo, $justificacion, $_SESSION['nombre'], $estado);

        if ($addComentario == 'success') {
    ?>
            <script>
                let errores = <?= json_encode(implode("\n", $errores)); ?>;
                swal.fire({
                    title: 'Felecicdades',
                    text: 'El comentario fue registraso satisfactoriamente',
                    icon: 'success',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/reportesOficiales.php';
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
                    text: 'No se pudo registrar el comentario.',
                    icon: 'error',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/reportesOficiales.php';
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
                    window.location.href = '<?= BASE_PATH ?>/reportesOficiales.php';
                }
            })
        </script>
        <?php
    }
}

if (isset($_POST['editar_comentario'])) {

    $errores = [];
    $camposValidar = [
        'cedula'      => 'Cédula',
        'nombre'       => 'Nombre',
        'delta'        => 'Delta',
        'estado'       => 'Estado',
        'puesto'       => 'Puesto',
        'motivo'       => 'Motivo',
        'justi'        => 'Justificación'
    ];

    foreach ($camposValidar as $campo => $nombreCampo) {
        if (empty($_POST[$campo])) {
            $errores[] = $nombreCampo;
        }
    }

    if (empty($errores)) {

        $id = $_POST['id'];
        $id_reporte = $_POST['id_reporte'];
        $motivo = $_POST['motivo'];
        $justificacion = $_POST['justi'];
        $estado = $_POST['estado'];

        $editar_comentario = $controller_super->update_reporte_oficial($id, $id_reporte, $motivo, $justificacion, $estado, $_SESSION['nombre']);

        if ($editar_comentario == 'success') {
        ?>
            <script>
                let errores = <?= json_encode(implode("\n", $errores)); ?>;
                swal.fire({
                    title: 'Felecicdades',
                    text: 'El comentario fue editado satisfactoriamente',
                    icon: 'success',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/reportesOficiales.php';
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
                    text: 'No se pudo editar el comentario.',
                    icon: 'error',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/reportesOficiales.php';
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
                    window.location.href = '<?= BASE_PATH ?>/reportesOficiales.php';
                }
            })
        </script>
    <?php
    }
}

if (isset($_POST['eliminar_comentario'])) {
    $id = $_POST['id'];

    if (empty($id)) {
    ?>
        <script>
            swal.fire({
                title: 'Error',
                text: 'No se recibió el código del comentario a eliminar',
                icon: 'error',
                confirmButtonText: 'Volver',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= BASE_PATH ?>/reportesOficiales.php';
                }
            })
        </script>
        <?php
    } else {
        $delete = $controller_super->delete_reporte_oficial($id);

        if ($delete == 'success') {
        ?>
            <script>
                swal.fire({
                    title: 'Felecicdades',
                    text: 'El comentario fue eliminado satisfactoriamente',
                    icon: 'success',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/reportesOficiales.php';
                    }
                })
            </script>
        <?php
        } else {
        ?>
            <script>
                swal.fire({
                    title: 'Lo Sentimos',
                    text: 'No se pudo realizar la solicitud',
                    icon: 'error',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/reportesOficiales.php';
                    }
                })
            </script>
    <?php
        }
    }
}
?>

<!-- ! Main -->
<main class="main users chart-page" id="skip-target">
    <div class="container">

        <h2 class="main-title text-center">Inventario Eletrónico</h2>

        <div class="row stat-cards">
            <div class="col-md-2 col-xl-3">
                <!-- El estado 4 indica que es para agregar -->
                <button class="nuevo_comentario" data-bs-toggle="modal" data-bs-target="#modalNuevoComentario" data-estado-agregar="4" style="border: none; background: none;">
                    <article class="stat-cards-item">
                        <div class="icono_nuevo">
                            <i data-feather="plus" style="color: white;"></i>
                        </div>
                        <div class="stat-cards-info">
                            <p class="stat-cards-info__num m-2">Nuevo Comentario</p>
                        </div>
                    </article>
                </button>
            </div>
        </div>

        <hr class="line mt-1 mb-2 pb-2">


        <!-- Botones de filtro -->
        <div id="botones-filtro" class="btn-group" role="group" aria-label="Basic mixed styles example">
            <a href="<?= VIEW_PATH ?>/reportesOficialesView.php?estado=35" class="btn btn-success" id="oficial_bien">Excelente</a>
            <a href="<?= VIEW_PATH ?>/reportesOficialesView.php?estado=36" class="btn btn-warning" id="oficial_medio">Atención</a>
            <a href="<?= VIEW_PATH ?>/reportesOficialesView.php?estado=37" class="btn btn-danger" id="oficial_malo">Crítico</a>
        </div>

        <div class="tabla-inventarios" id="tabla-inventarios">



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
                        <th>Colaborador</th>
                        <th>Delta</th>
                        <th>Puesto</th>
                        <th>Estado</th>
                        <th>Bitácora </th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (is_array($reportes)): ?>
                        <?php foreach ($reportes as $report): ?>
                            <tr>
                                <td data-label="Colaborador">
                                    <div>
                                        <span style="color: #007bff; font-weight: bold;"><?= $report['emp_nombre'] . ' ' . $report['emp_apellidos'] ?></span><br>
                                        <span><?= $report['emp_cedula'] ?></span>
                                    </div>
                                </td>
                                <td data-label="Delta"><?= htmlspecialchars($report['emp_delta']) ?></td>
                                <td data-label="Puesto"><?= htmlspecialchars($report['emp_puesto']) ?></td>
                                <td data-label="Estado">
                                    <?php
                                    switch ($report['emp_estado_supervision']) {
                                        case '35':
                                            echo "Excelente";
                                            break;
                                        case '36':
                                            echo "Atención";
                                            break;
                                        case '37':
                                            echo "Crítico";
                                            break;

                                        default:
                                            echo "Código no identificado";
                                            break;
                                    }
                                    ?>
                                </td>
                                <td data-label="Bitácora "><?= htmlspecialchars($report['ultimo_comentario']) ?></td>
                                <td data-label="Acciones">
                                    <button class="ver_detalles btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalDetalles" data-estado="24" data-codigo="<?= $report['emp_id'] ?>">
                                        <i class="fas fa-search"></i> <!-- Ícono de lupa -->
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No hay reportes para mostrar.</td>
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

    <!-- Inicio Modal Comentario Nuevo -->
    <div class="modal fade" id="modalNuevoComentario" tabindex="-1" aria-labelledby="modalNuevoComentarioLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">

                <div class="modal-header p-2">
                    <h6 class="modal-title" id="modalNuevoComentarioLabel"><b>Agregar Nuevo Comentario</b></h6>
                    <button type="button" class="btn-close p-1 me-2 mt-1" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div id="formulario-nuevo">

                </div>
            </div>
        </div>
    </div>
    <!-- Fin Modal Producto Nuevo -->

    <!-- Inicio Modal detalles  -->
    <div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h6 class="modal-title" id="modalNuevoDetallesLabel"><b>Oficiales</b></h6>
                    <button type="button" class="btn-close p-1 me-2 mt-1" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="tabla-inventarios" id="tabla-inventarios">
                        <div class="detalles p-2" id="detalles">

                        </div>
                    </div>

                </div>
                <div class="modal-footer py-1 px-2">
                    <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Volver</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Fin Modal detalles  -->

    <!-- Inicio Modal Oficales  -->
    <div class="modal fade" id="modalOficiales" tabindex="-1" aria-labelledby="modalOficialLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h6 class="modal-title" id="modalNuevoOficialLabel"><b>Oficiales</b></h6>
                    <button type="button" class="btn-close p-1 me-2 mt-1" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="tabla-inventarios" id="tabla-inventarios">
                        <div class="oficiales_general p-2" id="oficiales_general">
                            <table id="tabla-desagrupado" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Cédula</th>
                                        <th>Código</th>
                                        <th>Puesto</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>

                        </div>
                    </div>

                </div>
                <div class="modal-footer py-1 px-2">
                    <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Volver</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Fin Modal Oficales  -->

    <!-- Inicio Modal Oficales Editar -->
    <div class="modal fade" id="modalOficialesEditar" tabindex="-1" aria-labelledby="modalOficialEditarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h6 class="modal-title" id="modalNuevoOficialEditarLabel"><b>Oficiales</b></h6>
                    <button type="button" class="btn-close p-1 me-2 mt-1" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="tabla-inventarios" id="tabla-inventarios">
                        <div class="oficiales_general p-2" id="oficiales_general">
                            <table id="tabla-desagrupados" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Cédula</th>
                                        <th>Código</th>
                                        <th>Puesto</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>

                        </div>
                    </div>

                </div>
                <div class="modal-footer py-1 px-2">
                    <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Volver</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Fin Modal Oficales Editar -->


    <!-- Inicio Modal Editar -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">

                <div class="modal-header p-2">
                    <h6 class="modal-title" id="modalNuevoProductoLabel"><b>Editar Producto</b></h6>
                    <button type="button" class="btn-close p-1 me-2 mt-1" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div id="formulario-editar">

                </div>

            </div>
        </div>
    </div>
    <!-- Fin Modal Editar -->

    <!-- Inicio Modal Eliminar -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header py-1 px-2">
                    <h5 class="modal-title d-flex align-items-center">
                        <i class="me-2" data-feather="alert-triangle"></i> Eliminar
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div id="formularioEliminar">

                </div>
            </div>
        </div>
    </div>
    <!-- Inicio Modal Eliminar -->

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


<!-- Petición Ajax Nuevo Producto -->
<script>
    let tabla; // Variable global

    // Formulario nuevo producto (como ya lo tenés)
    document.querySelector('.nuevo_comentario').addEventListener('click', function() {
        const estado = this.getAttribute('data-estado-agregar');
        const contenedor = document.getElementById('formulario-nuevo');
        contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

        fetch('ajax/formularios-reportesOficiales.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'estado=' + encodeURIComponent(estado)
            })
            .then(res => res.text())
            .then(data => {
                contenedor.innerHTML = data;

                ClassicEditor.create(document.querySelector('#justi')).catch(console.error);
                ClassicEditor.create(document.querySelector('#motivo')).catch(console.error);

                if (!$.fn.DataTable.isDataTable('#tabla-desagrupado')) {
                    tabla = $('#tabla-desagrupado').DataTable({
                        language: {
                            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
                            paginate: {
                                previous: '<',
                                next: '>'
                            }
                        },
                        dom: '<"wrapper"Bfrtip>',
                        buttons: ['copy', 'excel', 'pdf'],
                        pageLength: 10,
                    });
                }
            })
            .catch(err => {
                contenedor.innerHTML = '<div class="text-danger">Error al cargar el formulario.</div>';
                console.error('Error AJAX:', err);
            });
    });

    let oficialSeleccionado = false;

    function abrirModalOficiales() {
        const modalEl = document.getElementById('modalOficiales');
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
        llenarTablaOficiales();

        // Inicializar bandera
        oficialSeleccionado = false;

        // Cuando se cierre el modal, si NO se seleccionó oficial, desbloquear inputs
        modalEl.addEventListener('hidden.bs.modal', () => {
            if (!oficialSeleccionado) {
                document.querySelectorAll('input').forEach(input => {
                    if (input.id !== 'id') {
                        input.removeAttribute('readonly');
                    }
                });
            }
        }, {
            once: true
        });
    }

    function llenarTablaOficiales() {
        const esMovil = window.innerWidth < 768;
        const oficialess = <?= json_encode($oficiales) ?>;

        if (!tabla) {
            console.error('La tabla no está inicializada');
            return;
        }

        tabla.clear();

        oficialess.forEach(oficial => {
            const btn = `<button class="btn btn-sm btn-primary" onclick="seleccionarOficial('${oficial.emp_id}', '${oficial.nombre}', '${oficial.emp_cedula}', '${oficial.emp_delta}', '${oficial.emp_puesto}')">Seleccionar</button>`;

            if (esMovil) {
                const contenidoMovil = `
                <div class="border rounded p-2 mb-2">
                    <div><strong>Colaborador:</strong> ${oficial.nombre}</div>
                    <div><strong>Cédula:</strong> ${oficial.emp_cedula}</div>
                    <div><strong>Código:</strong> ${oficial.emp_delta}</div>
                    <div><strong>Puesto:</strong> ${oficial.emp_puesto}</div>
                    <div class="mt-2">${btn}</div>
                </div>
            `;
                tabla.row.add([contenidoMovil, '', '', '', '']);
            } else {
                tabla.row.add([
                    oficial.nombre,
                    oficial.emp_cedula,
                    oficial.emp_delta,
                    oficial.emp_puesto,
                    btn
                ]);
            }
        });

        tabla.draw();
    }

    function seleccionarOficial(id, nombre, cedula, delta, puesto) {
        // Marcar como seleccionado
        oficialSeleccionado = true;

        // Llenar los campos
        document.getElementById('cedula').value = cedula;
        document.getElementById('nombre').value = nombre;
        document.getElementById('delta').value = delta;
        document.getElementById('puesto').value = puesto;
        document.getElementById('id').value = id;

        // Cerrar el modal
        bootstrap.Modal.getInstance(document.getElementById('modalOficiales')).hide();
    }
</script>

<!-- Carga la tabla de detalles -->
<script>
    document.querySelector('.ver_detalles').addEventListener('click', function() {
        const estado = this.getAttribute('data-estado');
        const id = this.getAttribute('data-codigo');
        const contenedor = document.getElementById('detalles');
        contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

        fetch('ajax/tabla-reportesOficiales.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'estado=' + encodeURIComponent(estado) + '&id=' + encodeURIComponent(id)
            })
            .then(res => res.text())
            .then(data => {
                contenedor.innerHTML = data;

                if (!$.fn.DataTable.isDataTable('#tabla_detalles')) {
                    tabla = $('#tabla_detalles').DataTable({
                        language: {
                            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
                            paginate: {
                                previous: '<',
                                next: '>'
                            }
                        },
                        dom: '<"wrapper"Bfrtip>',
                        buttons: ['copy', 'excel', 'pdf'],
                        pageLength: 10,
                    });
                }
            })
            .catch(err => {
                contenedor.innerHTML = '<div class="text-danger">Error al cargar la tabla.</div>';
                console.error('Error AJAX:', err);
            });
    });
</script>

<!-- Petición Ajax Editar Producto -->
<script>
    // Productos
    const reportes = <?= json_encode($reportes_generales); ?>;

    // Petición a Editar Producto
    document.addEventListener('click', function(e) {
        if (e.target.closest('.editar')) {
            const btn = e.target.closest('.editar');
            const estado = btn.getAttribute('data-estado-editar');
            const id = btn.getAttribute('data-editar-id');
            const contenedor = document.getElementById('formulario-editar');

            contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

            fetch('ajax/formularios-reportesOficiales.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'estado=' + encodeURIComponent(estado)
                })
                .then(res => res.text())
                .then(data => {
                    contenedor.innerHTML = data;

                    if (!$.fn.DataTable.isDataTable('#tabla-desagrupados')) {
                        tabla = $('#tabla-desagrupados').DataTable({
                            language: {
                                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
                                paginate: {
                                    previous: '<',
                                    next: '>'
                                }
                            },
                            dom: '<"wrapper"Bfrtip>',
                            buttons: ['copy', 'excel', 'pdf'],
                            pageLength: 10,
                        });
                    }

                    const report = reportes.find(p => p.reof_id == id);
                    if (!report) {
                        console.error('Reporte no encontrado:', id);
                        return;
                    }

                    funcionesFormulario_Editar(report);

                    const modalEditarElement = document.getElementById('modalEditar');
                    const modal = bootstrap.Modal.getOrCreateInstance(modalEditarElement);

                    modalEditarElement.addEventListener('shown.bs.modal', function() {
                        const btnClose = modalEditarElement.querySelector('.btn-close');
                        if (btnClose) {
                            btnClose.focus();
                        }
                    });

                    modal.show();
                })
                .catch(err => {
                    contenedor.innerHTML = '<div class="text-danger">Error al cargar el formulario.</div>';
                    console.error('Error AJAX:', err);
                });
        }
    });

    function funcionesFormulario_Editar(producto) {
        // Rellenar los inputs con los valores del producto
        document.getElementById('id').value = producto.emp_id || '';
        document.getElementById('id_reporte').value = producto.reof_id || '';
        document.getElementById('cedula').value = producto.emp_cedula || '';
        document.getElementById('nombre').value = producto.emp_nombre || '';
        document.getElementById('delta').value = producto.emp_delta || '';
        document.getElementById('puesto').value = producto.emp_puesto || '';
        document.getElementById('estado').value = producto.emp_estado_supervision || '';
        document.getElementById('motivo').value = producto.reof_motivo || '';
        document.getElementById('justi').value = producto.reof_justificacion || '';

        // Iniciar los editores de texto enriquecido (por si se reusa el modal)
        ClassicEditor.create(document.querySelector('#motivo')).catch(console.error);
        ClassicEditor.create(document.querySelector('#justi')).catch(console.error);
    }

    function abrirModalOficialesEditar() {
        const modalEditarEl = document.getElementById('modalEditar');
        const modalOficialesEl = document.getElementById('modalOficialesEditar');

        // Si el modal de edición está abierto, cerrarlo
        const modalEditarInst = bootstrap.Modal.getInstance(modalEditarEl);
        if (modalEditarInst) {
            modalEditarInst.hide();
        }

        // Esperar que se oculte para abrir el de oficiales
        modalEditarEl.addEventListener('hidden.bs.modal', () => {
            const modalOficialesInst = new bootstrap.Modal(modalOficialesEl);
            modalOficialesInst.show();

            // ⚠️ Llamar la función correcta de edición
            llenarTablaOficialesEditar();

            oficialSeleccionado = false;

            // Cuando se cierre el modal de oficiales, reabrir el de edición si no se seleccionó
            modalOficialesEl.addEventListener('hidden.bs.modal', () => {
                if (!oficialSeleccionado) {
                    document.querySelectorAll('input').forEach(input => {
                        if (input.id !== 'id') {
                            input.removeAttribute('readonly');
                        }
                    });
                }

                const modalReabrir = new bootstrap.Modal(modalEditarEl);
                modalReabrir.show();
            }, {
                once: true
            });

        }, {
            once: true
        });
    }


    function llenarTablaOficialesEditar() {
        const esMovil = window.innerWidth < 768;
        const oficialess = <?= json_encode($oficiales) ?>;

        console.log(oficialess);

        if (!tabla) {
            console.error('La tabla no está inicializada');
            return;
        }

        tabla.clear();

        oficialess.forEach(oficial => {
            const btn = `<button class="btn btn-sm btn-primary" onclick="seleccionarOficialEditar('${oficial.emp_id}', '${oficial.nombre}', '${oficial.emp_cedula}', '${oficial.emp_delta}', '${oficial.emp_puesto}')">Seleccionar</button>`;

            if (esMovil) {
                const contenidoMovil = `
                <div class="border rounded p-2 mb-2">
                    <div><strong>Colaborador:</strong> ${oficial.nombre}</div>
                    <div><strong>Cédula:</strong> ${oficial.emp_cedula}</div>
                    <div><strong>Código:</strong> ${oficial.emp_delta}</div>
                    <div><strong>Puesto:</strong> ${oficial.emp_puesto}</div>
                    <div class="mt-2">${btn}</div>
                </div>
            `;
                tabla.row.add([contenidoMovil, '', '', '', '']);
            } else {
                tabla.row.add([
                    oficial.nombre,
                    oficial.emp_cedula,
                    oficial.emp_delta,
                    oficial.emp_puesto,
                    btn
                ]);
            }
        });

        tabla.draw();
    }

    function seleccionarOficialEditar(id, nombre, cedula, delta, puesto) {
        // Marcar como seleccionado
        oficialSeleccionado = true;

        const contenedorEditar = document.getElementById('formulario-editar');

        // Llenar los campos dentro del formulario de edición
        contenedorEditar.querySelector('#cedula').value = cedula;
        contenedorEditar.querySelector('#nombre').value = nombre;
        contenedorEditar.querySelector('#delta').value = delta;
        contenedorEditar.querySelector('#puesto').value = puesto;
        contenedorEditar.querySelector('#id').value = id;

        // Cerrar el modal de oficiales
        bootstrap.Modal.getInstance(document.getElementById('modalOficialesEditar')).hide();
    }
</script>

<!-- Petición Ajax Eliminar Producto -->
<script>
    document.addEventListener('click', function(e) {
        // Verificamos si el elemento clickeado es un botón de eliminación
        if (e.target.closest('.eliminar')) {
            const btn = e.target.closest('.eliminar'); // El botón de eliminación que fue clickeado
            const estado = btn.getAttribute('data-estado-estado');
            const id = btn.getAttribute('data-eliminar-id');
            const nombre = btn.getAttribute('data-nombre');
            const contenedor = document.getElementById('formularioEliminar');

            // Mostrar mensaje de carga
            contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

            // Hacer petición AJAX para cargar el formulario de eliminación
            fetch('ajax/formularios-reportesOficiales.php', {
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

                    // Llamar a la función que maneja el formulario del modal
                    funcioneFormulario_eliminar(id, nombre);

                    // Mostrar el modal de eliminación
                    const modalEliminarElement = document.getElementById('modalEliminar');
                    const modal = bootstrap.Modal.getOrCreateInstance(modalEliminarElement);
                    modal.show();
                })
                .catch(err => {
                    contenedor.innerHTML = '<div class="text-danger">Error al cargar el formulario para eliminar.</div>';
                    console.error('Error AJAX:', err);
                });
        }
    });

    function funcioneFormulario_eliminar(id, nombre) {
        // Aquí puedes gestionar el formulario de eliminación con los valores que recibas
        document.getElementById('eliminarId').value = id;
        document.getElementById('CODIGO').textContent = nombre;
    }
</script>


<?php
// Incluir el footer
require_once 'layout/footer.php';
?>
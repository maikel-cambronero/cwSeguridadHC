<?php
require_once '../routes/rutas.php';
session_start();

// Verificación de sesión
if (!isset($_SESSION['usuario'])) {
    header('Location: ' . BASE_PATH . '/index.php');
    exit;
}

// Verificación de nivel de acceso
if (($_SESSION['nivel_acceso'] != 1 && $_SESSION['nivel_acceso'] != 7 && $_SESSION['nivel_acceso'] != 3)) {
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
require_once '../controllers/reportSalidasControllers.php';
require_once '../controllers/electronicoConroller.php';

$controller_electronico = new electronicoConroller();
$productos = $controller_electronico->getElectronicos_Agrupados_general();

$controller = new ordenConroller();
$ordenes = $controller->get_orden_trabajo();

$ordenesFiltradas = [];
$ordenesUnicas = [];

foreach ($ordenes as $orden) {
    $id = $orden['ord_id'];
    if (!in_array($id, $ordenesUnicas)) {
        $ordenesFiltradas[] = $orden;
        $ordenesUnicas[] = $id;
    }

    $equiposGeneral[] = [
        'codigo' => $orden['erd_codigo'],
        'eqDescipt' => $orden['erd_descripcion'],
        'cantidad' => $orden['erd_cantidad'],
        'tipo_equipo' => $orden['erd_tipo'],
        'orden_id' => $orden['erd_orden_id']
    ];
}


if (isset($_POST['nueva_orden'])) {
    $errores = [];
    $camposValidar = [
        'num_orden'      => '# Orden',
        'fecha_sale'       => 'Fecha',
        'tecnico'        => 'Técnico',
        'asistente_1'       => 'Asistente 1',
        'tipo_trabajo'       => 'Tipo de Trabajo',
        'cliente'        => 'Cliente',
        'trabajo'    => 'Trabajo a Relizar'
    ];

    foreach ($camposValidar as $campo => $nombreCampo) {
        if (empty($_POST[$campo])) {
            $errores[] = $nombreCampo;
        }
    }

    if (empty($errores)) {
        $orden = $_POST['num_orden'];
        $fecha = $_POST['fecha_sale'];
        $tecnico = $_POST['tecnico'];
        $asistente1 = $_POST['asistente_1'];
        $asistente2 = $_POST['asistente_2'];
        $tipoTrabajo = $_POST['tipo_trabajo'];
        $cliente = $_POST['cliente'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $descripcion = $_POST['trabajo'];
        $vehiculo = $_POST['vehiculo'];

        $equipos = [];

        if (isset($_POST['codigo'])) {
            for ($i = 0; $i < count($_POST['codigo']); $i++) {
                $equipos[] = [
                    'codigo' => $_POST['codigo'][$i],
                    'descripcion' => $_POST['descripcion'][$i],
                    'cantidad' => $_POST['cantidad'][$i],
                    'tipo_entrega' => $_POST['tipo_entrega'][$i]
                ];
            }
        }

        $addEquipo  = $controller->addOrden($orden, $fecha, $tecnico, $asistente1, $asistente2, $tipoTrabajo, $cliente, $direccion, $telefono, $descripcion, $equipos,  $vehiculo);


        if ($addEquipo == 'success') {
?>
            <script>
                Swal.fire({
                    title: '¡Felicidades!',
                    text: 'La orden fue registrada satisfactoriamente',
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: 'Descargar PDF',
                    cancelButtonText: 'Cerrar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.open('ajax/generar_pdf.php?orden=<?= urlencode($orden) ?>', '_blank');
                        window.location.href = '<?= BASE_PATH ?>/reportSalidas.php';
                    } else {
                        window.location.href = '<?= BASE_PATH ?>/reportSalidas.php';
                    }
                });
            </script>
        <?php
        } else {
        ?>
            <script>
                let errores = <?= json_encode(implode("\n", $errores)); ?>;
                swal.fire({
                    title: 'Error',
                    text: 'No se pudo registrar la orden.',
                    icon: 'error',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/reportSalidas.php';
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
                    window.location.href = '<?= BASE_PATH ?>/reportSalidas.php';
                }
            })
        </script>
        <?php
    }
}

if (isset($_POST['editar_orden'])) {
    $errores = [];
    $camposValidar = [
        'num_orden'      => '# Orden',
        'fecha_sale'       => 'Fecha',
        'tecnico'        => 'Técnico',
        'asistente_1'       => 'Asistente 1',
        'tipo_trabajo'       => 'Tipo de Trabajo',
        'cliente'        => 'Cliente',
        'trabajo'    => 'Trabajo a Relizar'
    ];

    foreach ($camposValidar as $campo => $nombreCampo) {
        if (empty($_POST[$campo])) {
            $errores[] = $nombreCampo;
        }
    }

    if (empty($errores)) {
        $id = $_POST['id'];
        $orden = $_POST['num_orden'];
        $fecha = $_POST['fecha_sale'];
        $tecnico = $_POST['tecnico'];
        $asistente1 = $_POST['asistente_1'];
        $asistente2 = $_POST['asistente_2'];
        $tipoTrabajo = $_POST['tipo_trabajo'];
        $cliente = $_POST['cliente'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $descripcion = $_POST['trabajo'];
        $vehiculo = $_POST['vehiculo'];

        $equipos_actuales = $controller->get_equipos_orden($id);

        $equipos_enviar = [];

        if (isset($_POST['codigo'])) {
            for ($i = 0; $i < count($_POST['codigo']); $i++) {
                $equipos_enviar[] = [
                    'codigo' => $_POST['codigo'][$i],
                    'descripcion' => $_POST['descripcion'][$i],
                    'cantidad' => $_POST['cantidad'][$i],
                    'tipo_entrega' => $_POST['tipo_entrega'][$i]
                ];
            }
        }

        if (!empty($equipos_actuales) && !empty($equipos_enviar)) {

            $equipos_enviar_1 = [];
            foreach ($equipos_enviar as $eq) {
                if ($eq['tipo_entrega'] == 1) {
                    $equipos_enviar_1[$eq['codigo']] = $eq;
                }
            }

            if (empty($equipos_enviar_1)) {
                $update_orden_Equipo = $controller->valida_equipos($id, $fecha, $tecnico, $asistente1, $asistente2, $tipoTrabajo, $cliente, $direccion, $telefono, $descripcion, $vehiculo, $equipos_enviar);
                if ($update_orden_Equipo == "success") {
        ?>
                    <script>
                        Swal.fire({
                            title: '¡Felicidades!',
                            text: 'La orden fue editada satisfactoriamente',
                            icon: 'success',
                            showCancelButton: true,
                            confirmButtonText: 'Descargar PDF',
                            cancelButtonText: 'Cerrar',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.open('ajax/generar_pdf.php?orden=<?= urlencode($orden) ?>', '_blank');
                                window.location.href = '<?= BASE_PATH ?>/reportSalidas.php';
                            } else {
                                window.location.href = '<?= BASE_PATH ?>/reportSalidas.php';
                            }
                        });
                    </script>
                <?php
                } else {
                ?>
                    <script>
                        let errores = <?= json_encode(implode("\n", $errores)); ?>;
                        swal.fire({
                            title: 'Error',
                            text: 'No se pudo editar la orden.',
                            icon: 'error',
                            confirmButtonText: 'Volver',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '<?= BASE_PATH ?>/reportSalidas.php';
                            }
                        })
                    </script>
                <?php
                }
            } else {
                $update_orden_Equipo_suma = $controller->valida_equipos($id, $fecha, $tecnico, $asistente1, $asistente2, $tipoTrabajo, $cliente, $direccion, $telefono, $descripcion, $vehiculo, $equipos_enviar);
                if ($update_orden_Equipo_suma == "success") {
                ?>
                    <script>
                        Swal.fire({
                            title: '¡Felicidades!',
                            text: 'La orden fue editada satisfactoriamente',
                            icon: 'success',
                            showCancelButton: true,
                            confirmButtonText: 'Descargar PDF',
                            cancelButtonText: 'Cerrar',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.open('ajax/generar_pdf.php?orden=<?= urlencode($orden) ?>', '_blank');
                                window.location.href = '<?= BASE_PATH ?>/reportSalidas.php';
                            } else {
                                window.location.href = '<?= BASE_PATH ?>/reportSalidas.php';
                            }
                        });
                    </script>
                <?php
                } else {
                ?>
                    <script>
                        let errores = <?= json_encode(implode("\n", $errores)); ?>;
                        swal.fire({
                            title: 'Error',
                            text: 'No se pudo editar la orden.',
                            icon: 'error',
                            confirmButtonText: 'Volver',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '<?= BASE_PATH ?>/reportSalidas.php';
                            }
                        })
                    </script>
                <?php
                }
            }
        } else {
            $update_orden_noEquipo = $controller->update_orden_sinEquipo($id, $fecha, $tecnico, $asistente1, $asistente2, $tipoTrabajo, $cliente, $direccion, $telefono, $descripcion, $vehiculo);
            if ($update_orden_noEquipo == "success") {
                ?>
                <script>
                    Swal.fire({
                        title: '¡Felicidades!',
                        text: 'La orden fue editada satisfactoriamente',
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonText: 'Descargar PDF',
                        cancelButtonText: 'Cerrar',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.open('ajax/generar_pdf.php?orden=<?= urlencode($orden) ?>', '_blank');
                            window.location.href = '<?= BASE_PATH ?>/reportSalidas.php';
                        } else {
                            window.location.href = '<?= BASE_PATH ?>/reportSalidas.php';
                        }
                    });
                </script>
            <?php
            } else {
            ?>
                <script>
                    let errores = <?= json_encode(implode("\n", $errores)); ?>;
                    swal.fire({
                        title: 'Error',
                        text: 'No se pudo editar la orden.',
                        icon: 'error',
                        confirmButtonText: 'Volver',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '<?= BASE_PATH ?>/reportSalidas.php';
                        }
                    })
                </script>
<?php
            }
        }





        // $updateEquipo  = $controller->updateOrden($id, $orden, $fecha, $tecnico, $asistente1, $asistente2, $tipoTrabajo, $cliente, $direccion, $telefono, $descripcion, $equipos);



    } /*{
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
                    window.location.href = '<?= BASE_PATH ?>/reportSalidas.php';
                }
            })
        </script>
<?php
    }*/
}

if (isset($_POST['btnEliminar'])) {
    $id = $_POST['id'] ?? null;
    $instalacion = $_POST['reintegrar'] ?? null;

    $redirectUrl = BASE_PATH . '/reportSalidas.php';

    // Función para mostrar alerta y redireccionar (para evitar repetición)
    function showAlertAndRedirect($title, $text, $icon, $redirectUrl)
    {
        echo "<script>
            Swal.fire({
                title: " . json_encode($title) . ",
                text: " . json_encode($text) . ",
                icon: " . json_encode($icon) . ",
                confirmButtonText: 'Volver',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = " . json_encode($redirectUrl) . ";
                }
            });
        </script>";
    }

    // Validar ID
    if (empty($id)) {
        showAlertAndRedirect('Error', 'No se recibió el código de la orden a eliminar', 'error', $redirectUrl);
        exit;
    }

    // Si no es reintegro (instalacion vacío o 0)
    if (empty($instalacion) || $instalacion == 2) {
        $resultado = $controller->delete_equipos_orden_id($id);

        if ($resultado === 'success') {
            showAlertAndRedirect('¡Felicidades!', 'La orden y los equipos se eliminaron satisfactoriamente', 'success', $redirectUrl);
        } else {
            showAlertAndRedirect('Lo sentimos', 'No se pudo realizar la solicitud', 'error', $redirectUrl);
        }

        exit;
    }

    // Si es reintegro
    if ($instalacion == 1) {
        $resultado = $controller->update_equipos_orden_id_integrar($id);

        if ($resultado === 'success') {
            showAlertAndRedirect('¡Felicidades!', 'El equipo se reintegró al sistema y se eliminó la orden', 'success', $redirectUrl);
        } else {
            showAlertAndRedirect('Lo sentimos', 'No se pudo realizar la operación', 'error', $redirectUrl);
        }

        exit;
    }

    // Caso no contemplado o variable instalacion con otro valor
    showAlertAndRedirect('Error', 'Parámetro inválido', 'error', $redirectUrl);
}

?>

<!-- ! Main -->
<main class="main users chart-page" id="skip-target">
    <div class="container">

        <h2 class="main-title text-center">Visitas Técnicas</h2>

        <div class="row stat-cards">
            <div class="col-md-2 col-xl-3">
                <!-- El estado 4 indica que es para agregar -->
                <button class="nueva_visita" data-bs-toggle="modal" data-bs-target="#modalNuevaVisita" data-estado-agregar="4" style="border: none; background: none;">
                    <article class="stat-cards-item">
                        <div class="icono_nuevo">
                            <i data-feather="plus" style="color: white;"></i>
                        </div>
                        <div class="stat-cards-info">
                            <p class="stat-cards-info__num m-2">Visita Nueva</p>
                        </div>
                    </article>
                </button>
            </div>
        </div>

        <hr class="line mt-1 mb-2 pb-2">

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
                        <th>° Orden</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Trabajo</th>
                        <th>Técnico</th>
                        <th>Asistente</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (is_array($ordenesFiltradas)): ?>
                        <?php foreach ($ordenesFiltradas as $orden): ?>
                            <tr>

                                <td data-label="° Orden"><?= htmlspecialchars($orden['ord_codigo']) ?></td>
                                <td data-label="Cliente"><?= htmlspecialchars($orden['ord_cliente']) ?></td>
                                <td data-label="Fecha"><?= date('d/m', strtotime($orden['ord_fecha'])) ?></td>
                                <td data-label="Trabajo">
                                    <?php
                                    if ($orden['ord_tipoTrabajo'] == 1) {
                                        echo "Instalación";
                                    } elseif ($orden['ord_tipoTrabajo'] = 2) {
                                        echo "Mantenimiento";
                                    } elseif ($orden['ord_tipoTrabajo'] = 3) {
                                        echo "Revisión";
                                    }
                                    ?>
                                </td>
                                <td data-label="Técnico"><?= htmlspecialchars($orden['ord_tecnico']) ?></td>
                                <td data-label="Asistente">
                                    <div>
                                        <?php
                                        echo htmlspecialchars($orden['ord_asistente1']);
                                        if (!empty($orden['ord_asistente2'])) {
                                            echo '<br>' . htmlspecialchars($orden['ord_asistente2']);
                                        }
                                        ?>
                                    </div>
                                </td>


                                <td data-label="Acciones">
                                    <span class="p-relative">
                                        <button class="dropdown-btn transparent-btn" type="button" title="More info">
                                            <div class="sr-only">More info</div>
                                            <i data-feather="more-horizontal" aria-hidden="true"></i>
                                        </button>
                                        <ul class="users-item-dropdown dropdown pt-1">
                                            <li>
                                                <a class="editar_orden" href="#" data-bs-toggle="modal" data-bs-target="#modalEditar" data-estado-editar="5" data-editar-id="<?= $orden['ord_id'] ?>">Editar</a>
                                            </li>
                                            <li>
                                                <a class="ver_orden" href="#" data-orden="<?= urlencode($orden['ord_codigo']) ?>">Ver</a>
                                            </li>

                                            <li>
                                                <a class="eliminar_orden" href="#" data-bs-toggle="modal" data-bs-target="#modalEliminar" data-estado-eliminar="6" data-codigo-orden="<?= $orden['ord_codigo'] ?>" data-id="<?= $orden['ord_id'] ?>">Eliminar</a>
                                            </li>
                                        </ul>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No hay productos para mostrar.</td>
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

    <!-- Inicio Modal Visita Nueva -->
    <div class="modal fade" id="modalNuevaVisita" tabindex="-1" aria-labelledby="modalNuevaVisitaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">

                <div class="modal-header p-2">
                    <h6 class="modal-title" id="modalNuevaVisitaLabel"><b>Asignar una Visita Nueva</b></h6>
                    <button type="button" class="btn-close p-1 me-2 mt-1" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div id="formulario-visita">

                </div>
            </div>
        </div>
    </div>
    <!-- Fin Modal Visita Nueva -->

    <!-- Inicio Model Productos Desagrupados -->
    <div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h6 class="modal-title" id="modalNuevoProductoLabel"><b>Detalles del Equipo</b></h6>
                    <button type="button" class="btn-close p-1 me-2 mt-1" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="tabla-inventarios" id="tabla-inventarios">
                        <div class="detalles_equipo" id="detalles_equipo">

                        </div>
                    </div>

                </div>
                <div class="modal-footer py-1 px-2">
                    <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Volver</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Fin Model Productos Desagrupados -->

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

                <div class="modal-header p-2">
                    <h6 class="modal-title" id="modalEliminarLabel"><b>Eliminar Orden</b></h6>
                    <button type="button" class="btn-close p-1 me-2 mt-1" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div id="formulario-eliminar">

                </div>

            </div>
        </div>
    </div>
    <!-- Fin Modal Eliminar -->

    <!-- Modal de productos para cargar la orden de trabajo -->
    <div class="modal fade" id="modalProductos" tabindex="-1" aria-labelledby="modalProductosLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="tabla-productos-modal" id="tabla-productos-modal">

                    <div class="modal-header p-2">
                        <h6 class="modal-title" id="modalProductosLabel"><b>Seleccione el equipo</b></h6>
                        <button type="button" class="btn-close p-1 me-2 mt-1" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row mb-3">
                            <!-- Buscador al centro -->
                            <div class="col-md-2 text-center contenedor-busqueda" id="contenedor-busqueda"></div>


                        </div>

                        <table class="table table-bordered table-hover pt-3 mb-3" id="tablaProductos">
                            <thead class="table-light">
                                <tr>
                                    <th>Código</th>
                                    <th>Descripción</th>
                                    <th>Cantidad</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Productos cargados dinámicamente -->
                            </tbody>
                        </table>
                        <div class="row mt-3">
                            <div class="col-md-6 text-end contenedor-paginacion" id="contenedor-paginacion"></div>
                        </div>
                    </div>
                    <div class="modal-footer py-1 px-2">
                        <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de productos para cargar la orden de trabajo -->
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

<!-- Código JavaScript -->
<script>
    let tabla; // Variable global

    $(document).ready(function() {
        tabla = $('#tablaProductos').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
                paginate: {
                    previous: '<',
                    next: '>'
                }
            },
            dom: '<"wrapper"ftp>',
            pageLength: 10
        });

        tabla.buttons().container().appendTo('#contenedor-botones');
        $('#tablaProductos_filter').appendTo('#contenedor-busqueda');

        $('#tablaProductos_paginate').appendTo('#contenedor-paginacion');
    });
</script>

<!-- Petición Ajax Ver Orden -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.ver_orden').forEach(function(btn) {
            btn.addEventListener("click", function(e) {
                e.preventDefault(); // Previene comportamiento por defecto
                const orden = this.dataset.orden;

                // Abrir el PDF en una nueva pestaña
                window.open('ajax/generar_pdf.php?orden=' + orden, '_blank');
            });
        });
    });
</script>

<!-- Petición Ajax Productos Desagrupados -->
<script>
    document.querySelectorAll('.ver_detalles').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const codigo = this.getAttribute('data-codigo');
            const estado = this.getAttribute('data-estado');
            const contenedor = document.getElementById('detalles_equipo');

            contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

            fetch('ajax/tablas-invElectronicos.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'estado=' + encodeURIComponent(estado) + '&codigo=' + encodeURIComponent(codigo)
                })
                .then(res => res.text())
                .then(data => {
                    contenedor.innerHTML = data;

                    const tabla = $('#tabla-desagrupado').DataTable({
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
                })
                .catch(err => {
                    contenedor.innerHTML = '<div class="text-danger">Error al cargar el formulario.</div>';
                    console.error('Error AJAX:', err);
                });
        });
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<!-- Petición Ajax Nueva Visita -->
<script>
    document.querySelector('.nueva_visita').addEventListener('click', function() {
        const estado = this.getAttribute('data-estado-agregar');
        const contenedor = document.getElementById('formulario-visita');
        let inputActual = null;

        contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

        fetch('ajax/formularios-reportVisitas.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'estado=' + encodeURIComponent(estado)
            })
            .then(res => res.text())
            .then(data => {
                contenedor.innerHTML = data;
                agregarEquipo();

                // Activar CKEditor
                if (document.querySelector('#trabajo')) {
                    ClassicEditor
                        .create(document.querySelector('#trabajo'))
                        .catch(error => console.error(error));
                }

                if (document.querySelector('#fecha_sale')) {
                    flatpickr("#fecha_sale", {
                        dateFormat: "Y-m-d"
                    });
                }
            })
            .catch(err => {
                contenedor.innerHTML = '<div class="text-danger">Error al cargar el formulario.</div>';
                console.error('Error AJAX:', err);
            });
    });

    function agregarEquipo() {
        const contenedor = document.getElementById('equiposContainer');
        const nuevoEquipo = document.createElement('div');
        nuevoEquipo.className = 'equipo border rounded-3 p-3 mb-3 shadow-sm';

        nuevoEquipo.innerHTML = `
            <div class="row mb-2">
                <div class="col-md-3">
                    <label class="form-label">Código</label>
                    <input type="text" name="codigo[]" class="form-control codigo" readonly  onclick="abrirModalProductos(this)">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Descripción</label>
                    <input type="text" name="descripcion[]" class="form-control descripcion">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Cantidad</label>
                    <input type="number" name="cantidad[]" class="form-control cantidad" min="1" value="1">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tipo</label>
                    <select name="tipo_entrega[]" class="form-control tipo_entrega">
                        <option value="1">Instalación</option>
                        <option value="2">Respaldo</option>
                        <option value="3">Muestra</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger w-100" onclick="this.closest('.equipo').remove()">🗑️ Quitar</button>
                </div>
            </div>
        `;

        contenedor.appendChild(nuevoEquipo);
    }

    const productos = <?php echo json_encode($productos, JSON_UNESCAPED_UNICODE); ?>;



    function abrirModalProductos(input) {
        inputActual = input;
        inputActual.setAttribute('readonly', true); // Activar readonly al abrir
        llenarTablaProductos();
        const modal = new bootstrap.Modal(document.getElementById('modalProductos'));
        modal.show();
    }

    function llenarTablaProductos() {
        const esMovil = window.innerWidth < 768;

        tabla.clear(); // Limpia la tabla

        productos.forEach(producto => {
            if (esMovil) {
                const contenidoMovil = `
                <div class="border rounded p-2 mb-2">
                    <div><strong>Código:</strong> ${producto.elec_codigo}</div>
                    <div><strong>Descripción:</strong> ${producto.elec_detalle}</div>
                     <div><strong>Cantidad:</strong> ${producto.elec_stock}</div>
                    <div class="mt-2">
                        <button class="btn btn-sm btn-primary w-100" onclick="seleccionarProducto('${producto.elec_codigo}', '${producto.elec_detalle}', '${producto.elec_stock}')">Seleccionar</button>
                    </div>
                </div>
            `;
                tabla.row.add([contenidoMovil, '', '']); // Añade en una sola celda
            } else {
                tabla.row.add([
                    producto.elec_codigo,
                    producto.elec_detalle,
                    producto.elec_stock,
                    `<button class="btn btn-sm btn-primary" onclick="seleccionarProducto('${producto.elec_codigo}', '${producto.elec_detalle}', '${producto.elec_stock}')">Seleccionar</button>`
                ]);
            }
        });

        tabla.draw(); // Redibuja la tabla
    }


    function seleccionarProducto(codigo, descripcion, stock) {
        if (inputActual) {
            inputActual.value = codigo;
            const row = inputActual.closest('.row');
            row.querySelector('.descripcion').value = descripcion;
            const cantidadInput = row.querySelector('.cantidad');
            cantidadInput.setAttribute('max', stock); // ← establece el máximo permitido
            cantidadInput.setAttribute('title', 'Máximo permitido: ' + stock); // opcional, por accesibilidad

            // Si la cantidad actual es mayor al stock, la ajusta
            if (parseInt(cantidadInput.value) > stock) {
                cantidadInput.value = stock;
            }

            // Cierra el modal
            bootstrap.Modal.getInstance(document.getElementById('modalProductos')).hide();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const modalElement = document.getElementById('modalProductos');
        modalElement.addEventListener('hidden.bs.modal', function() {
            if (inputActual && !inputActual.value) {
                inputActual.removeAttribute('readonly');
                inputActual.focus();
            }
        });
    });

    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('cantidad')) {
            const max = parseInt(e.target.getAttribute('max'));
            const val = parseInt(e.target.value);
            if (val > max) {
                e.target.value = max;
            }
        }
    });
</script>

<!-- Petición Ajax Editar Producto -->
<script>
    // Productos
    const ordenes = <?= json_encode($ordenesFiltradas); ?>;
    const equipos = <?= json_encode($equiposGeneral); ?>;

    // Petición a Editar Producto
    document.addEventListener('click', function(e) {
        if (e.target.closest('.editar_orden')) {
            const btn = e.target.closest('.editar_orden');
            const estado = btn.getAttribute('data-estado-editar');
            const editarID = btn.getAttribute('data-editar-id');
            const contenedor = document.getElementById('formulario-editar');

            contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

            fetch('ajax/formularios-reportVisitas.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'estado=' + encodeURIComponent(estado)
                })
                .then(res => res.text())
                .then(data => {
                    contenedor.innerHTML = data;

                    const orden = ordenes.find(p => p.ord_id == editarID);

                    if (!orden) {
                        console.error('Producto no encontrado:', editarID);
                        return;
                    }

                    funcionesFormulario_Editar(orden, editarID);

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

    function funcionesFormulario_Editar(orden, editarID) {
        // Llenar los inputs con la info de la orden
        document.getElementById('id').value = orden.ord_id;
        document.getElementById('num_orden').value = orden.ord_codigo;
        document.getElementById('fecha_sale').value = orden.ord_fecha;
        document.getElementById('vehiculo').value = orden.ord_vehiculo_id;
        document.getElementById('tecnico').value = orden.ord_tecnico;
        document.getElementById('asistente_1').value = orden.ord_asistente1;
        if (orden.ord_asistente2 != '') {
            document.getElementById('asistente_2').value = orden.ord_asistente2;
        }
        document.getElementById('tipo_trabajo').value = orden.ord_tipoTrabajo;
        document.getElementById('cliente').value = orden.ord_cliente;
        document.getElementById('direccion').value = orden.ord_direccion;
        document.getElementById('telefono').value = orden.ord_telefono;

        ClassicEditor
            .create(document.querySelector('#trabajo'))
            .then(editor => {
                editor.setData(orden.ord_descripcion || '');
                editorDetalleEditar = editor;
            });

        // Agregar dinámicamente los equipos de la orden
        const equiposOrden = equipos.filter(eq => Number(eq.orden_id) === Number(editarID));
        const contenedor = document.getElementById('equiposContainer');
        contenedor.innerHTML = ''; // Limpiar contenedor actual


        equiposOrden.forEach(eq => {
            const nuevoEquipo = document.createElement('div');
            nuevoEquipo.className = 'equipo border rounded-3 p-3 mb-3 shadow-sm';

            nuevoEquipo.innerHTML = `
            <div class="row mb-2">
                <div class="col-md-3">
                    <label class="form-label">Código</label>
                    <input type="text" name="codigo[]" class="form-control codigo" value="${eq.codigo}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Descripción</label>
                    <input type="text" name="descripcion[]" class="form-control descripcion" value="${eq.eqDescipt}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Cantidad</label>
                    <input type="number" name="cantidad[]" class="form-control cantidad" min="1" value="${eq.cantidad}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tipo</label>
                    <select name="tipo_entrega[]" class="form-control tipo_entrega">
                        <option value="1" ${eq.tipo_equipo == 1 ? 'selected' : ''}>Instalación</option>
                        <option value="2" ${eq.tipo_equipo == 2 ? 'selected' : ''}>Respaldo</option>
                        <option value="3" ${eq.tipo_equipo == 3 ? 'selected' : ''}>Muestra</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger w-100" onclick="this.closest('.equipo').remove()">🗑️ Quitar</button>
                </div>
            </div>
        `;

            contenedor.appendChild(nuevoEquipo);
        });
    }
</script>

<!-- Petición Ajax Eliminar Producto -->
<script>
    document.addEventListener('click', function(e) {
        // Verificamos si el elemento clickeado es un botón de eliminación
        if (e.target.closest('.eliminar_producto')) {
            const btn = e.target.closest('.eliminar_producto'); // El botón de eliminación que fue clickeado
            const estado = btn.getAttribute('data-estado-eliminar');
            const id = btn.getAttribute('data-id');
            const codigo = btn.getAttribute('data-codigo');
            const contenedor = document.getElementById('formularioEliminar');



            // Mostrar mensaje de carga
            contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

            // Hacer petición AJAX para cargar el formulario de eliminación
            fetch('ajax/formularios-invElectonicos.php', {
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
                    funcioneFormulario_eliminar(id, codigo);

                    // Mostrar el modal de eliminación
                    const modalEliminarElement = document.getElementById('modalEliminar');
                    const modal = bootstrap.Modal.getOrCreateInstance(modalEliminarElement);
                    modal.show();
                })
                .catch(err => {
                    contenedor.innerHTML = '<div class="text-danger">Error al cargar el formulario de eliminación.</div>';
                    console.error('Error AJAX:', err);
                });
        }
    });

    function funcioneFormulario_eliminar(id, codigo) {
        // Aquí puedes gestionar el formulario de eliminación con los valores que recibas
        console.log(id, '/', codigo);
        document.getElementById('eliminarId').value = id;
        document.getElementById('codigoEliminarTexto').textContent = codigo;
    }
</script>

<!-- Petición Ajax Eliminar Orden -->
<script>
    document.addEventListener('click', function(e) {
        // Verificamos si el elemento clickeado es un botón de eliminación
        if (e.target.closest('.eliminar_orden')) {
            const btn = e.target.closest('.eliminar_orden'); // El botón de eliminación clickeado
            const estado = btn.getAttribute('data-estado-eliminar');
            const id = btn.getAttribute('data-id');
            const codigo = btn.getAttribute('data-codigo-orden');

            const contenedor = document.getElementById('formulario-eliminar');

            // Mostrar mensaje de carga
            contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

            // Hacer petición AJAX para cargar el formulario de eliminación
            fetch('ajax/formularios-reportVisitas.php', {
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

                    funcioneFormulario_estado(id, codigo);

                    // Mostrar el modal de eliminación
                    const modalEstadoElement = document.getElementById('modalEliminar');
                    const modal = bootstrap.Modal.getOrCreateInstance(modalEstadoElement);
                    modal.show();
                })
                .catch(err => {
                    contenedor.innerHTML = '<div class="text-danger">Error al cargar el formulario.</div>';
                    console.error('Error AJAX:', err);
                });
        }
    });

    function funcioneFormulario_estado(id, codigo) {
        document.getElementById('eliminarId').value = id;
        document.getElementById('ordenEliminar').textContent = codigo;

        const reintegrarContainer = document.getElementById('reintegrarContainer');
        reintegrarContainer.classList.add('d-none'); // Ocultar por defecto

        // Petición para validar si tiene equipos con instalación
        fetch('ajax/validar_instalacion.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'orden_id=' + encodeURIComponent(id)
            })
            .then(res => res.text())
            .then(data => {
                if (data.trim() === 'instalacion') {
                    reintegrarContainer.classList.remove('d-none');

                    // Opcional: seleccionar automáticamente "Sí"
                    setTimeout(() => {
                        document.getElementById('reintegrarSi').checked = true;
                    }, 100);
                }
            });
    }

    // Delegación de evento submit para formulario cargado dinámicamente
    document.addEventListener('submit', function(e) {
        if (e.target && e.target.id === 'formVerificarPass') {
            const reintegrarContainer = document.getElementById('reintegrarContainer');
            const alerta = document.getElementById('alertaReintegrar');

            if (!reintegrarContainer.classList.contains('d-none')) {
                const seleccionado = document.querySelector('input[name="reintegrar"]:checked');
                if (!seleccionado) {
                    e.preventDefault();
                    alerta.classList.remove('d-none');
                } else {
                    alerta.classList.add('d-none');
                }
            }
        }
    });
</script>


<?php
// Incluir el footer
require_once 'layout/footer.php';
?>
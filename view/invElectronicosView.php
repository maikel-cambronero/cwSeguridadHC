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

function limpiarNumero($valor)
{
    // Reemplaza cualquier tipo de espacio (normal o duro) y cambia coma por punto decimal
    $valor = preg_replace('/[^\d,\.]/u', '', $valor); // Elimina todo lo que no sea dígito, coma o punto
    $valor = str_replace(',', '.', $valor); // Reemplaza coma por punto
    return floatval($valor);
}

// Incluir el header
require_once 'layout/header.php';
require_once '../controllers/electronicoConroller.php';

$estado = isset($_GET['estado']) ? intval($_GET['estado']) : 1; // Por defecto 1 = Óptimo

$controller = new electronicoConroller();
$productos = $controller->getElectronicos_Agrupados($estado);
$lista_equipos = $controller->getElectronicos_General();



if (isset($_POST['nuevo'])) {
    $errores = [];
    $camposValidar = [
        'detalle'      => 'Detalle',
        'codigo'       => 'Código',
        'stock'        => 'Stock',
        'limite'       => 'Límite Mínimo',
        'buffer'       => 'Buffer',
        'marca'        => 'Marca',
        'proveedor'    => 'Proveedor',
        'consecutivo'  => 'Consecutivo',
        'compra'       => 'Precio de Compra',
        'utilidad'     => 'Utilidad (%)',
        'venta'        => 'Precio de Venta'
    ];

    foreach ($camposValidar as $campo => $nombreCampo) {
        if (empty($_POST[$campo])) {
            $errores[] = $nombreCampo;
        }
    }

    if (empty($errores)) {
        $detalle      = $_POST['detalle'];
        $codigo       = $_POST['codigo'];
        $stock        = $_POST['stock'];
        $limite       = $_POST['limite'];
        $buffer       = $_POST['buffer'];
        $marca        = $_POST['marca'];
        $categoria    = $_POST['categoria'];
        $subcategoria = $_POST['subcategoria'];
        $proveedor    = $_POST['proveedor'];
        $consecutivo  = $_POST['consecutivo'];
        $compra = limpiarNumero($_POST['compra']);
        $utilidad = limpiarNumero($_POST['utilidad']);
        $venta = limpiarNumero($_POST['venta']);


        $addEquipo = $controller->addEquipo($detalle, $codigo, $stock, $limite, $buffer, $marca, $categoria, $subcategoria, $proveedor, $consecutivo, $compra, $utilidad, $venta);


        if ($addEquipo == 'success') {
    ?>
            <script>
                let errores = <?= json_encode(implode("\n", $errores)); ?>;
                swal.fire({
                    title: 'Felecicdades',
                    text: 'El producto fue registraso satisfactoriamente',
                    icon: 'success',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/invElectronicos.php';
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
                    text: 'No se pudo registrar el producto.',
                    icon: 'error',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/invElectronicos.php';
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
                    window.location.href = '<?= BASE_PATH ?>/invElectronicos.php';
                }
            })
        </script>
        <?php
    }
}

if (isset($_POST['editar'])) {

    $errores = [];
    $camposValidar = [
        'detalle'      => 'Detalle',
        'codigo'       => 'Código',
        'stock'        => 'Stock',
        'limite'       => 'Límite Mínimo',
        'buffer'       => 'Buffer',
        'marca'        => 'Marca',
        'proveedor'    => 'Proveedor',
        'consecutivo'  => 'Consecutivo',
        'compra'       => 'Precio de Compra',
        'utilidad'     => 'Utilidad (%)',
        'venta'        => 'Precio de Venta'
    ];

    foreach ($camposValidar as $campo => $nombreCampo) {
        if (empty($_POST[$campo])) {
            $errores[] = $nombreCampo;
        }
    }

    if (empty($errores)) {

        $id           = $_POST['id'];
        $detalle      = $_POST['detalle'];
        $codigo       = $_POST['codigo'];
        $stock        = $_POST['stock'];
        $limite       = $_POST['limite'];
        $buffer       = $_POST['buffer'];
        $marca        = $_POST['marca'];
        $categoria    = $_POST['categoria'];
        $subcategoria = $_POST['subcategoria'];
        $proveedor    = $_POST['proveedor'];
        $consecutivo  = $_POST['consecutivo'];
        $compra = limpiarNumero($_POST['compra']);
        $utilidad = limpiarNumero($_POST['utilidad']);
        $venta = limpiarNumero($_POST['venta']);


        $update = $controller->updateEquipo($id, $detalle, $codigo, $stock, $limite, $buffer, $marca, $categoria, $subcategoria, $proveedor, $consecutivo, $compra, $utilidad, $venta);

        if ($update == 'success') {
        ?>
            <script>
                let errores = <?= json_encode(implode("\n", $errores)); ?>;
                swal.fire({
                    title: 'Felecicdades',
                    text: 'El equipo fue editado satisfactoriamente',
                    icon: 'success',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/invElectronicos.php';
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
                    text: 'No se pudo editar el equipo.',
                    icon: 'error',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/invElectronicos.php';
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
                    window.location.href = '<?= BASE_PATH ?>/invElectronicos.php';
                }
            })
        </script>
    <?php
    }
}

if (isset($_POST['eliminar'])) {
    $id = $_POST['id'];

    if (empty($id)) {
    ?>
        <script>
            swal.fire({
                title: 'Error',
                text: 'No se recibió el código del producto a eliminar',
                icon: 'error',
                confirmButtonText: 'Volver',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= BASE_PATH ?>/invElectronicos.php';
                }
            })
        </script>
        <?php
    } else {
        $eliminarProducto = $controller->deleteEquipo($id);

        if ($eliminarProducto == 'success') {
        ?>
            <script>
                swal.fire({
                    title: 'Felecicdades',
                    text: 'El producto fue eliminado satisfactoriamente',
                    icon: 'success',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/invElectronicos.php';
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
                        window.location.href = '<?= BASE_PATH ?>/invElectronicos.php';
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

        <?php if ($_SESSION['nivel_acceso'] != 3): ?>
            <div class="row stat-cards">
                <div class="col-md-2 col-xl-3">
                    <!-- El estado 4 indica que es para agregar -->
                    <button class="nuevo_producto" data-bs-toggle="modal" data-bs-target="#modalNuevoProducto" data-estado-agregar="4" style="border: none; background: none;">
                        <article class="stat-cards-item">
                            <div class="icono_nuevo">
                                <i data-feather="plus" style="color: white;"></i>
                            </div>
                            <div class="stat-cards-info">
                                <p class="stat-cards-info__num m-2">Producto Nuevo</p>
                            </div>
                        </article>
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <hr class="line mt-1 mb-2 pb-2">

        <!-- Tabla para los productos -->
        <!-- Botones de filtro -->
        <div id="botones-filtro" class="btn-group" role="group" aria-label="Basic mixed styles example">
            <a href="<?= VIEW_PATH ?>/invElectronicosView.php?estado=1" class="inv_suficiente btn btn-success" id="inv_suficiente">Óptimo</a>
            <a href="<?= VIEW_PATH ?>/invElectronicosView.php?estado=2" class="inv_advertencia btn btn-warning" id="inv_advertencia">Advertencia</a>
            <a href="<?= VIEW_PATH ?>/invElectronicosView.php?estado=3" class="inv_critico btn btn-danger" id="inv_critico">Crítico</a>
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
                        <th>Equipo</th>
                        <th>Stock</th>
                        <?php if ($_SESSION['nivel_acceso'] != 3): ?>
                            <th>Límite</th>
                            <th>Buffer</th>
                            <th>Compra</th>
                        <?php endif; ?>
                        <th>Venta</th>
                        <th>Categoria</th>
                        <th>Subcategoria</th>
                        <?php if ($_SESSION['nivel_acceso'] != 3): ?>
                            <th>Acciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (is_array($productos)): ?>
                        <?php foreach ($productos as $producto): ?>
                            <tr>
                                <td data-label="Equipo">
                                    <div>
                                        <span style="color: #007bff; font-weight: bold;"><?= $producto['elec_codigo'] ?></span><br>
                                        <span><?= $producto['elec_detalle'] ?></span>
                                    </div>
                                </td>
                                <td data-label="Stock"><?= htmlspecialchars($producto['elec_stock']) ?></td>
                                <?php if ($_SESSION['nivel_acceso'] != 3): ?>
                                    <td data-label="Límite"><?= htmlspecialchars($producto['elec_cantMin']) ?></td>
                                    <td data-label="Buffer"><?= htmlspecialchars($producto['elec_buffer']) ?></td>
                                    <td data-label="Compra">₡<?= number_format($producto['elec_precio'], 2, ',', '.') ?></td>
                                <?php endif; ?>
                                <td data-label="Venta">₡<?= number_format($producto['elec_precioTotal'], 2, ',', '.') ?></td>
                                <td data-label="Categoria"><?= htmlspecialchars($producto['catg_detalle']) ?></td>
                                <td data-label="Subcategoria"><?= htmlspecialchars($producto['scat_detalle']) ?></td>
                                <?php if ($_SESSION['nivel_acceso'] != 3): ?>
                                    <td data-label="Acciones">
                                        <button class="ver_detalles btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalDetalles" data-estado="24" data-codigo="<?= $producto['elec_codigo'] ?>">
                                            <i class="fas fa-search"></i> <!-- Ícono de lupa -->
                                        </button>
                                    </td>
                                <?php endif; ?>
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

    <!-- Inicio Modal Producto Nuevo -->
    <div class="modal fade" id="modalNuevoProducto" tabindex="-1" aria-labelledby="modalNuevoProductoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">

                <div class="modal-header p-2">
                    <h6 class="modal-title" id="modalNuevoProductoLabel"><b>Agregar Nuevo Producto</b></h6>
                    <button type="button" class="btn-close p-1 me-2 mt-1" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div id="formulario-nuevo">

                </div>
            </div>
        </div>
    </div>
    <!-- Fin Modal Producto Nuevo -->

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
                <div class="modal-header py-1 px-2">
                    <h5 class="modal-title d-flex align-items-center">
                        <i class="me-2" data-feather="alert-triangle"></i> ¿Estás seguro de eliminar?
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

<!-- Petición Ajax para la tabla inv. Suficiente -->


<!-- Petición Ajax para la tabla inv. Advertencia 
<script>
    // Configurar los botones para hacer la petición AJAX
    document.querySelector('.inv_advertencia').addEventListener('click', function() {
        const estado = this.getAttribute('data-estado-adv');
        const contenedor = document.getElementById('tabla-inventarios');

        //Muestra "Cargando..." mientras obtiene el contenido
        contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

        fetch('ajax/tablas-invElectronicos.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'estado=' + encodeURIComponent(estado)
            })
            .then(res => res.text())
            .then(data => {
                contenedor.innerHTML = data;

                const tabla = $('#tablaAdvertencia').DataTable({
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
                $('#tablaAdvertencia_filter').appendTo('#contenedor-busqueda');
                $('#tablaAdvertencia_info').appendTo('#contenedor-info');
                $('#tablaAdvertencia_paginate').appendTo('#contenedor-paginacion');

            })
            .catch(err => {
                contenedor.innerHTML = '<div class="text-danger">Error al cargar la tabla.</div>';
                console.error('Error AJAX:', err);
            });
    });
</script>
-->

<!-- Petición Ajax para la tabla inv. Crítico 
<script>
    // Configurar los botones para hacer la petición AJAX
    document.querySelector('.inv_critico').addEventListener('click', function() {
        const estado = this.getAttribute('data-estado-crt');
        const contenedor = document.getElementById('tabla-inventarios');

        //Muestra "Cargando..." mientras obtiene el contenido
        contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

        fetch('ajax/tablas-invElectronicos.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'estado=' + encodeURIComponent(estado)
            })
            .then(res => res.text())
            .then(data => {
                contenedor.innerHTML = data;

                const tabla = $('#tablaCritico').DataTable({
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
                contenedor.innerHTML = '<div class="text-danger">Error al cargar la tabla.</div>';
                console.error('Error AJAX:', err);
            });
    });
</script>
-->

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


<!-- Petición Ajax Nuevo Producto -->
<script>
    // Petición a Formulario Nuevo Producto.
    document.querySelector('.nuevo_producto').addEventListener('click', function() {
        const estado = this.getAttribute('data-estado-agregar');
        const contenedor = document.getElementById('formulario-nuevo');

        //Muestra "Cargando..." mientras obtiene el contenido
        contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

        fetch('ajax/formularios-invElectonicos.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'estado=' + encodeURIComponent(estado)
            })
            .then(res => res.text())
            .then(data => {
                contenedor.innerHTML = data;
                funcionesFormulario_Agregar();
            })
            .catch(err => {
                contenedor.innerHTML = '<div class="text-danger">Error al cargar el formulario.</div>';
                console.error('Error AJAX:', err);
            });
    });

    function funcionesFormulario_Agregar() {
        const compraInput = document.getElementById('compra');
        const utilidadInput = document.getElementById('utilidad');
        const ventaInput = document.getElementById('venta');

        function formatNumber(value) {
            return new Intl.NumberFormat('fr-FR').format(value);
        }

        function parseNumber(value) {
            return parseFloat(value.replace(/\s/g, '').replace(',', '.'));
        }

        function calcularPrecios() {
            const compra = parseNumber(compraInput.value);
            const porcentaje = parseNumber(utilidadInput.value);
            const utilidad = 1 + (porcentaje / 100);
            const venta = compra * utilidad;

            if (!isNaN(venta)) {
                ventaInput.value = formatNumber(venta.toFixed(2));
            }
        }

        // Recalcular cuando se edita compra o utilidad
        compraInput?.addEventListener('input', calcularPrecios);
        utilidadInput?.addEventListener('input', calcularPrecios);

        // Formatear todos al salir del input
        [compraInput, utilidadInput, ventaInput].forEach(input => {
            input?.addEventListener('blur', () => {
                const val = parseNumber(input.value);
                input.value = isNaN(val) ? '' : formatNumber(val.toFixed(2));
            });
        });

        calcularPrecios();
    }
</script>

<!-- Petición Ajax Editar Producto -->
<script>
    // Productos
    const productos = <?= json_encode($lista_equipos); ?>;
    console.log(productos);

    // Petición a Editar Producto
    document.addEventListener('click', function(e) {
        if (e.target.closest('.editar_producto')) {
            const btn = e.target.closest('.editar_producto');
            const estado = btn.getAttribute('data-estado-editar');
            const productoID = btn.getAttribute('data-editar-id');
            const contenedor = document.getElementById('formulario-editar');

            console.log(productoID);

            contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

            fetch('ajax/formularios-invElectonicos.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'estado=' + encodeURIComponent(estado)
                })
                .then(res => res.text())
                .then(data => {
                    contenedor.innerHTML = data;

                    const producto = productos.find(p => p.elec_id == productoID);
                    console.log(producto);
                    if (!producto) {
                        console.error('Producto no encontrado:', productoID);
                        return;
                    }

                    funcionesFormulario_Editar(producto);

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
        const compraInput = document.getElementById('compra');
        const utilidadInput = document.getElementById('utilidad');
        const ventaInput = document.getElementById('venta');

        function formatNumber(value) {
            return new Intl.NumberFormat('fr-FR').format(value);
        }

        function parseNumber(value) {
            return parseFloat(value.replace(/\s/g, '').replace(',', '.'));
        }

        function calcularPrecios() {
            const compra = parseNumber(compraInput.value);
            const porcentaje = parseNumber(utilidadInput.value);
            const utilidad = 1 + (porcentaje / 100);
            const venta = compra * utilidad;

            if (!isNaN(venta)) {
                ventaInput.value = formatNumber(venta.toFixed(2));
            }
        }

        // Recalcular cuando se edita compra o utilidad
        compraInput?.addEventListener('input', calcularPrecios);
        utilidadInput?.addEventListener('input', calcularPrecios);

        // Formatear todos al salir del input
        [compraInput, utilidadInput, ventaInput].forEach(input => {
            input?.addEventListener('blur', () => {
                const val = parseNumber(input.value);
                input.value = isNaN(val) ? '' : formatNumber(val.toFixed(2));
            });
        });

        // Rellenar campos
        document.getElementById('id').value = producto.elec_id;
        document.getElementById('detalle').value = producto.elec_detalle;
        document.getElementById('codigo').value = producto.elec_codigo;
        document.getElementById('stock').value = producto.elec_stok;
        document.getElementById('limite').value = producto.elec_cantMin;
        document.getElementById('buffer').value = producto.elec_buffer;
        document.getElementById('marca').value = producto.elec_marca;
        document.getElementById('categoria').value = producto.elec_catg_id;
        document.getElementById('subcategoria').value = producto.elec_scat_id;
        document.getElementById('proveedor').value = producto.elec_prov_id;
        document.getElementById('consecutivo').value = producto.elec_fact_consecutivo;

        compraInput.value = formatNumber(producto.elec_precio_prov);
        utilidadInput.value = formatNumber(producto.elec_utilidad);
        ventaInput.value = formatNumber(producto.elec_total);

        calcularPrecios();
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

            console.log(codigo);

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


<?php
// Incluir el footer
require_once 'layout/footer.php';
?>
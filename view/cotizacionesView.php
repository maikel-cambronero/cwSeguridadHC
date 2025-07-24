<?php
require_once '../routes/rutas.php';
session_start();

// Verificación de sesión
if (!isset($_SESSION['usuario'])) {
    header('Location: ' . BASE_PATH . '/index.php');
    exit;
}

// Verificación de nivel de acceso
if (($_SESSION['nivel_acceso'] != 1 || $_SESSION['nivel_acceso'] == 4 || $_SESSION['nivel_acceso'] == 5)) {
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
require_once '../controllers/cotizacionController.php';

$controller = new cotizacionesController();

$productos = $controller->getProductos();
$cotizaciones = $controller->getCotizaciones();
$equipos_coti = $controller->get_equiposCoti();



function formatoFecha($fecha)
{
    if (empty($fecha)) return null;
    $fechaObj = DateTime::createFromFormat('d/m/Y', $fecha);
    return $fechaObj ? $fechaObj->format('Y-m-d') : null;
}

if (isset($_POST['nueva_coti'])) {
    $errores = [];
    $camposValidar = [
        'num_coti'      => '# Cotización',
        'fecha_emite'       => 'Fecha 1',
        'fecha_valida'        => 'Fecha 2',
        'Vendor'       => 'Vendedor',
        'cliente'       => 'Cliente',
        'telefono'        => 'Teléfono'
    ];

    foreach ($camposValidar as $campo => $nombreCampo) {
        if (empty($_POST[$campo])) {
            $errores[] = $nombreCampo;
        }
    }

    if (empty($errores)) {
        $cotizacion = $_POST['num_coti'];
        $dateEmite = $_POST['fecha_emite'];
        $dateValida = $_POST['fecha_valida'];
        $saler = $_POST['Vendor'];
        $cliente = $_POST['cliente'];
        $tell = $_POST['telefono'];

        $subtotal_general = $_POST['subtotal_general'];
        $iva_general = $_POST['iva_general'];
        $descuento_general = $_POST['descuento_general'];
        $total_general = $_POST['total_general'];

        if ($iva_general == "") {
            $iva_general = 0;
        }
        if ($descuento_general == "") {
            $descuento_general = 0;
        }

        $equipos = [];

        if (isset($_POST['descripcion'])) {
            for ($i = 0; $i < count($_POST['descripcion']); $i++) {
                if ($_POST['iva'][$i] == "") {
                    $_POST['iva'][$i] = 0;
                }
                if ($_POST['descuento'][$i] == "") {
                    $_POST['descuento'][$i] = 0;
                }
                if ($_POST['iva_hidden'][$i] == "") {
                    $_POST['iva_hidden'][$i] = 0;
                }
                if ($_POST['descuento_hidden'][$i] == "") {
                    $_POST['descuento_hidden'][$i] = 0;
                }
                $equipos[] = [
                    'descripcion' => $_POST['descripcion'][$i],
                    'cantidad' => $_POST['cantidad'][$i],
                    'precio' => $_POST['precio'][$i],
                    'iva' => $_POST['iva'][$i],
                    'descuento' => $_POST['descuento'][$i],
                    'subtotal_hidden' => $_POST['subtotal_hidden'][$i],
                    'iva_hidden' => $_POST['iva_hidden'][$i],
                    'descuento_hidden' => $_POST['descuento_hidden'][$i],
                    'total_hidden' => $_POST['total_hidden'][$i]
                ];
            }
        }

        $generaCoti = $controller->addCoti($cotizacion, $dateEmite, $dateValida, $saler, $cliente, $tell, $subtotal_general, $iva_general, $descuento_general, $total_general, $equipos);

        if ($generaCoti == 'success') {
    ?>
            <script>
                Swal.fire({
                    title: '¡Felicidades!',
                    text: 'La cotización fue registrada satisfactoriamente',
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: 'Descargar PDF',
                    cancelButtonText: 'Cerrar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.open('ajax/generar_pdf_cotizacion.php?coti=<?= urlencode($cotizacion) ?>', '_blank');
                        window.location.href = '<?= BASE_PATH ?>/cotizaciones.php';
                    } else {
                        window.location.href = '<?= BASE_PATH ?>/cotizaciones.php';
                    }
                });
            </script>
        <?php
        } else {
        ?>
            <script>
                Swal.fire({
                    title: 'Lo Sentimos',
                    text: 'No se logró procesar la solicitud',
                    icon: 'error',
                    showCancelButton: true,
                    cancelButtonText: 'Cerrar',
                }).then((result) => {


                    window.location.href = '<?= BASE_PATH ?>/cotizaciones.php';

                });
            </script>
        <?php
        }
    }
}

if (isset($_POST['btnEliminar'])) {
    $id = $_POST['id'];
    $eliminaCoti = $controller->deleteCoti($id);

    if ($eliminaCoti == "success") {
        ?>
        <script>
            Swal.fire({
                title: '¡Felicidades!',
                text: 'La cotización fue eliminada satisfactoriamente',
                icon: 'success',
                showCancelButton: true,
                cancelButtonText: 'Cerrar',
            }).then((result) => {
                window.location.href = '<?= BASE_PATH ?>/cotizaciones.php';
            });
        </script>
    <?php
    } else {
    ?>
        <script>
            Swal.fire({
                title: 'Lo Sentimos',
                text: 'No se logró procesar la solicitud',
                icon: 'error',
                showCancelButton: true,
                cancelButtonText: 'Cerrar',
            }).then((result) => {


                window.location.href = '<?= BASE_PATH ?>/cotizaciones.php';

            });
        </script>
<?php
    }
}



?>

<!-- ! Main -->
<main class="main users chart-page" id="skip-target">
    <div class="container">

        <h2 class="main-title text-center">Cotizaciones</h2>

        <div class="row stat-cards">
            <div class="col-md-2 col-xl-3">
                <!-- El estado 4 indica que es para agregar -->
                <button class="coti_nueva" data-bs-toggle="modal" data-bs-target="#modalNuevaCoti" data-estado-agregar="4" style="border: none; background: none;">
                    <article class="stat-cards-item">
                        <div class="icono_nuevo">
                            <i data-feather="plus" style="color: white;"></i>
                        </div>
                        <div class="stat-cards-info">
                            <p class="stat-cards-info__num m-2">Nueva Cotización</p>
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
                        <th>Cotización</th>
                        <th>Asesor</th>
                        <th>Cliente</th>
                        <th>Subtotal</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (is_array($cotizaciones)): ?>
                        <?php foreach ($cotizaciones as $coti): ?>
                            <tr>
                                <td data-label="Cotización"><?= $coti['cot_codigo'] ?></td>
                                <td data-label="Asesor"><?= $coti['cot_vendor'] ?></td>
                                <td data-label="Cliente"><?= $coti['cot_cliente'] ?></td>
                                <td data-label="Subtotal"><?= number_format($coti['cot_subtotal'], 2, ',', ' ') ?></td>
                                <td data-label="Total"><?= number_format($coti['cot_total'], 2, ',', ' ') ?></td>
                                <td data-label="Acciones">
                                    <span class="p-relative">
                                        <button class="dropdown-btn transparent-btn" type="button" title="More info">
                                            <div class="sr-only">More info</div>
                                            <i data-feather="more-horizontal" aria-hidden="true"></i>
                                        </button>
                                        <ul class="users-item-dropdown dropdown pt-1">
                                            <li>
                                                <a class="cot_ver" href="ajax/generar_pdf_cotizacion.php?coti=<?= $coti['cot_codigo'] ?>" target="_blank">Ver</a>
                                            </li>
                                            <li>
                                                <a class="coti_editar" href="#" data-bs-toggle="modal" data-bs-target="#modalEditar" data-estado-editar="5" data-id="<?= $coti['cot_id'] ?>">Editar</a>
                                            </li>
                                            <li>
                                                <a class="cot_eliminar" href="#" data-id="<?= $coti['cot_id'] ?>" data-codigo="<?= $coti['cot_codigo'] ?>" data-eliminar="6" data-bs-toggle="modal" data-bs-target="#modalEliminar">Eliminar</a>
                                            </li>
                                        </ul>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No hay usuarios para mostrar.</td>
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
    <div class="modal fade" id="modalNuevaCoti" tabindex="-1" aria-labelledby="modalNuevaCotiLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen">
            <div class="modal-content">

                <div class="modal-header p-2">
                    <h6 class="modal-title" id="modalNuevaCotiLabel"><b>Realizar cotización</b></h6>
                    <button type="button" class="btn-close p-1 me-2 mt-1" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div id="formulario-nuevo" style="background-color: white;">

                </div>
            </div>
        </div>
    </div>
    <!-- Fin Modal Nuevo -->

    <!-- Modal de productos -->
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
                                    <th>Precio</th>
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
    <!-- Modal de productos -->

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

<!-- Petición Ajax Nuevo -->
<script>
    // Variable global para el input activo en el modal
    let inputActual = null;

    // Productos pasados desde PHP
    const productos = <?php echo json_encode($productos, JSON_UNESCAPED_UNICODE); ?>;

    // Nivel de Acceso desde PHP
    const acceso = <?php echo json_encode($_SESSION['nivel_acceso']); ?>;

    let desc_max;

    switch (acceso) {
        case 1:
        case 4:
            desc_max = 20;
            break;
        default:
            desc_max = 5;
            break;
    }

    // Evento para cargar el formulario y agregar el primer equipo
    document.querySelector('.coti_nueva').addEventListener('click', function() {
        const estado = this.getAttribute('data-estado-agregar');
        const contenedor = document.getElementById('formulario-nuevo');

        contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

        fetch('ajax/formularios-cotizacion.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'estado=' + encodeURIComponent(estado)
            })
            .then(res => res.text())
            .then(data => {
                contenedor.innerHTML = data;
                agregarEquipo(); // Agrega la primera fila para equipos
            })
            .catch(err => {
                contenedor.innerHTML = '<div class="text-danger">Error al cargar el formulario.</div>';
                console.error('Error AJAX:', err);
            });
    });

    // Función para agregar una fila de equipo
    function agregarEquipo() {
        const contenedor = document.getElementById('equiposContainer');
        const nuevoEquipo = document.createElement('div');
        nuevoEquipo.className = 'equipo border rounded-3 p-3 mb-3 shadow-sm';

        nuevoEquipo.innerHTML = `
            <div class="row mb-2 equipo-item">
                <div class="col-md-4">
                    <label class="form-label">Descripción</label>
                    <div class="input-group">
                        <input type="text" name="descripcion[]" class="form-control descripcion">
                        <button type="button" class="btn btn-outline-secondary" onclick="abrirModalProductos(this)">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-1">
                    <label class="form-label">Cant.</label>
                    <input type="number" name="cantidad[]" class="form-control cantidad" min="1" value="1">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Precio</label>
                    <input type="text" name="precio[]" class="form-control precio" min="0" step="0.01">
                </div>
                <div class="col-md-1">
                    <label class="form-label">IVA %</label>
                    <input type="text" name="iva[]" class="form-control impuesto" value = "13">
                </div>
                <div class="col-md-1">
                    <label class="form-label">Desc. %</label>
                    <input type="number" name="descuento[]" class="form-control descuentoInput" min="0" max="${desc_max}">
                    <div class="form-text text-danger aviso-descuento d-none">
                        ⚠ Máx: ${desc_max}%
                    </div>
                </div>
                <div class="col-md-2 text-center resumen-precios">
                    <div><small>Subtotal: ₡<span class="subtotal">0.00</span></small></div>
                    <div><small>IVA: ₡<span class="iva">0.00</span></small></div>
                    <div><small>Desc: ₡<span class="descuento">0.00</span></small></div>
                    <div><strong>Total: ₡<span class="total">0.00</span></strong></div>

                    <!-- 🟡 Inputs ocultos para enviar al backend -->
                    <input type="hidden" name="subtotal_hidden[]" class="subtotal-hidden">
                    <input type="hidden" name="iva_hidden[]" class="iva-hidden">
                    <input type="hidden" name="descuento_hidden[]" class="descuento-hidden">
                    <input type="hidden" name="total_hidden[]" class="total-hidden">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger w-100" onclick="this.closest('.equipo').remove(); recalcularPrecios();">🗑️</button>
                </div>
            </div>
        `;

        const descuentoInputs = nuevoEquipo.querySelectorAll('.descuentoInput');

        descuentoInputs.forEach(input => {
            const aviso = input.parentElement.querySelector('.aviso-descuento');

            input.addEventListener('input', function() {
                let valor = parseFloat(this.value);

                if (valor > desc_max) {
                    this.value = desc_max;
                    aviso.classList.remove('d-none');
                } else {
                    aviso.classList.add('d-none');
                }

                if (valor < 0) {
                    this.value = 0;
                }
            });
        });

        contenedor.appendChild(nuevoEquipo);
    }

    // Función para abrir el modal de productos y cargar la tabla
    function abrirModalProductos(boton) {
        // Guardar referencias a los campos del mismo bloque
        const row = boton.closest('.equipo-item');
        inputDescripcionActual = row.querySelector('.descripcion');
        inputPrecioActual = row.querySelector('.precio');

        llenarTablaProductos();
        const modal = new bootstrap.Modal(document.getElementById('modalProductos'));
        modal.show();
    }


    // Inicializa o limpia y llena la tabla de productos
    function llenarTablaProductos() {
        const esMovil = window.innerWidth < 768;


        tabla.clear(); // limpiar filas anteriores


        productos.forEach(producto => {
            const precioFormateado = new Intl.NumberFormat('es-CR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(parseFloat(producto.elec_precioTotal));

            if (esMovil) {
                const contenidoMovil = `
                    <div class="border rounded p-2 mb-2">
                        <div><strong>Código:</strong> ${producto.elec_codigo}</div>
                        <div><strong>Descripción:</strong> ${producto.elec_detalle}</div>
                        <div><strong>Cantidad:</strong> ${producto.elec_stock}</div>
                        <div><strong>Precio:</strong> ₡${precioFormateado}</div>
                        <div class="mt-2">
                            <button class="btn btn-sm btn-primary w-100" onclick="seleccionarProducto('${producto.elec_detalle}', '${producto.elec_stock}', '${producto.elec_precioTotal}')">Seleccionar</button>
                        </div>
                    </div>
                `;
                tabla.row.add([contenidoMovil, '', '']);
            } else {
                tabla.row.add([
                    producto.elec_codigo,
                    producto.elec_detalle,
                    producto.elec_stock,
                    `₡${precioFormateado}`,
                    `<button class="btn btn-sm btn-primary" onclick="seleccionarProducto('${producto.elec_detalle}', '${producto.elec_stock}', '${producto.elec_precioTotal}')">Seleccionar</button>`
                ]);
            }
        });

        tabla.draw();
    }

    function formatearPrecio(valor) {
        return new Intl.NumberFormat('es-CR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
            useGrouping: true
        }).format(valor);
    }

    // Función para seleccionar un producto y llenar datos en el formulario
    function seleccionarProducto(detalle, stock, precio) {
        if (inputDescripcionActual && inputPrecioActual) {
            inputDescripcionActual.value = detalle;
            inputPrecioActual.value = parseFloat(precio).toFixed(2);
        }

        // Opcional: cerrar modal después de seleccionar
        const modalElement = document.getElementById('modalProductos');
        const modal = bootstrap.Modal.getInstance(modalElement);
        if (modal) modal.hide();

        // Opcional: recalcular precios
        recalcularPrecios();
    }
    // Función para convertir string numérico a número flotante (sin comas ni espacios)
    function parseNumero(valor) {
        if (!valor) return 0;
        let str = valor.toString().replace(/\s/g, '').replace(',', '.');
        let num = parseFloat(str);
        return isNaN(num) ? 0 : num;
    }

    // Función que recalcula los totales de cada equipo y actualiza la vista
    function recalcularPrecios() {
        document.querySelectorAll('.equipo-item').forEach(item => {
            const cantidad = parseNumero(item.querySelector('.cantidad').value);
            const precio = parseNumero(item.querySelector('.precio').value);
            const ivaPorcentaje = parseNumero(item.querySelector('.impuesto').value) / 100 || 0;
            const descuentoPorcentaje = parseNumero(item.querySelector('.descuentoInput').value) / 100 || 0;

            const subtotal = cantidad * precio;
            const descuento = (cantidad * precio) * descuentoPorcentaje;
            const subtotalConDesc = subtotal - descuento;
            const iva = subtotalConDesc * ivaPorcentaje;
            const total = subtotalConDesc + iva;

            item.querySelector('.subtotal').innerText = formatearPrecio(subtotal.toFixed(2));
            item.querySelector('.descuento').innerText = formatearPrecio(descuento.toFixed(2));
            item.querySelector('.iva').innerText = formatearPrecio(iva.toFixed(2));
            item.querySelector('.total').innerText = formatearPrecio(total.toFixed(2));

            // Cargar en inputs ocultos
            item.querySelector('.subtotal-hidden').value = subtotal.toFixed(2);
            item.querySelector('.iva-hidden').value = iva.toFixed(2);
            item.querySelector('.descuento-hidden').value = descuento.toFixed(2);
            item.querySelector('.total-hidden').value = total.toFixed(2);

        });

        calcularTotalesGenerales();
    }

    // Función para actualizar los totales generales
    function calcularTotalesGenerales() {
        let subtotal = 0,
            descuento = 0,
            iva = 0,
            total = 0;

        document.querySelectorAll('.equipo-item').forEach(item => {
            subtotal += parseNumero(item.querySelector('.subtotal').innerText);
            descuento += parseNumero(item.querySelector('.descuento').innerText);
            iva += parseNumero(item.querySelector('.iva').innerText);
            total += parseNumero(item.querySelector('.total').innerText);
        });

        document.getElementById('subtotal_general').value = formatearPrecio(subtotal.toFixed(2));
        document.getElementById('descuento_general').value = formatearPrecio(descuento.toFixed(2));
        document.getElementById('iva_general').value = formatearPrecio(iva.toFixed(2));
        document.getElementById('total_general').value = formatearPrecio(total.toFixed(2));
    }

    // Cuando se cierra el modal de productos, si no seleccionó nada se quita readonly y enfoca
    document.addEventListener('DOMContentLoaded', function() {
        const modalElement = document.getElementById('modalProductos');
        modalElement.addEventListener('hidden.bs.modal', function() {
            if (inputActual && !inputActual.value) {
                inputActual.removeAttribute('readonly');
                inputActual.focus();
            }
        });
    });

    // Validar que cantidad no supere el stock máximo
    document.addEventListener('input', function(e) {


        recalcularPrecios();

    });
</script>

<!-- Petición Ajax Editar -->
<script>
    // Cotización y Equipos Asociados
    const cotizacion = <?= json_encode($cotizaciones); ?>;
    const equipos = <?= json_encode($equipos_coti); ?>;


    // Variable global para el input activo en el modal
    let inActual = null;

    // Productos pasados desde PHP
   

    // Nivel de Acceso desde PHP
    const acceso = <?php echo json_encode($_SESSION['nivel_acceso']); ?>;

    let descuento_max;

    switch (acceso) {
        case 1:
        case 4:
            descuento_max = 20;
            break;
        default:
            descuento_max = 5;
            break;
    }

    // Evento para cargar el formulario y agregar el primer equipo
    document.querySelector('.coti_editar').addEventListener('click', function() {
        const estado = this.getAttribute('data-estado-editar');
        const id_editar = this.getAttribute('data-id');
        const contenedor = document.getElementById('formulario-editar');

        contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

        fetch('ajax/formularios-cotizacion.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'estado=' + encodeURIComponent(estado)
            })
            .then(res => res.text())
            .then(data => {
                contenedor.innerHTML = data;

                // realiza la busqueda en el arreglo e iguala los valores a "cotizacion"
                const coti = cotizacion.find(p => p.cot_id == id_editar);
                if (!coti) {
                    console.error('Cotización no encontrada:', id_editar);
                    return;
                }

                funcionesFormulario_Editar(coti, id_editar);






                agregarEquipo(); // Agrega la primera fila para equipos
            })
            .catch(err => {
                contenedor.innerHTML = '<div class="text-danger">Error al cargar el formulario.</div>';
                console.error('Error AJAX:', err);
            });
    });

    function funcionesFormulario_Editar(coti, id_editar) {
        // Llenar los inputs actuales
        document.getElementById('id').value = coti.cot_id;
        document.getElementById('num_coti').value = coti.cot_codigo;
        document.getElementById('fecha_emite').value = coti.cot_fecha1;
        document.getElementById('fecha_valida').value = coti.cot_fecha2;
        document.getElementById('Vendor').value = coti.cot_vendor;
        document.getElementById('cliente').value = coti.cot_cliente;
        document.getElementById('telefono').value = coti.cot_telefono;
        document.getElementById('subtotal_general').value = coti.cot_subtotal;
        document.getElementById('iva_general').value = coti.cot_iva;
        document.getElementById('descuento_general').value = coti.cot_descuento;
        document.getElementById('total_general').value = coti.cot_total;

        // Cargar los equipos asociaodos a la cotizacion
        const equipo = equipos.filter(eq => Number(eq.cteq_coti_id) === Number(id_editar));
        const contenedor = document.getElementById('equiposContainer');
        contenedor.innerHTML = ''; // Limpiar contenedor actual

        equipo.forEach(eq => {
            const equipoNuevo = document.createElement('div');
            equipoNuevo.className = 'equipo border rounded-3 p-3 mb-3 shadow-sm';

            equipoNuevo.innerHTML = `
            <div class="row mb-2 equipo-item">
                <div class="col-md-4">
                    <label class="form-label">Descripción</label>
                    <div class="input-group">
                        <input type="text" name="descripcion[]" class="form-control descripcion" value="${eq.cteq_detalle}">
                        <button type="button" class="btn btn-outline-secondary" onclick="abrirModalProductos(this)">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-1">
                    <label class="form-label">Cant.</label>
                    <input type="number" name="cantidad[]" class="form-control cantidad" min="1" value="1" value="${eq.cteq_can}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Precio</label>
                    <input type="text" name="precio[]" class="form-control precio" min="0" step="0.01" value="${eq.cteq_precio}">
                </div>
                <div class="col-md-1">
                    <label class="form-label">IVA %</label>
                    <input type="text" name="iva[]" class="form-control impuesto" value = "13" value="${eq.cteq_iva}">
                </div>
                <div class="col-md-1">
                    <label class="form-label">Desc. %</label>
                    <input type="number" name="descuento[]" class="form-control descuentoInput" min="0" max="${descuento_max}" value="${eq.cteq_descuento}">
                    <div class="form-text text-danger aviso-descuento d-none">
                        ⚠ Máx: ${descuento_max}%
                    </div>
                </div>
                <div class="col-md-2 text-center resumen-precios">
                    <div><small>Subtotal: ₡<span class="subtotal" value="${eq.cteq_subtotal}">0.00</span></small></div>
                    <div><small>IVA: ₡<span class="iva" value="${eq.cteq_sub_iva}">0.00</span></small></div>
                    <div><small>Desc: ₡<span class="descuento" value="${eq.cteq_sub_desc}>0.00</span></small></div>
                    <div><strong>Total: ₡<span class="total" value="${eq.cteq_total_linea}">0.00</span></strong></div>

                    <!-- 🟡 Inputs ocultos para enviar al backend -->
                    <input type="hidden" name="subtotal_hidden[]" class="subtotal-hidden" value="${eq.cteq_subtotal}">
                    <input type="hidden" name="iva_hidden[]" class="iva-hidden" value="${eq.cteq_sub_iva}">
                    <input type="hidden" name="descuento_hidden[]" class="descuento-hidden" value="${eq.cteq_sub_desc}">
                    <input type="hidden" name="total_hidden[]" class="total-hidden" value="${eq.cteq_total_linea}">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger w-100" onclick="this.closest('.equipo').remove(); recalcularPrecios();">🗑️</button>
                </div>
            </div>
        `;
            contenedor.appendChild(equipoNuevo);
        });


    }

    

 


   




   



  

</script>

<!-- Petición Ajax Eliminar Producto -->
<script>
    document.addEventListener('click', function(e) {
        // Verificamos si el elemento clickeado es un botón de eliminación
        if (e.target.closest('.cot_eliminar')) {
            const btn = e.target.closest('.cot_eliminar'); // El botón de eliminación que fue clickeado
            const estado = btn.getAttribute('data-eliminar');
            const id = btn.getAttribute('data-id');
            const codigo = btn.getAttribute('data-codigo');
            const contenedor = document.getElementById('formularioEliminar');

            // Mostrar mensaje de carga
            contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

            // Hacer petición AJAX para cargar el formulario de eliminación
            fetch('ajax/formularios-cotizacion.php', {
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
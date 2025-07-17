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



function formatoFecha($fecha)
{
    if (empty($fecha)) return null;
    $fechaObj = DateTime::createFromFormat('d/m/Y', $fecha);
    return $fechaObj ? $fechaObj->format('Y-m-d') : null;
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
                                                <a class="emp_ver" href="#" data-bs-toggle="modal" data-bs-target="#modalVerColaborador" data-estado-ver="7" data-id="<?= $colab['emp_id'] ?>">Ver</a>
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
                    <input type="text" name="descuento[]" class="form-control descuentoInput" min="0" max="100">
                </div>
                <div class="col-md-2 text-center resumen-precios">
                    <div><small>Subtotal: ₡<span class="subtotal">0.00</span></small></div>
                    <div><small>IVA: ₡<span class="iva">0.00</span></small></div>
                    <div><small>Desc: ₡<span class="descuento">0.00</span></small></div>
                    <div><strong>Total: ₡<span class="total">0.00</span></strong></div>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger w-100" onclick="this.closest('.equipo').remove(); recalcularPrecios();">🗑️</button>
                </div>
            </div>
        `;

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
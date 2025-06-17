<?php
require_once '../routes/rutas.php';
session_start();

// Verificación de sesión
if (!isset($_SESSION['usuario'])) {
    header('Location: ' . BASE_PATH . '/index.php');
    exit;
}

// Incluir el header
require_once 'layout/header.php';
require_once '../controllers/seguridadCotroller.php';

$controller = new seguridadConroller();


$equipoAsignado = $controller->getEquipoAsignado();
$equipo = $controller->getEquipo();
$listaSubCategoriasCampo = $controller->getsubCategoria();
$subcategoriasAgruadas = [];

foreach ($listaSubCategoriasCampo as $row) {
    $categoriaID = $row['catg_id'];

    if (!isset($subcategoriasAgruadas[$categoriaID])) {
        $subcategoriasAgruadas[$categoriaID] = [];
    }

    $subcategoriasAgruadas[$categoriaID][] = [
        'id' => $row['scat_id'],
        'detalle' => $row['scat_detalle']
    ];
}



if (isset($_POST['nuevo'])) {
    $errores = [];
    $camposValidar = [
        'stock' => 'Stock',
        'condicion' => 'Condición',
        'categoria' => 'Categoria',
        'subcategoria' => 'Sub-categoria',
        'detalle' => 'Detalle'
    ];

    foreach ($camposValidar as $campo => $nombreCampo) {
        if (empty($_POST[$campo])) {
            $errores[] = $nombreCampo;
        }
    }

    if (empty($errores)) {

        $stock = $_POST['stock'];
        $condicion = $_POST['condicion'];
        $categoria = $_POST['categoria'];
        $subCategoria = $_POST['subcategoria'];
        $colaborador = $_POST['colaborador'];
        $detalle = $_POST['detalle'];


        $addEquipo = $controller->addEquipo(
            $stock,
            $condicion,
            $categoria,
            $subCategoria,
            $colaborador,
            $detalle
        );

        if ($addEquipo == 'success') {
    ?>
            <script>
                let errores = <?= json_encode(implode("\n", $errores)); ?>;
                swal.fire({
                    title: 'Felecicdades',
                    text: 'El equipo fue registraso satisfactoriamente',
                    icon: 'success',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/invSeguridad.php';
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
                    text: 'No se pudo registrar el equipo.',
                    icon: 'error',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/invSeguridad.php';
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
                    window.location.href = '<?= BASE_PATH ?>/invSeguridad.php';
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
                text: 'No se recibió el código del equipo a eliminar',
                icon: 'error',
                confirmButtonText: 'Volver',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= BASE_PATH ?>/invSeguridad.php';
                }
            })
        </script>
        <?php
    } else {
        $eliminarEquipo = $controller->deleteEquipo($id);

        if ($eliminarEquipo == 'success') {
        ?>
            <script>
                swal.fire({
                    title: 'Felecicdades',
                    text: 'El equipo fue eliminado satisfactoriamente',
                    icon: 'success',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/invSeguridad.php';
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
                        window.location.href = '<?= BASE_PATH ?>/invSeguridad.php';
                    }
                })
            </script>
        <?php
        }
    }
}

if (isset($_POST['editar'])) {
    $errores = [];
    $camposValidar = [
        'stock' => 'Stock',
        'condicion' => 'Condición',
        'categoria' => 'Categoria',
        'subcategoria' => 'Sub-categoria',
        'detalle' => 'Detalle'
    ];

    foreach ($camposValidar as $campo => $nombreCampo) {
        if (empty($_POST[$campo])) {
            $errores[] = $nombreCampo;
        }
    }

    if (empty($errores)) {

        $stock = $_POST['stock'];
        $condicion = $_POST['condicion'];
        $categoria = $_POST['categoria'];
        $subCategoria = $_POST['subcategoria'];
        $colaborador = $_POST['colaborador'];
        $detalle = $_POST['detalle'];
        $id = $_POST['id'];

        $addHerramienta = $controller->updateEquipo(
            $stock,
            $condicion,
            $categoria,
            $subCategoria,
            $colaborador,
            $detalle,
            $id
        );

        if ($addHerramienta == 'success') {
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
                        window.location.href = '<?= BASE_PATH ?>/invSeguridad.php';
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
                        window.location.href = '<?= BASE_PATH ?>/invSeguridad.php';
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
                    window.location.href = '<?= BASE_PATH ?>/invSeguridad.php';
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

        <h2 class="main-title text-center">Inventario Seguridad</h2>

        <div class="row stat-cards">
            <div class="col-md-2 col-xl-3">
                <!-- El estado 4 indica que es para agregar -->
                <button class="nuevo_equipo" data-bs-toggle="modal" data-bs-target="#modalNuevoEquipo" data-estado-agregar="4" style="border: none; background: none;">
                    <article class="stat-cards-item">
                        <div class="icono_nuevo">
                            <i data-feather="plus" style="color: white;"></i>
                        </div>
                        <div class="stat-cards-info">
                            <p class="stat-cards-info__num m-2">Nuevo Equipo</p>
                        </div>
                    </article>
                </button>
            </div>
        </div>

        <hr class="line mt-1 mb-2 pb-2">

        <!-- Tabla para las herramientas -->
        <div id="botones-filtro" class="btn-group" role="group" aria-label="Basic mixed styles example">
            <a href="<?= VIEW_PATH ?>/invSeguridadView.php" class="inv_suficiente btn btn-primary" id="inv_suficiente">General</a>
            <button type="button" class="inv_asignado btn btn-success" id="inv_asignado" data-estado-asi="16">Asignado</button>
        </div>

        <div class="tabla-inventarios" id="tabla-inventarios">

            <h6 class="indicador m-2 p-2"><b><i>Inventaior de campo sin asignar.</i></b></h6>

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
                        <th>Stock</th>
                        <th>Detalle</th>
                        <th>Condición</th>
                        <th>Categoria</th>
                        <th>Subcategoria</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (is_array($equipo)): ?>
                        <?php foreach ($equipo as $equip): ?>
                            <tr>
                                <td data-label="Stock"><?= htmlspecialchars($equip['scat_cantidad']) ?></td>
                                <td data-label="Detalle"><?= $equip['segd_detalle'] ?></td>
                                <td data-label="Condición"><?= htmlspecialchars($equip['segd_condicion']) ?></td>
                                <td data-label="Categoria"><?= $equip['catg_detalle'] ?></td>
                                <td data-label="Subcategoria"><?= $equip['scat_detalle'] ?></td>
                                <td data-label="Acciones">
                                    <span class="p-relative">
                                        <button class="dropdown-btn transparent-btn" type="button" title="More info">
                                            <div class="sr-only">More info</div>
                                            <i data-feather="more-horizontal" aria-hidden="true"></i>
                                        </button>
                                        <ul class="users-item-dropdown dropdown pt-1">
                                            <li>
                                                <a class="editar_equipo" href="#" data-bs-toggle="modal" data-bs-target="#modalEditar" data-estado-editar="5" data-asignado="0" data-editar-id="<?= $equip['segd_id'] ?>">Editar</a>
                                            </li>
                                            <li>
                                                <a class="eliminar_producto" href="#" data-bs-toggle="modal" data-bs-target="#modalEliminar" data-estado-eliminar="6" data-id="<?= $equip['segd_id'] ?>" data-codigo="<?= $equip['segd_detalle'] ?>">Eliminar</a>
                                            </li>
                                        </ul>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No hay equipo de seguridad para mostrar.</td>
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
    <div class="modal fade" id="modalNuevoEquipo" tabindex="-1" aria-labelledby="modalNuevoEquipoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">

                <div class="modal-header p-2">
                    <h6 class="modal-title" id="modalNuevoEquipoLabel"><b>Agregar Equipo de Seguridad Nuevo</b></h6>
                    <button type="button" class="btn-close p-1 me-2 mt-1" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div id="formulario-nuevo">

                </div>
            </div>
        </div>
    </div>
    <!-- Fin Modal Producto Nuevo -->

    <!-- Inicio Modal Editar -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">

                <div class="modal-header p-2">
                    <h6 class="modal-title" id="modalNuevoProductoLabel"><b>Editar Equipo de Seguridad</b></h6>
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

<!-- Petición Ajax para la tabla inv. Asignado -->
<script>
    // Configurar los botones para hacer la petición AJAX
    document.querySelector('.inv_asignado').addEventListener('click', function() {
        const estado = this.getAttribute('data-estado-asi');
        const contenedor = document.getElementById('tabla-inventarios');

        //Muestra "Cargando..." mientras obtiene el contenido
        contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

        fetch('ajax/tablas-invSeguridad.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'estado=' + encodeURIComponent(estado)
            })
            .then(res => res.text())
            .then(data => {
                contenedor.innerHTML = data;

                const tabla = $('#tablaAsignado').DataTable({
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
                $('#tablaAsignado_filter').appendTo('#contenedor-busqueda');
                $('#tablaAsignado_info').appendTo('#contenedor-info');
                $('#tablaAsignado_paginate').appendTo('#contenedor-paginacion');

            })
            .catch(err => {
                contenedor.innerHTML = '<div class="text-danger">Error al cargar la tabla.</div>';
                console.error('Error AJAX:', err);
            });
    });
</script>

<!-- Petición Ajax Nueva Herramienta -->
<script>
    const subcategorias = <?= json_encode($subcategoriasAgruadas, JSON_UNESCAPED_UNICODE) ?>;

    // Petición a Formulario Nuevo Producto.
    document.querySelector('.nuevo_equipo').addEventListener('click', function() {
        const estado = this.getAttribute('data-estado-agregar');
        const contenedor = document.getElementById('formulario-nuevo');

        //Muestra "Cargando..." mientras obtiene el contenido
        contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

        fetch('ajax/formularios-invSeguridad.php', {
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
        const categoria_select = document.getElementById('categoria');
        const subSelect = document.getElementById('subcategoria');

        if (!categoria_select || !subSelect) return;

        // Agrega el event listener dinámicamente
        categoria_select.addEventListener('change', function() {
            const catID = String(this.value);
            subSelect.innerHTML = '<option value="">Seleccione una categoria</option>';

            const subcats = subcategorias[catID];
            if (subcats) {
                subcats.forEach(sub => {
                    const option = document.createElement('option');
                    option.value = sub.id;
                    option.textContent = sub.detalle;
                    subSelect.appendChild(option);
                });
            } else {
                console.log("No se encontraron subcategorías para ID:", catID);
            }
        });
    }
</script>

<!-- Petición Ajax Editar Producto -->
<script>

</script>
<script>
    // Productos
    const equipoAsignado = <?= json_encode($equipoAsignado); ?>;
    const equiposGenerales = <?= json_encode($equipo); ?>;

    // Petición a Editar Producto
    document.addEventListener('click', function(e) {
        if (e.target.closest('.editar_equipo')) {
            const btn = e.target.closest('.editar_equipo');
            const estado = btn.getAttribute('data-estado-editar');
            const herramientaID = parseInt(btn.getAttribute('data-editar-id'), 10);
            const asignado = parseInt(btn.getAttribute('data-asignado'), 10);

            const herramientas = (asignado === 0) ? equiposGenerales : equipoAsignado;

            const contenedor = document.getElementById('formulario-editar');
            contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

            fetch('ajax/formularios-invSeguridad.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'estado=' + encodeURIComponent(estado)
                })
                .then(res => res.text())
                .then(data => {
                    contenedor.innerHTML = data;
                    subcategoria();

                    const herramienta = herramientas.find(p => p.segd_id == herramientaID);
                    if (!herramienta) {
                        console.error('Producto no encontrado:', herramientaID);
                        return;
                    }

                    requestAnimationFrame(() => {
                        funcionesFormulario_Editar(herramienta);

                        const modalEditarElement = document.getElementById('modalEditar');
                        const modal = bootstrap.Modal.getOrCreateInstance(modalEditarElement);

                        modalEditarElement.addEventListener('shown.bs.modal', function() {
                            const btnClose = modalEditarElement.querySelector('.btn-close');
                            if (btnClose) {
                                btnClose.focus();
                            }
                        });

                        modal.show();
                    });
                })
                .catch(err => {
                    contenedor.innerHTML = '<div class="text-danger">Error al cargar el formulario.</div>';
                    console.error('Error AJAX:', err);
                });
        }
    });

    function funcionesFormulario_Editar(herramienta) {


        // Rellenar campos
        document.getElementById('id').value = herramienta.segd_id;
        document.getElementById('stock').value = herramienta.scat_cantidad;
        document.getElementById('condicion').value = herramienta.segd_condicion;
        document.getElementById('categoria').value = herramienta.segd_catg_IDcategoria;

        // Dispara el cambio para que se carguen las subcategorías
        const categoria_select = document.getElementById('categoria');
        categoria_select.dispatchEvent(new Event('change'));

        // Esperar un poco para que el DOM se actualice con las nuevas opciones
        setTimeout(() => {
            document.getElementById('subcategoria').value = herramienta.segd_scat_IDsubcategoria;
        }, 50);


        document.getElementById('colaborador').value = herramienta.segd_empl_IDempleado;
        document.getElementById('detallHerramienta').value = herramienta.segd_detalle;
    }

    function subcategoria() {
        const categoria_select = document.getElementById('categoria');
        const subSelect = document.getElementById('subcategoria');

        if (!categoria_select || !subSelect) return;

        // Agrega el event listener dinámicamente
        categoria_select.addEventListener('change', function() {
            const catID = String(this.value);
            subSelect.innerHTML = '<option value="">Seleccione una categoria</option>';

            const subcats = subcategorias[catID];
            if (subcats) {
                subcats.forEach(sub => {
                    const option = document.createElement('option');
                    option.value = sub.id;
                    option.textContent = sub.detalle;
                    subSelect.appendChild(option);
                });
            } else {
                console.log("No se encontraron subcategorías para ID:", catID);
            }
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

            console.log(codigo);

            // Mostrar mensaje de carga
            contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

            // Hacer petición AJAX para cargar el formulario de eliminación
            fetch('ajax/formularios-invElectonico.php', {
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
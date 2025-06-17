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
require_once '../controllers/electronicoConroller.php';

$controller = new electronicoConroller();
$listaProveedores = $controller->getProveedor();
$listaCategorias = $controller->getCategoria();
$listaSubCategorias = $controller->getsubCategoria();
$productos = $controller->getElectronico();
$productosGeneral = $controller->getElectronicoGeneral();


if (isset($_POST['nuevo'])) {
    $errores = [];
    $camposValidar = [
        'stock' => 'Stock',
        'minima' => 'Cantidad Mínima',
        'marca' => 'Marca',
        'codigo' => 'Código',
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
        $cantidadMin = $_POST['minima'];
        $marca = $_POST['marca'];
        $codigo = $_POST['codigo'];
        $categoria = $_POST['categoria'];
        $subCategoria = $_POST['subcategoria'];
        $proveedor = $_POST['proveedor'];
        $precioDolar = $_POST['precioDolar'];
        $tipoCambio = $_POST['proveedor'];
        $precio = $_POST['precioNoGana'];
        $porcentaje = $_POST['porcentaje'];
        $total = $_POST['total'];
        $detalle = $_POST['detalle'];


        $addProducto = $controller->addElectronico(
            $stock,
            $cantidadMin,
            $marca,
            $codigo,
            $categoria,
            $subCategoria,
            $proveedor,
            $precioDolar,
            $tipoCambio,
            $precio,
            $porcentaje,
            $total,
            $detalle
        );

        if ($addProducto == 'success') {
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
                        window.location.href = '<?= BASE_PATH ?>/invElectronico.php';
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
                        window.location.href = '<?= BASE_PATH ?>/invElectronico.php';
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
                    window.location.href = '<?= BASE_PATH ?>/invElectronico.php';
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
                    window.location.href = '<?= BASE_PATH ?>/invElectronico.php';
                }
            })
        </script>
        <?php
    } else {
        $eliminarProducto = $controller->deleteProducto($id);

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
                        window.location.href = '<?= BASE_PATH ?>/invElectronico.php';
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
                        window.location.href = '<?= BASE_PATH ?>/invElectronico.php';
                    }
                })
            </script>
        <?php
        }
    }
}

if (isset($_POST['detalleEditar'])) {
    $errores = [];
    $camposValidar = [
        'stockEditar' => 'Stock',
        'minimaEditar' => 'Cantidad Mínima',
        'marcaEditar' => 'Marca',
        'codigoEditar' => 'Código',
        'categoriaEditar' => 'Categoria',
        'subcategoriaEditar' => 'Sub-categoria',
        'proveedorEditar' => 'Proveedor',
        'precioDolarEditar' => 'Precio Dolar',
        'dolarEditar' => 'Tipo Cambio',
        'precioNoGanaEditar' => 'Precio Proveedor',
        'porcentajeEditar' => 'Porcentaje Ganancia',
        'totalEditar' => 'Total',
        'detalleEditar' => 'Detalle',
        'idEditar' => 'Editar'
    ];

    foreach ($camposValidar as $campo => $nombreCampo) {
        if (empty($_POST[$campo])) {
            $errores[] = $nombreCampo;
        }
    }

    if (empty($errores)) {

        $stock = $_POST['stockEditar'];
        $cantidadMin = $_POST['minimaEditar'];
        $marca = $_POST['marcaEditar'];
        $codigo = $_POST['codigoEditar'];
        $categoria = $_POST['categoriaEditar'];
        $subCategoria = $_POST['subcategoriaEditar'];
        $proveedor = $_POST['proveedorEditar'];
        $precioDolar = $_POST['precioDolarEditar'];
        $tipoCambio = $_POST['proveedorEditar'];
        $precio = $_POST['precioNoGanaEditar'];
        $porcentaje = $_POST['porcentajeEditar'];
        $total = $_POST['totalEditar'];
        $detalle = $_POST['detalleEditar'];
        $id = $_POST['idEditar'];


        $updateProducto = $controller->updateElectronico(
            $stock,
            $cantidadMin,
            $marca,
            $codigo,
            $categoria,
            $subCategoria,
            $proveedor,
            $precioDolar,
            $tipoCambio,
            $precio,
            $porcentaje,
            $total,
            $detalle,
            $id
        );

        if ($updateProducto == 'success') {
        ?>
            <script>
                let errores = <?= json_encode(implode("\n", $errores)); ?>;
                swal.fire({
                    title: 'Felecicdades',
                    text: 'El producto fue editado satisfactoriamente',
                    icon: 'success',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/invElectronico.php';
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
                    text: 'No se pudo editar el producto.',
                    icon: 'error',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/invElectronico.php';
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
                    window.location.href = '<?= BASE_PATH ?>/invElectronico.php';
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

        <h2 class="main-title text-center">Inventario Eletrónico</h2>

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

        <hr class="line mt-1 mb-2 pb-2">

        <!-- Tabla para los productos -->
        <div id="botones-filtro" class="btn-group" role="group" aria-label="Basic mixed styles example">
            <a href="<?= VIEW_PATH ?>/invElectronicoView.php" class="inv_suficiente btn btn-success" id="inv_suficiente">Óptimo</a>
            <button type="button" class="inv_advertencia btn btn-warning" id="inv_advertencia" data-estado-adv="2">Advertencia</button>
            <button type="button" class="inv_critico btn btn-danger" id="inv_critico" data-estado-crt="3">Crítico</button>
        </div>

        <div class="tabla-inventarios" id="tabla-inventarios">

            <h6 class="indicador m-2 p-2"><b><i>✅ Inventario Óptimo</i></b></h6>

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
                        <th></th>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Stock</th>
                        <th>Cant. Mínima</th>
                        <th>Precio Proveedor</th>
                        <th>Precio Venta</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (is_array($productos)): ?>
                        <?php foreach ($productos as $producto): ?>
                            <tr>
                                <td><i class="bi bi-circle-fill" style="color: green !important;"></i></td>
                                <td data-label="Código"><?= htmlspecialchars($producto['elec_codigo']) ?></td>
                                <td data-label="Descripción"><?= $producto['elec_detalle'] ?></td>
                                <td data-label="Stock"><?= htmlspecialchars($producto['elec_stock']) ?></td>
                                <td data-label="Cant. Mínima"><?= htmlspecialchars($producto['elec_cantMin']) ?></td>
                                <td data-label="Precio Proveedor">₡<?= number_format($producto['elec_precio'], 2, ',', '.') ?></td>
                                <td data-label="Precio Venta">₡<?= number_format($producto['elec_precioTotal'], 2, ',', '.') ?></td>
                                <td data-label="Acciones">
                                    <span class="p-relative">
                                        <button class="dropdown-btn transparent-btn" type="button" title="More info">
                                            <div class="sr-only">More info</div>
                                            <i data-feather="more-horizontal" aria-hidden="true"></i>
                                        </button>
                                        <ul class="users-item-dropdown dropdown pt-1">
                                            <li>
                                                <a class="editar_producto" href="#" data-bs-toggle="modal" data-bs-target="#modalEditar" data-estado-editar="5" data-editar-id="<?= $producto['elec_id'] ?>">Editar</a>
                                            </li>
                                            <li>
                                                <a class="eliminar_producto" href="#" data-bs-toggle="modal" data-bs-target="#modalEliminar" data-estado-eliminar="6" data-id="<?= $producto['elec_id'] ?>" data-codigo="<?= $producto['elec_codigo'] ?>">Eliminar</a>
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


<!-- Petición Ajax para la tabla inv. Advertencia -->
<script>
    // Configurar los botones para hacer la petición AJAX
    document.querySelector('.inv_advertencia').addEventListener('click', function() {
        const estado = this.getAttribute('data-estado-adv');
        const contenedor = document.getElementById('tabla-inventarios');

        //Muestra "Cargando..." mientras obtiene el contenido
        contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

        fetch('ajax/tablas-invElectronico.php', {
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

<!-- Petición Ajax para la tabla inv. Crítico -->
<script>
    // Configurar los botones para hacer la petición AJAX
    document.querySelector('.inv_critico').addEventListener('click', function() {
        const estado = this.getAttribute('data-estado-crt');
        const contenedor = document.getElementById('tabla-inventarios');

        //Muestra "Cargando..." mientras obtiene el contenido
        contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

        fetch('ajax/tablas-invElectronico.php', {
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

<!-- Petición Ajax Nuevo Producto -->
<script>
    // Petición a Formulario Nuevo Producto.
    document.querySelector('.nuevo_producto').addEventListener('click', function() {
        const estado = this.getAttribute('data-estado-agregar');
        const contenedor = document.getElementById('formulario-nuevo');

        //Muestra "Cargando..." mientras obtiene el contenido
        contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

        fetch('ajax/formularios-invElectonico.php', {
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
        // CKEditor
        ClassicEditor
            .create(document.querySelector('#detalle'))
            .catch(error => {
                console.error(error);
            });

        // Cálculos y eventos
        const proveedorSelect = document.getElementById('proveedor');
        const tipoCambioInput = document.getElementById('dolar');
        const precioDolarInput = document.getElementById('precioDolar');
        const porcentajeInput = document.getElementById('porcentaje');
        const precioEmpresaInput = document.getElementById('precioNoGana');
        const totalInput = document.getElementById('total');

        let tipoCambio = 0;

        // Función que calcula los precios
        function calcularPrecios() {
            const precioDolar = parseFloat(precioDolarInput.value) || 0;
            const porcentaje = parseFloat(porcentajeInput.value) || 0;

            const precioColonesEmpresa = precioDolar * tipoCambio;
            precioEmpresaInput.value = precioColonesEmpresa.toFixed(2);

            const ganancia = 1 + (porcentaje / 100);
            const precioTotal = precioColonesEmpresa * ganancia;
            totalInput.value = precioTotal.toFixed(2);
        }

        // Tipo de cambio según el proveedor seleccionado
        if (proveedorSelect) {
            proveedorSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                tipoCambio = parseFloat(selectedOption.getAttribute('data-cambio')) || 0;
                tipoCambioInput.value = tipoCambio;
                calcularPrecios();
            });

            // Inicializa el tipo de cambio si ya hay uno seleccionado
            const selectedOption = proveedorSelect.options[proveedorSelect.selectedIndex];
            tipoCambio = parseFloat(selectedOption.getAttribute('data-cambio')) || 0;
            tipoCambioInput.value = tipoCambio;
        }

        // Eventos para recalcular al cambiar valores
        precioDolarInput?.addEventListener('input', calcularPrecios);
        porcentajeInput?.addEventListener('input', calcularPrecios);

        // Ejecutar el cálculo inicial si ya hay datos
        calcularPrecios();
    }
</script>

<!-- Petición Ajax Editar Producto -->
<script>
    // Productos
    const productos = <?= json_encode($productosGeneral); ?>;

    // Petición a Editar Producto
    document.addEventListener('click', function(e) {
        if (e.target.closest('.editar_producto')) {
            const btn = e.target.closest('.editar_producto');
            const estado = btn.getAttribute('data-estado-editar');
            const productoID = btn.getAttribute('data-editar-id');
            const contenedor = document.getElementById('formulario-editar');

            console.log(estado);
            console.log(productoID);

            contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

            fetch('ajax/formularios-invElectonico.php', {
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
        let editorDetalleEditar;

        ClassicEditor
            .create(document.querySelector('#detalleEditar'))
            .then(editor => {
                editor.setData(producto.elec_detalle || '');
                editorDetalleEditar = editor;
            });

        const proveedorSelect = document.getElementById('proveedorEditar');
        const tipoCambioInput = document.getElementById('dolarEditar');
        const precioDolarInput = document.getElementById('precioDolarEditar');
        const porcentajeInput = document.getElementById('porcentajeEditar');
        const precioEmpresaInput = document.getElementById('precioNoGanaEditar');
        const totalInput = document.getElementById('totalEditar');

        function calcularPrecios() {
            const selectedOption = proveedorSelect.options[proveedorSelect.selectedIndex];
            const tipoCambio = parseFloat(selectedOption.getAttribute('data-cambio')) || 0;
            tipoCambioInput.value = tipoCambio;

            const precioDolar = parseFloat(precioDolarInput.value) || 0;
            const porcentaje = parseFloat(porcentajeInput.value) || 0;
            const precioColonesEmpresa = precioDolar * tipoCambio;
            precioEmpresaInput.value = precioColonesEmpresa.toFixed(2);
            const ganancia = 1 + (porcentaje / 100);
            const precioTotal = precioColonesEmpresa * ganancia;
            totalInput.value = precioTotal.toFixed(2);
        }

        proveedorSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const nuevoTipoCambio = parseFloat(selectedOption.getAttribute('data-cambio')) || 0;
            tipoCambioInput.value = nuevoTipoCambio;
            calcularPrecios();
        });

        precioDolarInput.addEventListener('input', calcularPrecios);
        porcentajeInput.addEventListener('input', calcularPrecios);

        // Rellenar campos
        document.getElementById('idEditar').value = producto.elec_id;
        document.getElementById('stockEditar').value = producto.elec_stock;
        document.getElementById('minimaEditar').value = producto.elec_cantMin;
        document.getElementById('marcaEditar').value = producto.elec_marca;
        document.getElementById('codigoEditar').value = producto.elec_codigo;
        document.getElementById('categoriaEditar').value = producto.elec_catg_IDcategoria;
        document.getElementById('subcategoriaEditar').value = producto.elec_scat_IDsubcategoria;
        document.getElementById('proveedorEditar').value = producto.elec_prov_IDproveedor;
        document.getElementById('precioDolarEditar').value = producto.elec_precioDolar;
        document.getElementById('dolarEditar').value = producto.elec_porv_IDdolar;
        document.getElementById('precioNoGanaEditar').value = producto.elec_precio;
        document.getElementById('porcentajeEditar').value = producto.elec_porcentaje;
        document.getElementById('totalEditar').value = producto.elec_precioTotal;

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
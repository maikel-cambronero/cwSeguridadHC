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
require_once '../controllers/usuariosController.php';

$controller = new usuariosController();

$colaboradores = $controller->get_empleado();
$roles = $controller->get_rol();
$usuarios_general = $controller->get_usuario_general();

$usuarios = $controller->get_usuarios_activos();
//$usuarios_general = $controller->get_usuarios_general();


function formatoFecha($fecha)
{
    if (empty($fecha)) return null;
    $fechaObj = DateTime::createFromFormat('d/m/Y', $fecha);
    return $fechaObj ? $fechaObj->format('Y-m-d') : null;
}

if (isset($_POST['nuevo'])) {
    $errores = [];

    $camposValidar = [
        'cedula' => 'Cédula',
        'acceso' => 'Nivel de Acceso',
        'password' => 'Contraseña'
    ];

    foreach ($camposValidar as $campo => $nombreCampo) {
        if (empty($_POST[$campo])) {
            $errores[] = $nombreCampo;
        }
    }

    if (empty($errores)) {

        $cedula = $_POST['cedula'];
        $acceso = $_POST['acceso'];
        $password = $_POST['password'];
        $addUser = '';

        if (preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
            // Ahora enviar a la base de datos SOLO el nombre del archivo
            $addUser = $controller->addUser($cedula, $acceso, $password);
        } else {
            // Contraseña inválida
?>
            <script>
                let errores = <?= json_encode(implode("\n", $errores)); ?>;
                swal.fire({
                    title: 'Error',
                    text: 'La contraseña debe tener al menos 8 caracteres, incluir una mayúscula, una minúscula, un número y un carácter especial.',
                    icon: 'error',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/usuarios.php';
                    }
                })
            </script>
        <?php
        }

        if ($addUser == 'success') {
        ?>
            <script>
                let errores = <?= json_encode(implode("\n", $errores)); ?>;
                swal.fire({
                    title: 'Felecicdades',
                    text: 'El usuario fue registraso satisfactoriamente',
                    icon: 'success',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/usuarios.php';
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
                    text: 'No se pudo registrar el usuario.',
                    icon: 'error',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/usuarios.php';
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
                    window.location.href = '<?= BASE_PATH ?>/usuarios.php';
                }
            })
        </script>
        <?php
    }
}

if (isset($_POST['accesobtn'])) {
    if (!empty($_POST['acceso'] && $_POST['observaciones'])) {
        $id = $_POST['id'];
        $usuario = $_SESSION['usuario'];
        $acceso = $_POST['acceso'];
        $observaciones = $_POST['observaciones'];

        $update = $controller->update_acceso($id, $usuario, $acceso, $observaciones);

        if ($update == 'success') {
        ?>
            <script>
                swal.fire({
                    title: 'Felecicdades',
                    text: 'El acceso del usuario fue editado satisfactoriamente',
                    icon: 'success',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/usuarios.php';
                    }
                })
            </script>
        <?php
        } else {
        ?>
            <script>
                swal.fire({
                    title: 'Error',
                    text: 'No se pudo editar el acceso del usuario.',
                    icon: 'error',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/usuarios.php';
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
                text: 'El nivel de acceso y la observación son campos requeridos.',
                icon: 'error',
                confirmButtonText: 'Volver',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= BASE_PATH ?>/usuarios.php';
                }
            })
        </script>
        <?php
    }
}


if (isset($_POST['btnEstado'])) {
    if (!empty($_POST['id'])) {
        $id = $_POST['id'];
        $estado = $_POST['estado'];

        // Determinar acción
        if ($estado == 32) {
            $action = "inactivado";
        } else {
            $action = "activado";
        }

        // Ejecutar método de actualización
        $updateEstado = $controller->updateEstadoUser($id, $estado);

        if ($updateEstado == 'success') {
        ?>
            <script>
                Swal.fire({
                    title: 'Felicidades',
                    text: "El usuario fue <?= $action ?> satisfactoriamente",
                    icon: 'success',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/usuarios.php';
                    }
                });
            </script>
        <?php
        } else {
        ?>
            <script>
                Swal.fire({
                    title: 'Error',
                    text: 'No se pudo editar el estado del usuario.',
                    icon: 'error',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/usuarios.php';
                    }
                });
            </script>
        <?php
        }
    } else {
        ?>
        <script>
            Swal.fire({
                title: 'Error',
                text: 'No se proporcionó el ID del usuario.',
                icon: 'error',
                confirmButtonText: 'Volver',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= BASE_PATH ?>/usuarios.php';
                }
            });
        </script>
<?php
    }
}



?>

<!-- ! Main -->
<main class="main users chart-page" id="skip-target">
    <div class="container">

        <h2 class="main-title text-center">Usuarios HC</h2>

        <div class="row stat-cards">
            <div class="col-md-2 col-xl-3">
                <!-- El estado 4 indica que es para agregar -->
                <button class="user_nuevo" data-bs-toggle="modal" data-bs-target="#modalNuevaColaborador" data-estado-agregar="4" style="border: none; background: none;">
                    <article class="stat-cards-item">
                        <div class="icono_nuevo">
                            <i data-feather="plus" style="color: white;"></i>
                        </div>
                        <div class="stat-cards-info">
                            <p class="stat-cards-info__num m-2">Nuevo Usuario</p>
                        </div>
                    </article>
                </button>
            </div>
        </div>

        <hr class="line mt-1 mb-2 pb-2">

        <!-- Tabla para las herramientas -->
        <div id="botones-filtro" class="btn-group" role="group" aria-label="Basic mixed styles example">
            <a href="<?= BASE_PATH ?>/usuarios.php" class="emp_activos btn btn-success" id="emp_activos">Acivos</a>
            <button type="button" class="user_inactivo btn btn-secondary" id="emp_inactivo" data-estado-ina="32">Inactivos</button>
        </div>

        <div class="tabla-inventarios" id="tabla-inventarios">

            <h6 class="indicador m-2 p-2"><b><i>Usuarios Activos</i></b></h6>

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
                        <th>Usuario</th>
                        <th>Acceso</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (is_array($usuarios)): ?>
                        <?php foreach ($usuarios as $user): ?>
                            <tr>
                                <td data-label="Código"><?= $user['emp_codigo'] ?></td>
                                <td data-label="Colaborador">
                                    <div>
                                        <span style="color: blue;"><?= $user['emp_cedula'] ?></span>
                                        <p><?= $user['emp_nombre'] . " " . $user['emp_apellidos'] ?></p>
                                    </div>
                                </td>
                                <td data-label="Usuario"><?= $user['user_name'] ?></td>
                                <td data-label="Acceso"><?= $user['acs_nombre'] ?></td>
                                <td data-label="Acciones">
                                    <span class="p-relative">
                                        <button class="dropdown-btn transparent-btn" type="button" title="More info">
                                            <div class="sr-only">More info</div>
                                            <i data-feather="more-horizontal" aria-hidden="true"></i>
                                        </button>
                                        <ul class="users-item-dropdown dropdown pt-1">
                                            <li>
                                                <a class="user_acces" href="#" data-bs-toggle="modal" data-bs-target="#modalAcces" data-estado-editar="5" data-editar-id="<?= $user['user_id'] ?>">Nivel Acceso</a>
                                            </li>
                                            <li>
                                                <a class="user_estado" href="#" data-bs-toggle="modal" data-bs-target="#modalEstado" data-estado-estado="6" data-estado="32" data-usuario="<?= $user['user_name'] ?>" data-estado-id="<?= $user['user_id'] ?>">Desctivar</a>
                                            </li>
                                            <li>
                                                <a href="#" class="user_pass" data-id="<?= $user['user_id'] ?>" data-bs-toggle="modal" data-bs-target="#modalVerificarPass">Contraseña</a>
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
    <div class="modal fade" id="modalNuevaColaborador" tabindex="-1" aria-labelledby="modalNuevaColaboradorLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header p-2">
                    <h6 class="modal-title" id="modalNuevaColaboradorLabel"><b>Ingresar un Nuevo Usuario</b></h6>
                    <button type="button" class="btn-close p-1 me-2 mt-1" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div id="formulario-nuevo">

                </div>
            </div>
        </div>
    </div>
    <!-- Fin Modal Nuevo -->


    <!-- Inicio Modal Nivel de Acceso -->
    <div class="modal fade" id="modalAcces" tabindex="-1" aria-labelledby="modalAccesLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">

                <div class="modal-header p-2">
                    <h6 class="modal-title" id="modalAccesLabel"><b>Cambiar Acceso del Usuario</b></h6>
                    <button type="button" class="btn-close p-1 me-2 mt-1" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div id="formulario-acceso">

                </div>
            </div>
        </div>
    </div>
    <!-- Fin Modal Nivel de Acceso -->

    <!-- Inicio Modal Verificar Contraseña -->
    <div class="modal fade" id="modalVerificarPass" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form id="formVerificarPass" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Verificación de Identidad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_usuario_seleccionado" id="idUsuarioSeleccionado">
                    <div class="mb-3">
                        <label for="pass" class="form-label">Tu contraseña actual</label>
                        <input type="password" class="form-control" name="pass" id="pass" required>
                    </div>
                    <p class="text-danger d-none" id="errorVerificacion"></p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Verificar</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Fin Modal Verificar Contraseña -->

    <!-- Inicio Modal cambiar Contraseña -->
    <div class="modal fade" id="modalCambiarPass" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form id="formCambiarPass" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cambiar contraseña del usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_usuario" id="userIdCambio">
                    <div class="mb-3">
                        <label>Nueva contraseña</label>
                        <input type="password" name="nueva_pass" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Confirmar contraseña</label>
                        <input type="password" name="confirmar_pass" class="form-control" required>
                    </div>
                    <p class="text-danger d-none" id="mensajeCambioPass"></p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Fin Modal cambiar Contraseña -->

    <!-- Inicio Modal Activar/Desactivar -->
    <div class="modal fade" id="modalEstado" tabindex="-1" aria-labelledby="modalEstadoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header p-2">
                    <h6 class="modal-title" id="modalEstadoLabel"><b>Cambiar Estado del Usuario</b></h6>
                    <button type="button" class="btn-close p-1 me-2 mt-1" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div id="formularioEstado">

                </div>
            </div>
        </div>
    </div>
    <!-- Fin Modal Activar/Desactivar -->

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
    document.querySelector('.user_inactivo').addEventListener('click', function() {
        const estado = this.getAttribute('data-estado-ina');
        const contenedor = document.getElementById('tabla-inventarios');

        //Muestra "Cargando..." mientras obtiene el contenido
        contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

        fetch('ajax/tablas-usuarios.php', {
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

<!-- Petición Ajax Nuevo -->
<script>
    // Petición a Formulario Nuevo Producto.
    document.querySelector('.user_nuevo').addEventListener('click', function() {
        const estado = this.getAttribute('data-estado-agregar');
        const contenedor = document.getElementById('formulario-nuevo');

        //Muestra "Cargando..." mientras obtiene el contenido
        contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

        fetch('ajax/formularios-usuarios.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'estado=' + encodeURIComponent(estado)
            })
            .then(res => res.text())
            .then(data => {
                contenedor.innerHTML = data;

            })
            .catch(err => {
                contenedor.innerHTML = '<div class="text-danger">Error al cargar el formulario.</div>';
                console.error('Error AJAX:', err);
            });
    });
</script>

<!-- Petición Ajax Cambiar el Acceso del Usuario -->
<script>
    document.addEventListener('click', function(e) {
        // Verificamos si se hizo clic en un botón con la clase 'user_acces'
        if (e.target.closest('.user_acces')) {
            const btn = e.target.closest('.user_acces');
            const estado = btn.getAttribute('data-estado-editar');
            const userID = btn.getAttribute('data-editar-id');
            const contenedor = document.getElementById('formulario-acceso');

            // Mostrar mensaje de carga
            contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

            // Hacer petición AJAX para cargar el formulario
            fetch('ajax/formularios-usuarios.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'estado=' + encodeURIComponent(estado)
                })
                .then(res => res.text())
                .then(data => {
                    contenedor.innerHTML = data;

                    // Cargar los datos del usuario
                    setTimeout(() => {
                        cargarAcceso(userID);
                    }, 50);

                    // Inicializar el editor si existe el campo
                    const observacionesInput = document.querySelector('#observaciones');
                    if (observacionesInput) {
                        ClassicEditor
                            .create(observacionesInput)
                            .catch(error => {
                                console.error(error);
                            });
                    }

                    // Mostrar el modal (si aplica)
                    const modalAccesoElement = document.getElementById('modalAcceso');
                    if (modalAccesoElement) {
                        const modal = bootstrap.Modal.getOrCreateInstance(modalAccesoElement);
                        modal.show();
                    }
                })
                .catch(err => {
                    contenedor.innerHTML = '<div class="text-danger">Error al cargar el formulario.</div>';
                    console.error('Error AJAX:', err);
                });
        }
    });

    function cargarAcceso(userID) {

        const users = <?= json_encode($usuarios_general); ?>;

        console.log(users);

        const user = users.find(p => p.user_id == userID);

        if (!user) {
            console.error('No se encontró al usuario: ', userID);
            return;
        }

        document.getElementById('id').value = user.user_id;
        document.getElementById('name').value = user.emp_nombre + ' ' + user.emp_apellidos;
        document.getElementById('user').value = user.user_name;
        document.getElementById('acceso').value = user.user_nivelAcceso;

        console.log(document.getElementById('id'));
        console.log(document.getElementById('name'));
        console.log(document.getElementById('user'));
        console.log(document.getElementById('acceso'));

    }
</script>

<!-- Petición Ajax Eliminar Usuario -->
<script>
    document.addEventListener('click', function(e) {
        // Verificamos si el elemento clickeado es un botón de eliminación
        if (e.target.closest('.user_estado')) {
            const btn = e.target.closest('.user_estado'); // El botón de eliminación que fue clickeado
            const estado = btn.getAttribute('data-estado-estado');
            const id = btn.getAttribute('data-estado-id');
            const estado_cambiar = btn.getAttribute('data-estado');
            const user_name = btn.getAttribute('data-usuario');
            const contenedor = document.getElementById('formularioEstado');


            // Mostrar mensaje de carga
            contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

            // Hacer petición AJAX para cargar el formulario de eliminación
            fetch('ajax/formularios-usuarios.php', {
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
                    console.log(id, ' ', user_name, ' ', estado);
                    funcioneFormulario_estado(id, user_name, estado_cambiar);

                    // Mostrar el modal de eliminación
                    const modalEstadoElement = document.getElementById('modalEstado');
                    const modal = bootstrap.Modal.getOrCreateInstance(modalEstadoElement);
                    modal.show();
                })
                .catch(err => {
                    contenedor.innerHTML = '<div class="text-danger">Error al cargar el formulario.</div>';
                    console.error('Error AJAX:', err);
                });
        }
    });

    function funcioneFormulario_estado(id, username, estado_cambiar) {
        document.getElementById('eliminarId').value = id;
        document.getElementById('estado').value = estado_cambiar;
        document.getElementById('usernameEliminar').textContent = username;
    }
</script>

<!-- Petición Ajax Cambiar Contraseña -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Captura el ID del usuario al abrir el primer modal
        document.querySelectorAll('.user_pass').forEach(btn => {
            btn.addEventListener('click', () => {
                const userId = btn.dataset.id;
                document.getElementById('idUsuarioSeleccionado').value = userId;
            });
        });

        // Validación de la contraseña actual
        document.getElementById('formVerificarPass').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const pass = form.pass.value;
            const userId = form.id_usuario_seleccionado.value;
            const errorMsg = document.getElementById('errorVerificacion');
            const user = <?= json_encode($_SESSION['usuario']) ?>;

            fetch('ajax/verificar-password.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `clave=${encodeURIComponent(pass)}&user=${encodeURIComponent(user)}`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.ok) {
                        bootstrap.Modal.getInstance(document.getElementById('modalVerificarPass')).hide();
                        document.getElementById('userIdCambio').value = userId;
                        new bootstrap.Modal(document.getElementById('modalCambiarPass')).show();
                    } else {
                        errorMsg.textContent = "Contraseña incorrecta.";
                        errorMsg.classList.remove("d-none");
                    }
                }).catch(err => {
                    console.error("Error al procesar la respuesta:", err);
                    errorMsg.textContent = "Error inesperado.";
                    errorMsg.classList.remove("d-none");
                });
        });

        // Cambio de contraseña del usuario seleccionado
        // Cambio de contraseña del usuario seleccionado
        document.getElementById('formCambiarPass').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const pass1 = form.nueva_pass.value;
            const pass2 = form.confirmar_pass.value;
            const userId = form.userIdCambio.value;
            const msg = document.getElementById('mensajeCambioPass');

            // Validación de formato
            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{8,16}$/;

            if (pass1 !== pass2) {
                msg.textContent = "Las contraseñas no coinciden.";
                msg.classList.remove("d-none");
                return;
            }

            if (!regex.test(pass1)) {
                msg.textContent = "La contraseña debe tener entre 8 y 16 caracteres, e incluir mayúsculas, minúsculas, números y al menos un carácter especial.";
                msg.classList.remove("d-none");
                return;
            }

            // Envío si la validación pasó
            fetch('ajax/guardar-nueva-password.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id=${encodeURIComponent(userId)}&pass=${encodeURIComponent(pass1)}`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.ok) {
                        msg.classList.add("d-none");
                        bootstrap.Modal.getInstance(document.getElementById('modalCambiarPass')).hide();
                        alert("Contraseña cambiada correctamente");
                    } else {
                        msg.textContent = "Error al cambiar la contraseña.";
                        msg.classList.remove("d-none");
                    }
                });
        });

    })
</script>


<?php
// Incluir el footer
require_once 'layout/footer.php';
?>
<?php

include_once '../../controllers/usuariosController.php';

$controller = new usuariosController();
$usuarios = $controller->get_usuario_inactivo();


if (isset($_POST['estado'])) {
    $estado = $_POST['estado'];

    // Simulamos una base de datos o lógica
    switch ($estado) {

        case '32': // Usuarios Inactivos
?>
            <h6 class="indicador m-2 p-2"><b><i>Usuarios Inactivos</i></b></h6>

            <div class="row mb-3">
                <!-- Buscador al centro -->
                <div class="col-md-2 text-center contenedor-busqueda" id="contenedor-busqueda"></div>

                <!-- Botones + length en la misma columna -->
                <div class="col-md-2 text-end contenedor-botones">
                    <div id="contenedor-botones"></div>
                </div>
            </div>

            <table id="tablaAdvertencia" class="display mt-2" style="width: 100%;">
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
                                        <!-- Botón de dropdown -->
                                        <button class="dropdown-btn transparent-btn" type="button" title="More info" data-bs-toggle="dropdown" aria-expanded="false">
                                            <div class="sr-only">More info</div>
                                            <i class="bi bi-three-dots fs-5"></i>
                                        </button>
                                        <!-- Menú desplegable de acciones -->
                                        <ul class="users-item-dropdown dropdown-menu" id="desplegar">
                                            <li>
                                                <a class="user_estado" href="#" data-bs-toggle="modal" data-bs-target="#modalEstado" data-estado-estado="6" data-estado="31" data-usuario="<?= $user['user_name'] ?>" data-estado-id="<?= $user['user_id'] ?>">Activar</a>
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
        <?php
            break;
        
        default:
            echo 'Estado no reconocido';
            break;
    }
}

?>
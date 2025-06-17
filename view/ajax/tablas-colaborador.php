<?php

include_once '../../controllers/usuariosController.php';

$controller = new usuariosController();
$usuarios = $controller->get_usuario_inactivo();
$colabInactivo = $controller->get_colaborador_inactivo();
$colabDespedido = $controller->get_colaborador_despedido();

if (isset($_POST['estado'])) {
    $estado = $_POST['estado'];

    // Simulamos una base de datos o lógica
    switch ($estado) {

        case '29': // Usuarios Inactivos
?>
            <h6 class="indicador m-2 p-2"><b><i>Colaorador Inactivos</i></b></h6>

            <div class="row mb-3">
                <!-- Buscador al centro -->
                <div class="col-md-2 text-center contenedor-busqueda" id="contenedor-busqueda"></div>

                <!-- Botones + length en la misma columna -->
                <div class="col-md-2 text-end contenedor-botones">
                    <div id="contenedor-botones"></div>
                </div>
            </div>

            <table id="tablaInactivo" class="display mt-2" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Colaborador</th>
                        <th>Teléfono</th>
                        <th>Ingreso</th>
                        <th>Departamento</th>
                        <th>Observaciones</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (is_array($colabInactivo)): ?>
                        <?php foreach ($colabInactivo as $colab): ?>
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
                                <td data-label="Observaciones"><?= $colab['obe_observación'] ?></td>
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
        case '30': // Usuarios Inactivos
        ?>
            <h6 class="indicador m-2 p-2"><b><i>Colaoradores Despedidos</i></b></h6>

            <div class="row mb-3">
                <!-- Buscador al centro -->
                <div class="col-md-2 text-center contenedor-busqueda" id="contenedor-busqueda"></div>

                <!-- Botones + length en la misma columna -->
                <div class="col-md-2 text-end contenedor-botones">
                    <div id="contenedor-botones"></div>
                </div>
            </div>

            <table id="tablaDespedido" class="display mt-2" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Colaborador</th>
                        <th>Teléfono</th>
                        <th>Ingreso</th>
                        <th>Departamento</th>
                        <th>Observaciones</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (is_array($colabDespedido)): ?>
                        <?php foreach ($colabDespedido as $colab): ?>
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
                                <td data-label="Observaciones"><?= $colab['obe_observación'] ?></td>
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
<?php

include_once '../../controllers/supervisionController.php';

$controller = new supervisionController();



if (isset($_POST['estado'])) {
    $estado = $_POST['estado'];

    // Simulamos una base de datos o lógica
    switch ($estado) {

        case '24':
            $id = $_POST['id'];
            $detalles = $controller->get_reportes_general($id);
?>

            <div class="row mb-3">
                <!-- Buscador al centro -->
                <div class="col-md-2 text-center contenedor-busqueda" id="contenedor-busqueda"></div>

                <!-- Botones + length en la misma columna -->
                <div class="col-md-2 text-end contenedor-botones">
                    <div id="contenedor-botones"></div>
                </div>
            </div>

            <table id="tabla_detalles" class="display mt-2" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Colaborador</th>
                        <th>Motivo</th>
                        <th>Justificación</th>
                        <th>Bitacora</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (is_array($detalles)): ?>
                        <?php foreach ($detalles as $detalle): ?>
                            <tr>
                                <td data-label="Colaborador">
                                    <div>
                                        <span style="color: blue;"><?= $detalle['emp_cedula'] ?></span>
                                        <p><?= $detalle['emp_nombre'] . " " . $detalle['emp_apellidos'] ?></p>
                                    </div>
                                </td>
                                <td data-label="Motivo"><?= $detalle['reof_motivo'] ?></td>
                                <td data-label="Justificación"><?= $detalle['reof_justificacion'] ?></td>
                                <td data-label="Bitacora"><?= $detalle['reof_bitacora'] ?></td>
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
                                                <a class="editar" href="#" data-bs-toggle="modal" data-bs-target="#modalEditar" data-estado-editar="5" data-editar-id="<?= $detalle['reof_id'] ?>">Editar</a>
                                            </li>
                                            <li>
                                                <a class="eliminar" href="#" data-bs-toggle="modal" data-bs-target="#modalEliminar" data-estado-estado="6" data-nombre="<?= $detalle['emp_nombre']?>" data-eliminar-id="<?= $detalle['reof_id'] ?>">Eliminar</a>
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
<?php

include_once '../../controllers/seguridadCotroller.php';

$controller = new seguridadConroller();
$equipoAsignado = $controller->getEquipoAsignado();


if (isset($_POST['estado'])) {
    $estado = $_POST['estado'];

    // Simulamos una base de datos o lógica
    switch ($estado) {

        case '16': // Inv. Advertencia
?>
            <h6 class="indicador m-2 p-2"><b><i>Inventario de Seguridad Asignado.</i></b></h6>

            <div class="row mb-3">
                <!-- Buscador al centro -->
                <div class="col-md-2 text-center contenedor-busqueda" id="contenedor-busqueda"></div>

                <!-- Botones + length en la misma columna -->
                <div class="col-md-2 text-end contenedor-botones">
                    <div id="contenedor-botones"></div>
                </div>
            </div>

            <table id="tablaAsignado" class="display mt-2" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Detalle</th>
                        <th>Stock</th>
                        <th>Marca</th>
                        <th>Subcategoria</th>
                        <th>Asignado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (is_array($equipoAsignado)): ?>
                        <?php foreach ($equipoAsignado as $equip): ?>
                            <tr>
                                <td data-label="Stock"><?= htmlspecialchars($equip['scat_cantidad']) ?></td>
                                <td data-label="Detalle"><?= $equip['segd_detalle'] ?></td>
                                <td data-label="Condición"><?= htmlspecialchars($equip['segd_condicion']) ?></td>
                                <td data-label="Subcategoria"><?= $equip['scat_detalle'] ?></td>
                                <td data-label="Asignado"><?= $equip['emp_nombre'] ?></td>

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
                                                <a class="editar_equipo" href="#" data-bs-toggle="modal" data-bs-target="#modalEditar" data-estado-editar="5" data-editar-id="<?= $equip['segd_id'] ?>">Editar</a>
                                            </li>
                                            <li>
                                                <a class="eliminar_producto" href="#" data-bs-toggle="modal" data-bs-target="#modalEliminar" data-estado-eliminar="6" data-id="<?= $equip['segd_id'] ?>" data-codigo="<?= $equip['segd_detalle'] ?>">Eliminar</a>
                                            </li>
                                        </ul>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: '' ?>
                        <tr>
                            <td colspan="8">No hay productos para mostrar.</td>
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
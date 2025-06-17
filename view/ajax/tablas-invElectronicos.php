<?php

include_once '../../controllers/electronicoConroller.php';

$controller = new electronicoConroller();




if (isset($_POST['estado'])) {
    $estado = $_POST['estado'];

    // Simulamos una base de datos o lógica
    switch ($estado) {

        case '24':
            ?>
            <div class="row mb-3">
                <!-- Buscador al centro -->
                <div class="col-md-2 text-center contenedor-busqueda" id="contenedor-busqueda"></div>

                <!-- Botones + length en la misma columna -->
                <div class="col-md-2 text-end contenedor-botones">
                    <div id="contenedor-botones"></div>
                </div>
            </div>

            <table id="tabla-desagrupado" class="display mt-2" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Equipo</th>
                        <th>Stock</th>
                        <th>Factura</th>
                        <th>Compra</th>
                        <th>Venta</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $codigo = $_POST['codigo'];
                    $lista_equipos = $controller->getElectronicos_Todos($codigo);
                    ?>
                    <?php if (is_array($lista_equipos)): ?>
                        <?php foreach ($lista_equipos as $producto): ?>
                            <tr>
                                <td data-label="Equipo">
                                    <div>
                                        <span style="color: #007bff; font-weight: bold;"><?= $producto['elec_codigo'] ?></span><br>
                                        <span><?= $producto['elec_detalle'] ?></span>
                                    </div>
                                </td>
                                <td data-label="Stock"><?= htmlspecialchars($producto['elec_stok']) ?></td>
                                <td data-label="Factura"><?= htmlspecialchars($producto['elec_fact_consecutivo']) ?></td>
                                <td data-label="Compra">₡<?= number_format($producto['elec_precio_prov'], 2, ',', '.') ?></td>
                                <td data-label="Venta">₡<?= number_format($producto['elec_total'], 2, ',', '.') ?></td>
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
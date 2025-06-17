<?php

include_once '../../controllers/gestionInvController.php';
require_once '../../routes/rutas.php';

$controller = new gestionConroller();

$subCategoria = $controller->getSubcategoria();
$marcas = $controller->getMarca();
$proveedores = $controller->getProveedor();
$vehiculos = $controller->getVehiculo();

if (isset($_POST['estado'])) {
    $estado = $_POST['estado'];

    // Simulamos una base de datos o lógica
    switch ($estado) {

        case '18': // Subcategoria
            ?>
            <div class="row stat-cards">
                <div class="col-md-2 col-xl-3">
                    <!-- El estado 4 indica que es para agregar -->
                    <button class="nueva_categoria" data-bs-toggle="modal" data-bs-target="#modalNuevaHerramienta" data-estado-agregar="4" data-identificador-agregar="2" style="border: none; background: none;">
                        <article class="stat-cards-item">
                            <div class="icono_nuevo">
                                <i data-feather="plus" style="color: white;"></i>
                            </div>
                            <div class="stat-cards-info">
                                <p class="stat-cards-info__num m-2">Nueva Subcategoria</p>
                            </div>
                        </article>
                    </button>
                </div>
            </div>

            <hr class="line mt-1 mb-2 pb-2">

            <h6 class="indicador m-2 p-2"><b><i>Subcategorias.</i></b></h6>

            <div class="row mb-3">
                <!-- Buscador al centro -->
                <div class="col-md-2 text-center contenedor-busqueda" id="contenedor-busqueda"></div>

                <!-- Botones + length en la misma columna -->
                <div class="col-md-2 text-end contenedor-botones">
                    <div id="contenedor-botones"></div>
                </div>
            </div>

            <table id="tablaFiltro" class="display mt-2" style="width: 100%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Detalle</th>
                        <th>Cat Padre</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (is_array($subCategoria)): ?>
                        <?php foreach ($subCategoria as $cat): ?>
                            <tr>
                                <td data-label="ID"><?= $cat['scat_id'] ?></td>
                                <td data-label="Detalle"><?= $cat['scat_detalle'] ?></td>
                                <td data-label="Categoria"><?= $cat['categoria_nombre'] ?></td>
                                <td data-label="Estado"><?= $cat['estado_nombre'] ?></td>
                                <td class="text-center align-middle" data-label="Acciones">
                                    <div class="d-flex justify-content-center align-items-center gap-1">
                                        <button class="editar_categoria btn btn-sm btn-outline-primary" title="Editar"
                                            data-bs-toggle="modal" data-bs-target="#modalEditar"
                                            data-estado-editar="5" data-identificador-editar="2" data-editar-id="<?= $cat['scat_id'] ?>">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <button class="eliminar_categoria btn btn-sm btn-outline-danger" title="Eliminar"
                                            data-bs-toggle="modal" data-bs-target="#modalEliminar"
                                            data-estado-eliminar="6" data-identificador-eliminar="2" data-id="<?= $cat['scat_id'] ?>" data-codigo="<?= $cat['scat_detalle'] ?>">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No hay categorias para mostrar.</td>
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

        case '19': // Marcas
        ?>
            <div class="row stat-cards">
                <div class="col-md-2 col-xl-3">
                    <!-- El estado 4 indica que es para agregar -->
                    <button class="nueva_categoria" data-bs-toggle="modal" data-bs-target="#modalNuevaHerramienta" data-estado-agregar="4" data-identificador-agregar="3" style="border: none; background: none;">
                        <article class="stat-cards-item">
                            <div class="icono_nuevo">
                                <i data-feather="plus" style="color: white;"></i>
                            </div>
                            <div class="stat-cards-info">
                                <p class="stat-cards-info__num m-2">Nueva Marca</p>
                            </div>
                        </article>
                    </button>
                </div>
            </div>

            <hr class="line mt-1 mb-2 pb-2">

            <h6 class="indicador m-2 p-2"><b><i>Marcas</i></b></h6>

            <div class="row mb-3">
                <!-- Buscador al centro -->
                <div class="col-md-2 text-center contenedor-busqueda" id="contenedor-busqueda"></div>

                <!-- Botones + length en la misma columna -->
                <div class="col-md-2 text-end contenedor-botones">
                    <div id="contenedor-botones"></div>
                </div>
            </div>

            <table id="tablaFiltro" class="display mt-2" style="width: 100%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Detalle</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (is_array($marcas)): ?>
                        <?php foreach ($marcas as $marca): ?>
                            <tr>
                                <td data-label="ID"><?= $marca['marc_id'] ?></td>
                                <td data-label="Detalle"><?= $marca['marc_detalle'] ?></td>
                                <td data-label="Estado"><?= $marca['est_detalle'] ?></td>
                                <td class="text-center align-middle" data-label="Acciones">
                                    <div class="d-flex justify-content-center align-items-center gap-1">
                                        <button class="editar_categoria btn btn-sm btn-outline-primary" title="Editar"
                                            data-bs-toggle="modal" data-bs-target="#modalEditar"
                                            data-estado-editar="5" data-identificador-editar="3" data-editar-id="<?= $marca['marc_id'] ?>">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <button class="eliminar_categoria btn btn-sm btn-outline-danger" title="Eliminar"
                                            data-bs-toggle="modal" data-bs-target="#modalEliminar"
                                            data-estado-eliminar="6" data-identificador-eliminar="3" data-id="<?= $marca['marc_id'] ?>" data-codigo="<?= $marca['marc_detalle'] ?>">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No hay categorias para mostrar.</td>
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
        case '20': // Proveedores
            ?>
            <div class="row stat-cards">
                <div class="col-md-2 col-xl-3">
                    <!-- El estado 4 indica que es para agregar -->
                    <button class="nueva_categoria" data-bs-toggle="modal" data-bs-target="#modalNuevaHerramienta" data-estado-agregar="4" data-identificador-agregar="4" style="border: none; background: none;">
                        <article class="stat-cards-item">
                            <div class="icono_nuevo">
                                <i data-feather="plus" style="color: white;"></i>
                            </div>
                            <div class="stat-cards-info">
                                <p class="stat-cards-info__num m-2">Nuevo Proveedor</p>
                            </div>
                        </article>
                    </button>
                </div>
            </div>

            <hr class="line mt-1 mb-2 pb-2">

            <h6 class="indicador m-2 p-2"><b><i>Proveedores</i></b></h6>

            <div class="row mb-3">
                <!-- Buscador al centro -->
                <div class="col-md-2 text-center contenedor-busqueda" id="contenedor-busqueda"></div>

                <!-- Botones + length en la misma columna -->
                <div class="col-md-2 text-end contenedor-botones">
                    <div id="contenedor-botones"></div>
                </div>
            </div>

            <table id="tablaFiltro" class="display mt-2" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Empresa</th>
                        <th>Proveedor</th>
                        <th>Teléfono</th>
                        <th>Moneda</th>
                        <th>Pago</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (is_array($proveedores)): ?>
                        <?php foreach ($proveedores as $prov): ?>
                            <tr>
                                <!-- Empresa -->
                                <td data-label="Empresa">
                                    <div>
                                        <span style="color: #007bff; font-weight: bold;"><?= $prov['prov_identificacion'] ?></span><br>
                                        <span><?= $prov['prov_empresa'] ?></span>
                                    </div>
                                </td>

                                <!-- Proveedor -->
                                <td data-label="Proveedor"><?= $prov['prov_contacto_nombre'] ?></td>

                                <!-- Teléfono -->
                                <td data-label="Teléfono"><?= $prov['prov_contacto_telefono'] ?></td>

                                <!-- Moneda -->
                                <td data-label="Moneda"><?= $prov['prov_moneda_preferida'] ?></td>

                                <!-- Pago -->
                                <td data-label="Pago"><?= $prov['prov_condiciones_pago'] ?></td>

                                <!-- Acciones -->
                                <td class="text-center align-middle" data-label="Acciones">
                                    <div class="d-flex justify-content-center align-items-center gap-1">
                                        <button class="editar_categoria btn btn-sm btn-outline-primary" title="Editar"
                                            data-bs-toggle="modal" data-bs-target="#modalEditar"
                                            data-estado-editar="5" data-identificador-editar="4"
                                            data-editar-id="<?= $prov['prov_id'] ?>">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <button class="eliminar_categoria btn btn-sm btn-outline-danger" title="Eliminar"
                                            data-bs-toggle="modal" data-bs-target="#modalEliminar"
                                            data-estado-eliminar="6" data-identificador-eliminar="4"
                                            data-id="<?= $prov['prov_id'] ?>" data-codigo="<?= $prov['prov_empresa'] ?>">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No hay proveedores para mostrar.</td>
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
        case '21': // Busetas
            ?>
            <div class="row stat-cards">
                <div class="col-md-2 col-xl-3">
                    <!-- El estado 4 indica que es para agregar -->
                    <button class="nueva_categoria" data-bs-toggle="modal" data-bs-target="#modalNuevaHerramienta" data-estado-agregar="4" data-identificador-agregar="5" style="border: none; background: none;">
                        <article class="stat-cards-item">
                            <div class="icono_nuevo">
                                <i data-feather="plus" style="color: white;"></i>
                            </div>
                            <div class="stat-cards-info">
                                <p class="stat-cards-info__num m-2">Nueva Buseta</p>
                            </div>
                        </article>
                    </button>
                </div>
            </div>

            <hr class="line mt-1 mb-2 pb-2">

            <h6 class="indicador m-2 p-2"><b><i>Busetas</i></b></h6>

            <div class="row mb-3">
                <!-- Buscador al centro -->
                <div class="col-md-2 text-center contenedor-busqueda" id="contenedor-busqueda"></div>

                <!-- Botones + length en la misma columna -->
                <div class="col-md-2 text-end contenedor-botones">
                    <div id="contenedor-botones"></div>
                </div>
            </div>

            <table id="tablaFiltro" class="display mt-2" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Placa</th>
                        <th>Modelo</th>
                        <th>Año</th>
                        <th>Kilometraje</th>
                        <th>Fecha Seguro</th>
                        <th>Fecha Revisión</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (is_array($vehiculos)): ?>
                        <?php foreach ($vehiculos as $veh): ?>
                            <tr>
                                <td data-label="Placa"><?= $veh['veh_placa'] ?></td>
                                <td data-label="Modelo">
                                    <div>
                                        <span style="color: #007bff; font-weight: bold;"><?= $veh['veh_marca'] ?></span><br>
                                        <span><?= $veh['veh_modelo'] ?></span>
                                    </div>
                                </td>
                                <td data-label="Año"><?= $veh['veh_anio'] ?></td>
                                <td data-label="Kilometraje"><?= $veh['veh_kilometraje'] ?></td>
                                <td data-label="Fecha Seguro"><?= $veh['veh_fecha_vencimiento_seguro'] ?></td>
                                <td data-label="Fecha Revisión"><?= $veh['veh_fecha_revision'] ?></td>

                                <!-- Acciones -->
                                <td class="text-center align-middle" data-label="Acciones">
                                    <div class="d-flex justify-content-center align-items-center gap-1">
                                        <button class="editar_categoria btn btn-sm btn-outline-primary" title="Editar"
                                            data-bs-toggle="modal" data-bs-target="#modalEditar"
                                            data-estado-editar="5" data-identificador-editar="5"
                                            data-editar-id="<?= $veh['veh_id'] ?>">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <button class="eliminar_categoria btn btn-sm btn-outline-danger" title="Eliminar"
                                            data-bs-toggle="modal" data-bs-target="#modalEliminar"
                                            data-estado-eliminar="6" data-identificador-eliminar="5"
                                            data-id="<?= $veh['veh_id'] ?>" data-codigo="<?= $veh['veh_placa'] ?>">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No hay proveedores para mostrar.</td>
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
<?php
include_once '../../controllers/gestionInvController.php';

// Instancias
$controller = new gestionConroller();

$estados = $controller->getEstados();
$categorias = $controller->getCategorias();

$estadosCategoria = [];
$estadosSubcategoria = [];

foreach ($estados as $esta) {
    if (!empty($esta)) {
        $detalle = strtolower($esta['est_detalle']);
        if (strpos($detalle, 'categoria') !== false && strpos($detalle, 'subcategoria') === false) {
            $estadosCategoria[] = $esta;
        }
    }
}

foreach ($estados as $esta) {
    if (!empty($esta)) {
        $detalle = strtolower($esta['est_detalle']);
        if (preg_match('/\bsubcategoria\b/i', $esta['est_detalle'])) {
            $estadosSubcategoria[] = $esta;
        }
    }
}

foreach ($estados as $esta) {
    if (!empty($esta)) {
        $detalle = strtolower($esta['est_detalle']);
        if (strpos($detalle, 'marca') !== false) {
            $estadosMarca[] = $esta;
        }
    }
}


if (isset($_POST['estado']) && isset($_POST['identificador'])) {

    $estado = $_POST['estado'];
    $identificador = $_POST['identificador'];

    if ($identificador == 1) { // Categorias
        switch ($estado) {
            case '4': // Agregar
?>
                <form action="" method="post">
                    <div class="modal-body">

                        <div class="row g-2">
                            <div class="col-12 col-sm-6 col-md-6">
                                <label for="detalle" class="form-label">Detalle</label>
                                <input type="text" class="form-control" id="detalle" name="detalle">
                            </div>

                            <div class="col-12 col-sm-6">
                                <label for="estado" class="form-label">Estado</label>
                                <?php if ($estadosCategoria != 'error'): ?>
                                    <select class="form-select" name="estado" id="estado">
                                        <option value="">Seleccione una opción</option>
                                        <?php foreach ($estadosCategoria as $estados): ?>
                                            <option value="<?= $estados['est_id'] ?>">
                                                <?= $estados['est_detalle'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php else: ?>
                                    <p>No se encontraron categorias disponibles.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer py-1 px-2">
                        <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="nuevo" class="btn btn-success fs-6">Guardar</button>
                    </div>
                </form>
            <?php
                break;

            case '5': // Editar
            ?>
                <form action="" method="post">
                    <div class="modal-body">

                        <input type="hidden" name="id" id="id">

                        <div class="row g-2">
                            <div class="col-12 col-sm-6 col-md-6">
                                <label for="detalle" class="form-label">Detalle</label>
                                <input type="text" class="form-control" id="detalle" name="detalle">
                            </div>

                            <div class="col-12 col-sm-6">
                                <label for="estado" class="form-label">Estado</label>
                                <?php if ($estadosCategoria != 'error'): ?>
                                    <select class="form-select" name="estado" id="estado">
                                        <option value="">Seleccione una opción</option>
                                        <?php foreach ($estadosCategoria as $estados): ?>
                                            <option value="<?= $estados['est_id'] ?>">
                                                <?= $estados['est_detalle'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php else: ?>
                                    <p>No se encontraron categorias disponibles.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer py-1 px-2">
                        <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="editar" class="btn btn-success fs-6">Guardar</button>
                    </div>
                </form>
            <?php
                break;
            case '6': // Eliminar
            ?>
                <form action="" method="post">
                    <div class="modal-body m-2 p-2">
                        <p class="mb-3">¿Desea eliminar la siguiente categoria <br>
                            <strong id="codigoEliminarTexto">#CODIGO</strong>?
                        </p>
                        <input type="hidden" id="eliminarId" name="id">
                    </div>
                    <div class="modal-footer py-1 px-2">
                        <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="eliminar" class="btn btn-danger fs-6">Eliminar</button>
                    </div>
                </form>
            <?php
                break;
        }
    } elseif ($identificador == 2) { // Subcategorias
        switch ($estado) {
            case '4': // Agregar
            ?>
                <form action="" method="post">
                    <div class="modal-body">

                        <div class="row g-2">
                            <div class="col-12 col-sm-12 col-md-12">
                                <label for="detalle_subcategoria" class="form-label">Detalle</label>
                                <input type="text" class="form-control" id="detalle_subcategoria" name="detalle_subcategoria">
                            </div>

                            <div class="col-12 col-sm-6">
                                <label for="cat_padre_subcategoria" class="form-label">Categoría Padre</label>
                                <?php if ($categorias != 'error'): ?>
                                    <select class="form-select" name="cat_padre_subcategoria" id="cat_padre_subcategoria">
                                        <option value="">Seleccione una opción</option>
                                        <?php foreach ($categorias as $categoria): ?>
                                            <option value="<?= $categoria['catg_id'] ?>">
                                                <?= $categoria['catg_detalle'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php else: ?>
                                    <p>No se encontraron categorias disponibles.</p>
                                <?php endif; ?>
                            </div>

                            <div class="col-12 col-sm-6">
                                <label for="estado_subcategoria" class="form-label">Estado</label>
                                <?php if ($estadosSubcategoria != 'error'): ?>
                                    <select class="form-select" name="estado_subcategoria" id="estado_subcategoria">
                                        <option value="">Seleccione una opción</option>
                                        <?php foreach ($estadosSubcategoria as $estados): ?>
                                            <option value="<?= $estados['est_id'] ?>">
                                                <?= $estados['est_detalle'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php else: ?>
                                    <p>No se encontraron estados disponibles.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer py-1 px-2">
                        <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="nuevo_subcategoria" class="btn btn-success fs-6">Guardar</button>
                    </div>
                </form>
            <?php
                break;

            case '5': // Editar
            ?>
                <form action="" method="post">
                    <div class="modal-body">

                        <input type="hidden" name="id" id="id">

                        <div class="row g-2">
                            <div class="col-12 col-sm-12 col-md-12">
                                <label for="detalle_subcategoria" class="form-label">Detalle</label>
                                <input type="text" class="form-control" id="detalle_subcategoria" name="detalle_subcategoria">
                            </div>

                            <div class="col-12 col-sm-6">
                                <label for="cat_padre_subcategoria" class="form-label">Categoría Padre</label>
                                <?php if ($categorias != 'error'): ?>
                                    <select class="form-select" name="cat_padre_subcategoria" id="cat_padre_subcategoria">
                                        <option value="">Seleccione una opción</option>
                                        <?php foreach ($categorias as $categoria): ?>
                                            <option value="<?= $categoria['catg_id'] ?>">
                                                <?= $categoria['catg_detalle'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php else: ?>
                                    <p>No se encontraron categorias disponibles.</p>
                                <?php endif; ?>
                            </div>

                            <div class="col-12 col-sm-6">
                                <label for="estado_subcategoria" class="form-label">Estado</label>
                                <?php if ($estadosSubcategoria != 'error'): ?>
                                    <select class="form-select" name="estado_subcategoria" id="estado_subcategoria">
                                        <option value="">Seleccione una opción</option>
                                        <?php foreach ($estadosSubcategoria as $estados): ?>
                                            <option value="<?= $estados['est_id'] ?>">
                                                <?= $estados['est_detalle'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php else: ?>
                                    <p>No se encontraron estados disponibles.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer py-1 px-2">
                        <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="editar_subcategoria" class="btn btn-success fs-6">Guardar</button>
                    </div>
                </form>
            <?php
                break;
            case '6': // Eliminar
            ?>
                <form action="" method="post">
                    <div class="modal-body m-2 p-2">
                        <p class="mb-3">¿Desea eliminar la siguiente categoria <br>
                            <strong id="codigoEliminarTexto">#CODIGO</strong>?
                        </p>
                        <input type="hidden" id="eliminarId" name="id">
                    </div>
                    <div class="modal-footer py-1 px-2">
                        <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="eliminar" class="btn btn-danger fs-6">Eliminar</button>
                    </div>
                </form>
            <?php
                break;
        }
    } elseif ($identificador == 3) { // Marcas
        switch ($estado) {
            case '4': // Agregar
            ?>
                <form action="" method="post">
                    <div class="modal-body">

                        <div class="row g-2">
                            <div class="col-12 col-sm-6 col-md-6">
                                <label for="detalle_marca" class="form-label">Detalle</label>
                                <input type="text" class="form-control" id="detalle_marca" name="detalle_marca">
                            </div>

                            <div class="col-6 col-sm-6">
                                <label for="estado_marca" class="form-label">Estado</label>
                                <?php if ($estadosMarca != 'error'): ?>
                                    <select class="form-select" name="estado_marca" id="estado_marca">
                                        <option value="">Seleccione una opción</option>
                                        <?php foreach ($estadosMarca as $marca): ?>
                                            <option value="<?= $marca['est_id'] ?>">
                                                <?= $marca['est_detalle'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php else: ?>
                                    <p>No se encontraron estados disponibles.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer py-1 px-2">
                        <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="nuevo_marca" class="btn btn-success fs-6">Guardar</button>
                    </div>
                </form>
            <?php
                break;

            case '5': // Editar
            ?>
                <form action="" method="post">
                    <div class="modal-body">

                        <input type="hidden" name="id" id="id">

                        <div class="row g-2">
                            <div class="col-12 col-sm-6 col-md-6">
                                <label for="detalle_marca" class="form-label">Detalle</label>
                                <input type="text" class="form-control" id="detalle_marca" name="detalle_marca">
                            </div>

                            <div class="col-6 col-sm-6">
                                <label for="estado_marca" class="form-label">Estado</label>
                                <?php if ($estadosMarca != 'error'): ?>
                                    <select class="form-select" name="estado_marca" id="estado_marca">
                                        <option value="">Seleccione una opción</option>
                                        <?php foreach ($estadosMarca as $marca): ?>
                                            <option value="<?= $marca['est_id'] ?>">
                                                <?= $marca['est_detalle'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php else: ?>
                                    <p>No se encontraron estados disponibles.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer py-1 px-2">
                        <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="editar_marca" class="btn btn-success fs-6">Guardar</button>
                    </div>
                </form>
            <?php
                break;
            case '6': // Eliminar
            ?>
                <form action="" method="post">
                    <div class="modal-body m-2 p-2">
                        <p class="mb-3">¿Desea eliminar la siguiente marca <br>
                            <strong id="codigoEliminarTexto">#CODIGO</strong>?
                        </p>
                        <input type="hidden" id="eliminarId" name="id">
                    </div>
                    <div class="modal-footer py-1 px-2">
                        <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="eliminar_marca" class="btn btn-danger fs-6">Eliminar</button>
                    </div>
                </form>
            <?php
                break;
        }
    } elseif ($identificador == 4) { // Proveedores
        switch ($estado) {
            case '4': // Agregar
                ?>
                <form action="" method="post">
                    <div class="modal-body">

                        <div class="row g-2">
                            <div class="col-12 col-sm-7 col-md-7">
                                <label for="nombre_empr" class="form-label">Nombre de la Empresa</label>
                                <input type="text" class="form-control" id="nombre_empr" name="nombre_empr">
                            </div>

                            <div class="col-12 col-sm-5 col-md-5">
                                <label for="cedula_empr" class="form-label">Cédula Fisica o Jurídica</label>
                                <input type="text" class="form-control" id="cedula_empr" name="cedula_empr">
                            </div>

                            <div class="col-12 col-sm-7 col-md-7">
                                <label for="correo_empr" class="form-label">Correo</label>
                                <input type="text" class="form-control" id="correo_empr" name="correo_empr">
                            </div>

                            <div class="col-12 col-sm-5 col-md-5">
                                <label for="condiciones_pago" class="form-label">Condiciones Pago</label>
                                <select class="form-select" name="condiciones_pago" id="condiciones_pago">
                                    <option value="">Seleccione una opción</option>
                                    <option value="contado">Contado</option>
                                    <option value="credito">Crédito</option>
                                </select>
                            </div>

                            <div class="col-12 col-sm-12 col-md-12">
                                <label for="ubicacion" class="form-label">Ubicación</label>
                                <input type="text" class="form-control" id="ubicacion" name="ubicacion">
                            </div>

                            <div class="col-12 col-sm-7 col-md-7">
                                <label for="nombre_prov" class="form-label">Nombre Proveedor</label>
                                <input type="text" class="form-control" id="nombre_prov" name="nombre_prov">
                            </div>

                            <div class="col-12 col-sm-5 col-md-5">
                                <label for="telefono_prov" class="form-label">Teléfono Proveedor</label>
                                <input type="text" class="form-control" id="telefono_prov" name="telefono_prov">
                            </div>

                            <div class="col-12 col-sm-7 col-md-7">
                                <label for="correo_prov" class="form-label">Correo Proveedor</label>
                                <input type="text" class="form-control" id="correo_prov" name="correo_prov">
                            </div>

                            <div class="col-12 col-sm-5 col-md-5">
                                <label for="moneda" class="form-label">Moneda</label>
                                <select class="form-select" name="moneda" id="moneda">
                                    <option value="">Seleccione una opción</option>
                                    <option value="colones">Colones</option>
                                    <option value="dolares">Dolares</option>
                                </select>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer py-1 px-2">
                        <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="nuevo_prov" class="btn btn-success fs-6">Guardar</button>
                    </div>
                </form>
            <?php
                break;

            case '5': // Editar
            ?>
                <form action="" method="post">
                    <div class="modal-body">

                        <input type="hidden" name="id" id="id">

                        <div class="row g-2">
                            <div class="col-12 col-sm-7 col-md-7">
                                <label for="nombre_empr" class="form-label">Nombre de la Empresa</label>
                                <input type="text" class="form-control" id="nombre_empr" name="nombre_empr">
                            </div>

                            <div class="col-12 col-sm-5 col-md-5">
                                <label for="cedula_empr" class="form-label">Cédula Fisica o Jurídica</label>
                                <input type="text" class="form-control" id="cedula_empr" name="cedula_empr">
                            </div>

                            <div class="col-12 col-sm-7 col-md-7">
                                <label for="correo_empr" class="form-label">Correo</label>
                                <input type="text" class="form-control" id="correo_empr" name="correo_empr">
                            </div>

                            <div class="col-12 col-sm-5 col-md-5">
                                <label for="condiciones_pago" class="form-label">Condiciones Pago</label>
                                <select class="form-select" name="condiciones_pago" id="condiciones_pago">
                                    <option value="">Seleccione una opción</option>
                                    <option value="contado">Contado</option>
                                    <option value="credito">Crédito</option>
                                </select>
                            </div>

                            <div class="col-12 col-sm-12 col-md-12">
                                <label for="ubicacion" class="form-label">Ubicación</label>
                                <input type="text" class="form-control" id="ubicacion" name="ubicacion">
                            </div>

                            <div class="col-12 col-sm-7 col-md-7">
                                <label for="nombre_prov" class="form-label">Nombre Proveedor</label>
                                <input type="text" class="form-control" id="nombre_prov" name="nombre_prov">
                            </div>

                            <div class="col-12 col-sm-5 col-md-5">
                                <label for="telefono_prov" class="form-label">Teléfono Proveedor</label>
                                <input type="text" class="form-control" id="telefono_prov" name="telefono_prov">
                            </div>

                            <div class="col-12 col-sm-7 col-md-7">
                                <label for="correo_prov" class="form-label">Correo Proveedor</label>
                                <input type="text" class="form-control" id="correo_prov" name="correo_prov">
                            </div>

                            <div class="col-12 col-sm-5 col-md-5">
                                <label for="moneda" class="form-label">Moneda</label>
                                <select class="form-select" name="moneda" id="moneda">
                                    <option value="">Seleccione una opción</option>
                                    <option value="colones">Colones</option>
                                    <option value="dolares">Dolares</option>
                                </select>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer py-1 px-2">
                        <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="editar_prov" class="btn btn-success fs-6">Guardar</button>
                    </div>
                </form>
            <?php
                break;
            case '6': // Eliminar
            ?>
                <form action="" method="post">
                    <div class="modal-body m-2 p-2">
                        <p class="mb-3">¿Desea eliminar el siguiente proveedor <br>
                            <strong id="codigoEliminarTexto">#CODIGO</strong>?
                        </p>
                        <input type="hidden" id="eliminarId" name="id">
                    </div>
                    <div class="modal-footer py-1 px-2">
                        <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="eliminar_marca" class="btn btn-danger fs-6">Eliminar</button>
                    </div>
                </form>
            <?php
                break;
        }
    } elseif ($identificador == 5) {
        switch ($estado) {
            case '4': // Agregar
                ?>
                <form action="" method="post">
                    <div class="modal-body">

                        <input type="hidden" name="id" id="id">

                        <div class="row g-2">
                            <div class="col-12 col-sm-3 col-md-3">
                                <label for="placa" class="form-label">Placa</label>
                                <input type="text" class="form-control" id="placa" name="placa">
                            </div>

                            <div class="col-12 col-sm-3 col-md-3">
                                <label for="marca" class="form-label">Marca</label>
                                <input type="text" class="form-control" id="marca" name="marca">
                            </div>

                            <div class="col-12 col-sm-3 col-md-3">
                                <label for="modelo" class="form-label">Modelo</label>
                                <input type="text" class="form-control" id="modelo" name="modelo">
                            </div>

                            <div class="col-12 col-sm-3 col-md-3">
                                <label for="anio_fabrica" class="form-label">Año de Fabricación</label>
                                <input type="text" class="form-control" id="anio_fabrica" name="anio_fabrica">
                            </div>

                            <div class="col-12 col-sm-3 col-md-3">
                                <label for="tipo_vehiculo" class="form-label">Tipo de Vehículo</label>
                                <select class="form-select" name="tipo_vehiculo" id="tipo_vehiculo">
                                    <option value="">Seleccione</option>
                                    <option value="1">Buseta</option>
                                    <option value="2">Automóvil</option>
                                    <option value="3">Pick-Up</option>
                                    <option value="4">Camión</option>
                                    <option value="5">Motocicleta</option>
                                    <option value="6">Cuadraciclo</option>
                                </select>
                            </div>

                            <div class="col-12 col-sm-4 col-md-4">
                                <label for="num_chasis" class="form-label">Num. Chasis</label>
                                <input type="text" class="form-control" id="num_chasis" name="num_chasis">
                            </div>

                            <div class="col-12 col-sm-5 col-md-5">
                                <label for="num_motor" class="form-label">Num. Motor</label>
                                <input type="text" class="form-control" id="num_motor" name="num_motor">
                            </div>

                            <div class="col-12 col-sm-4 col-md-4">
                                <label for="KM_vehicuo" class="form-label">Kilometraje</label>
                                <input type="text" class="form-control" id="KM_vehicuo" name="KM_vehicuo">
                            </div>

                            <div class="col-12 col-sm-4 col-md-4">
                                <label for="fecha_seguro" class="form-label">Seguro</label>
                                <input type="text" class="form-control" id="fecha_seguro" name="fecha_seguro">
                            </div>

                            <div class="col-12 col-sm-4 col-md-4">
                                <label for="fecha_revision" class="form-label">Revisión Técnica</label>
                                <input type="text" class="form-control" id="fecha_revision" name="fecha_revision">
                            </div>

                            <div class="col-12 col-sm-12 col-md-12">
                                <label for="observaciones" class="form-label">Observaciones</label>
                                <textarea class="form-control" id="observaciones" name="observaciones"></textarea>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer py-1 px-2">
                        <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="nuevo_vehiculo" class="btn btn-success fs-6">Guardar</button>
                    </div>
                </form>

                <?php
                break;
            case '5': // Editar
            ?>
                <form action="" method="post">
                    <div class="modal-body">

                        <input type="hidden" name="id" id="id">

                        <div class="row g-2">
                            <div class="col-12 col-sm-3 col-md-3">
                                <label for="placa" class="form-label">Placa</label>
                                <input type="text" class="form-control" id="placa" name="placa">
                            </div>

                            <div class="col-12 col-sm-3 col-md-3">
                                <label for="marca" class="form-label">Marca</label>
                                <input type="text" class="form-control" id="marca" name="marca">
                            </div>

                            <div class="col-12 col-sm-3 col-md-3">
                                <label for="modelo" class="form-label">Modelo</label>
                                <input type="text" class="form-control" id="modelo" name="modelo">
                            </div>

                            <div class="col-12 col-sm-3 col-md-3">
                                <label for="anio_fabrica" class="form-label">Año de Fabricación</label>
                                <input type="text" class="form-control" id="anio_fabrica" name="anio_fabrica">
                            </div>

                            <div class="col-12 col-sm-3 col-md-3">
                                <label for="tipo_vehiculo" class="form-label">Tipo de Vehículo</label>
                                <select class="form-select" name="tipo_vehiculo" id="tipo_vehiculo">
                                    <option value="">Seleccione</option>
                                    <option value="1">Buseta</option>
                                    <option value="2">Automóvil</option>
                                    <option value="3">Pick-Up</option>
                                    <option value="4">Camión</option>
                                    <option value="5">Motocicleta</option>
                                    <option value="6">Cuadraciclo</option>
                                </select>
                            </div>

                            <div class="col-12 col-sm-4 col-md-4">
                                <label for="num_chasis" class="form-label">Num. Chasis</label>
                                <input type="text" class="form-control" id="num_chasis" name="num_chasis">
                            </div>

                            <div class="col-12 col-sm-5 col-md-5">
                                <label for="num_motor" class="form-label">Num. Motor</label>
                                <input type="text" class="form-control" id="num_motor" name="num_motor">
                            </div>

                            <div class="col-12 col-sm-4 col-md-4">
                                <label for="KM_vehicuo" class="form-label">Kilometraje</label>
                                <input type="text" class="form-control" id="KM_vehicuo" name="KM_vehicuo">
                            </div>

                            <div class="col-12 col-sm-4 col-md-4">
                                <label for="fecha_seguro" class="form-label">Seguro</label>
                                <input type="text" class="form-control" id="fecha_seguro" name="fecha_seguro">
                            </div>

                            <div class="col-12 col-sm-4 col-md-4">
                                <label for="fecha_revision" class="form-label">Revisión Técnica</label>
                                <input type="text" class="form-control" id="fecha_revision" name="fecha_revision">
                            </div>

                            <div class="col-12 col-sm-12 col-md-12">
                                <label for="observaciones" class="form-label">Observaciones</label>
                                <textarea class="form-control" id="observaciones" name="observaciones"></textarea>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer py-1 px-2">
                        <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="editar_vehiculo" class="btn btn-success fs-6">Guardar</button>
                    </div>
                </form>
            <?php
                break;
            case '6': // Eliminar
            ?>
                <form action="" method="post">
                    <div class="modal-body m-2 p-2">
                        <p class="mb-3">¿Desea eliminar el siguiente vehículo <br>
                            <strong id="codigoEliminarTexto">#CODIGO</strong>?
                        </p>
                        <input type="hidden" id="eliminarId" name="id">
                    </div>
                    <div class="modal-footer py-1 px-2">
                        <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="eliminar_vehiculo" class="btn btn-danger fs-6">Eliminar</button>
                    </div>
                </form>
<?php
                break;

            default:
                # code...
                break;
        }
    }
}


?>
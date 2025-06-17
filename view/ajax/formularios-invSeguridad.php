<?php
include_once '../../controllers/seguridadCotroller.php';

// Instancias
$controller = new seguridadConroller();
$listaColaboradores = $controller->getColaboradores();
$listaCategorias = $controller->getCategoria();


if (isset($_POST['estado'])) {
    $estado = $_POST['estado'];

    switch ($estado) {
        case '4': // Agregar
?>
            <form action="" method="post">
                <div class="modal-body">

                    <div class="row g-2">
                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="stock" name="stock">
                        </div>

                        <div class="col-12 col-sm-4">
                            <label for="condicion" class="form-label">Condición</label>
                            <select class="form-select" name="condicion" id="condicion">
                                <option value="">Seleccione una opción</option>
                                <option value="Nuevo">Nuevo</option>
                                <option value="Usado">Usado</option>
                                <option value="Dañado">Dañado</option>
                            </select>
                        </div>

                        <div class="col-12 col-sm-4">
                            <label for="categoria" class="form-label">Categoría</label>
                            <?php if ($listaCategorias != 'error'): ?>
                                <select class="form-select" name="categoria" id="categoria">
                                    <option value="">Seleccione una opción</option>
                                    <?php foreach ($listaCategorias as $categoria): ?>
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
                            <label for="subcategoria" class="form-label">Subcategoría</label>
                            <select class="form-select" name="subcategoria" id="subcategoria">
                                <option value="">Seleccione una opción</option>
                            </select>
                        </div>

                        <div class="col-12 col-sm-6">
                            <label for="colaborador" class="form-label">Colaborador</label>
                            <?php if ($listaColaboradores != 'error'): ?>
                                <select class="form-select" name="colaborador" id="colaborador">
                                    <option value="">Seleccione una opción</option>
                                    <option value="0">Sin Asignar</option>
                                    <?php foreach ($listaColaboradores as $colaborador): ?>
                                        <option value="<?= $colaborador['emp_id'] ?>">
                                            <?= $colaborador['emp_nombre'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <p>No se encontraron colaboradores disponibles.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-12 col-sm-6 col-md-12">
                            <label for="detalle" class="form-label">Detalle</label>
                            <input type="text" class="form-control" id="detallHerramienta" name="detalle">
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
                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="stock" name="stock">
                        </div>

                        <div class="col-12 col-sm-4">
                            <label for="condicion" class="form-label">Condición</label>
                            <select class="form-select" name="condicion" id="condicion">
                                <option value="">Seleccione una opción</option>
                                <option value="Nuevo">Nuevo</option>
                                <option value="Usado">Usado</option>
                                <option value="Dañado">Dañado</option>
                            </select>
                        </div>

                        <div class="col-12 col-sm-4">
                            <label for="categoria" class="form-label">Categoría</label>
                            <?php if ($listaCategorias != 'error'): ?>
                                <select class="form-select" name="categoria" id="categoria">
                                    <option value="">Seleccione una opción</option>
                                    <?php foreach ($listaCategorias as $categoria): ?>
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
                            <label for="subcategoria" class="form-label">Subcategoría</label>
                            <select class="form-select" name="subcategoria" id="subcategoria">
                                <option value="">Seleccione una opción</option>
                            </select>
                        </div>

                        <div class="col-12 col-sm-6">
                            <label for="colaborador" class="form-label">Colaborador</label>
                            <?php if ($listaColaboradores != 'error'): ?>
                                <select class="form-select" name="colaborador" id="colaborador">
                                    <option value="">Seleccione una opción</option>
                                    <option value="0">Sin Asignar</option>
                                    <?php foreach ($listaColaboradores as $colaborador): ?>
                                        <option value="<?= $colaborador['emp_id'] ?>">
                                            <?= $colaborador['emp_nombre'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <p>No se encontraron colaboradores disponibles.</p>
                            <?php endif; ?>
                        </div>

                        <div class="col-12 col-sm-6 col-md-12">
                            <label for="detalle" class="form-label">Detalle</label>
                            <input type="text" class="form-control" id="detallHerramienta" name="detalle">
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
                    <p class="mb-3">¿Desea eliminar el siguiente equipo <br>
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
}



?>
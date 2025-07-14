<?php
include_once '../../controllers/electronicoConroller.php';

// Instancias
$controller = new electronicoConroller();
$listaProveedores = $controller->getProveedor();
$listaCategorias = $controller->getCategoria();
$listaSubCategorias = $controller->getsubCategoria();
$listaMarcas = $controller->getMarcas();

if (isset($_POST['estado'])) {
    $estado = $_POST['estado'];

    switch ($estado) {
        case '4': // Agregar
?>
            <form action="" method="post">
                <div class="modal-body">

                    <div class="row g-2">
                        <div class="col-12 col-sm-6 col-md-7">
                            <label for="detalle" class="form-label">Nombre del Equipo</label>
                            <input type="text" class="form-control" id="detalle" name="detalle">
                        </div>

                        <div class="col-12 col-sm-6 col-md-5">
                            <label for="codigo" class="form-label">Código</label>
                            <input type="text" class="form-control" id="codigo" name="codigo">
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="stock" name="stock">
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="limite" class="form-label">Límite</label>
                            <input type="number" class="form-control" id="limite" name="limite">
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="buffer" class="form-label">Buffer</label>
                            <input type="number" class="form-control" id="buffer" name="buffer">
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="marca" class="form-label">Marca</label>
                            <input type="text" class="form-control" id="marca" list="listaMarcas" name="marca">
                            <datalist id="listaMarcas">
                                <?php foreach ($listaMarcas as $marca): ?>
                                    <option value="<?= $marca['marc_detalle'] ?>">
                                    <?php endforeach; ?>
                            </datalist>
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
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

                        <div class="col-12 col-sm-4">
                            <label for="subcategoria" class="form-label">Subcategoría</label>
                            <?php if ($listaSubCategorias != 'error'): ?>
                                <select class="form-select" name="subcategoria" id="subcategoria">
                                    <option value="">Seleccione una opción</option>
                                    <?php foreach ($listaSubCategorias as $subCategoria): ?>
                                        <option value="<?= $subCategoria['scat_id'] ?>">
                                            <?= $subCategoria['scat_detalle'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <p>No se encontraron subCategorias disponibles.</p>
                            <?php endif; ?>
                        </div>

                        <div class="col-12 col-sm-6 col-md-6">
                            <label for="proveedor" class="form-label">Proveedor</label>
                            <?php if ($listaProveedores != 'error'): ?>
                                <select class="form-select" name="proveedor" id="proveedor">
                                    <option value="">Seleccione una opción</option>
                                    <?php foreach ($listaProveedores as $proveedor): ?>
                                        <option value="<?= $proveedor['prov_id'] ?>">
                                            <?= $proveedor['prov_empresa'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <p>No se encontraron proveedores disponibles.</p>
                            <?php endif; ?>
                        </div>

                        <div class="col-12 col-sm-6 col-md-6">
                            <label for="consecutivo" class="form-label">Facrura</label>
                            <input type="number" class="form-control" id="consecutivo" name="consecutivo">
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="compra" class="form-label">Compra</label>
                            <input type="text" class="form-control" id="compra" name="compra">
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="utilidad" class="form-label">Utilidad</label>
                            <input type="text" class="form-control" id="utilidad" name="utilidad">
                        </div>

                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="venta" class="form-label">Venta</label>
                            <input type="text" class="form-control" id="venta" name="venta">
                        </div>

                    </div>
                </div>

                <div class="modal-footer py-1 px-2">
                    <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="nuevo" class="btn btn-success fs-6">Agregar</button>
                </div>
            </form>
        <?php
            break;

        case '5': // Editar
        ?>
            <form action="" method="post">
                <div class="modal-body">

                    <input type="hidden" id="id" name="id">

                    <div class="row g-2">
                        <div class="col-12 col-sm-6 col-md-7">
                            <label for="detalle" class="form-label">Nombre del Equipo</label>
                            <input type="text" class="form-control" id="detalle" name="detalle">
                        </div>

                        <div class="col-12 col-sm-6 col-md-5">
                            <label for="codigo" class="form-label">Código</label>
                            <input type="text" class="form-control" id="codigo" name="codigo">
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="stock" name="stock">
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="limite" class="form-label">Límite</label>
                            <input type="number" class="form-control" id="limite" name="limite">
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="buffer" class="form-label">Buffer</label>
                            <input type="number" class="form-control" id="buffer" name="buffer">
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="marca" class="form-label">Marca</label>
                            <input type="text" class="form-control" id="marca" list="listaMarcas" name="marca">
                            <datalist id="listaMarcas">
                                <?php foreach ($listaMarcas as $marca): ?>
                                    <option value="<?= $marca['marc_detalle'] ?>">
                                    <?php endforeach; ?>
                            </datalist>
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
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

                        <div class="col-12 col-sm-4">
                            <label for="subcategoria" class="form-label">Subcategoría</label>
                            <?php if ($listaSubCategorias != 'error'): ?>
                                <select class="form-select" name="subcategoria" id="subcategoria">
                                    <option value="">Seleccione una opción</option>
                                    <?php foreach ($listaSubCategorias as $subCategoria): ?>
                                        <option value="<?= $subCategoria['scat_id'] ?>">
                                            <?= $subCategoria['scat_detalle'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <p>No se encontraron subCategorias disponibles.</p>
                            <?php endif; ?>
                        </div>

                        <div class="col-12 col-sm-6 col-md-6">
                            <label for="proveedor" class="form-label">Proveedor</label>
                            <?php if ($listaProveedores != 'error'): ?>
                                <select class="form-select" name="proveedor" id="proveedor">
                                    <option value="">Seleccione una opción</option>
                                    <?php foreach ($listaProveedores as $proveedor): ?>
                                        <option value="<?= $proveedor['prov_id'] ?>">
                                            <?= $proveedor['prov_empresa'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <p>No se encontraron proveedores disponibles.</p>
                            <?php endif; ?>
                        </div>

                        <div class="col-12 col-sm-6 col-md-6">
                            <label for="consecutivo" class="form-label">Facrura</label>
                            <input type="number" class="form-control" id="consecutivo" name="consecutivo">
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="compra" class="form-label">Compra</label>
                            <input type="text" class="form-control" id="compra" name="compra">
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="utilidad" class="form-label">Utilidad</label>
                            <input type="text" class="form-control" id="utilidad" name="utilidad">
                        </div>

                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="venta" class="form-label">Venta</label>
                            <input type="text" class="form-control" id="venta" name="venta">
                        </div>

                    </div>
                </div>

                <div class="modal-footer py-1 px-2">
                    <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="editar" class="btn btn-success fs-6">Editar</button>
                </div>
            </form>
        <?php
            break;
        case '6': // Eliminar
        ?>
            <form action="" method="post">
                <div class="modal-body m-2 p-2">
                    <p class="mb-3">¿Desea eliminar el siguiente producto <br>
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
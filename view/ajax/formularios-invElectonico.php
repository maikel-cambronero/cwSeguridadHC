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
                        <div class="col-12 col-sm-6 col-md-2">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="stock" name="stock">
                        </div>
                        <div class="col-12 col-sm-6 col-md-2">
                            <label for="minima" class="form-label">Catn. Mínima</label>
                            <input type="number" class="form-control" id="minima" name="minima">
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <label for="marca" class="form-label">Marca</label>
                            <input type="text" class="form-control" list="listaMarcas" id="marca" name="marca">
                            <datalist id="listaMarcas">
                                <?php foreach ($listaMarcas as $marca): ?>
                                    <option value="<?= $marca['marc_detalle'] ?>">
                                <?php endforeach; ?>
                            </datalist>
                        </div>
                        <div class="col-12 col-sm-6 col-md-5">
                            <label for="codigo" class="form-label">Código</label>
                            <input type="text" class="form-control" id="codigo" name="codigo">
                        </div>
                    </div>

                    <div class="row g-2">
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

                        <div class="col-12 col-sm-4">
                            <label for="proveedor" class="form-label">Proveedor</label>
                            <?php if ($listaProveedores != 'error'): ?>
                                <select class="form-select" name="proveedor" id="proveedor">
                                    <option value="">Seleccione una opción</option>
                                    <?php foreach ($listaProveedores as $proveedor): ?>
                                        <option value="<?= $proveedor['ID'] ?> " data-cambio="<?= $proveedor['Tipo_Cambio'] ?>">
                                            <?= $proveedor['Empresa'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <p>No se encontraron proveedores disponibles.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-12 col-sm-6 col-md-3">
                            <label for="precioDolar" class="form-label">Precio en Dolar</label>
                            <input type="text" class="form-control" id="precioDolar" name="precioDolar">
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <label for="dolar" class="form-label">Tipo de Cambio</label>
                            <input type="text" class="form-control" id="dolar" name="dolar" readonly>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <label for="precioNoGana" class="form-label">Precio</label>
                            <input type="text" class="form-control" id="precioNoGana" name="precioNoGana" readonly>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <label for="porcentaje" class="form-label">Porcentaje</label>
                            <input type="text" class="form-control" id="porcentaje" name="porcentaje">
                        </div>
                    </div>

                    <div class="row g-2 justify-content-center">
                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="total" class="form-label">Precio Total</label>
                            <input type="text" class="form-control" id="total" name="total" readonly>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-12 col-sm-12 col-md-12">
                            <label for="detalle" class="form-label">Detalle</label>
                            <textarea class="form-control" id="detalle" name="detalle" placeholder="Escriba aquí el detalle..." style="height: 500px;"></textarea>
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
                    <!-- Formulario del modal -->
                    <div class="row g-2">
                        <div class="col-12 col-sm-6 col-md-2">
                            <label for="stockEditar" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="stockEditar" name="stockEditar">
                        </div>
                        <div class="col-12 col-sm-6 col-md-2">
                            <label for="minimaEditar" class="form-label">Catn. Mínima</label>
                            <input type="number" class="form-control" id="minimaEditar" name="minimaEditar">
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <label for="marcaEditar" class="form-label">Marca</label>
                            <input type="text" class="form-control" id="marcaEditar" list="listaMarcas" name="marcaEditar">
                            <datalist id="listaMarcas">
                                <?php foreach ($listaMarcas as $marca): ?>
                                    <option value="<?= $marca['marc_detalle'] ?>">
                                <?php endforeach; ?>
                            </datalist>
                        </div>
                        
                        <div class="col-12 col-sm-6 col-md-5">
                            <label for="codigoEditar" class="form-label">Código</label>
                            <input type="text" class="form-control" id="codigoEditar" name="codigoEditar">
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-12 col-sm-4">
                            <label for="categoriaEditar" class="form-label">Categoría</label>
                            <?php if ($listaCategorias != 'error'): ?>
                                <select class="form-select" name="categoriaEditar" id="categoriaEditar">
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
                            <label for="subcategoriaEditar" class="form-label">Subcategoría</label>
                            <?php if ($listaSubCategorias != 'error'): ?>
                                <select class="form-select" name="subcategoriaEditar" id="subcategoriaEditar">
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

                        <div class="col-12 col-sm-4">
                            <label for="proveedorEditar" class="form-label">Proveedor</label>
                            <?php if ($listaProveedores != 'error'): ?>
                                <select class="form-select" name="proveedorEditar" id="proveedorEditar">
                                    <option value="">Seleccione una opción</option>
                                    <?php foreach ($listaProveedores as $proveedor): ?>
                                        <option value="<?= $proveedor['ID'] ?>" data-cambio="<?= $proveedor['Tipo_Cambio'] ?>">
                                            <?= $proveedor['Empresa'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <p>No se encontraron proveedores disponibles.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-12 col-sm-6 col-md-3">
                            <label for="precioDolarEditar" class="form-label">Precio en Dolar</label>
                            <input type="text" class="form-control" id="precioDolarEditar" name="precioDolarEditar">
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <label for="dolarEditar" class="form-label">Tipo de Cambio</label>
                            <input type="text" class="form-control" id="dolarEditar" name="dolarEditar" readonly>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <label for="precioNoGanaEditar" class="form-label">Precio</label>
                            <input type="text" class="form-control" id="precioNoGanaEditar" name="precioNoGanaEditar" readonly>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <label for="porcentajeEditar" class="form-label">Porcentaje</label>
                            <input type="text" class="form-control" id="porcentajeEditar" name="porcentajeEditar">
                        </div>
                    </div>

                    <div class="row g-2 justify-content-center">
                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="totalEditar" class="form-label">Precio Total</label>
                            <input type="text" class="form-control" id="totalEditar" name="totalEditar" readonly>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-12 col-sm-12 col-md-12">
                            <label for="detalle" class="form-label">Detalle</label>
                            <textarea class="form-control" id="detalleEditar" name="detalleEditar"></textarea>
                        </div>
                    </div>

                    <input type="hidden" name="idEditar" id="idEditar">
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
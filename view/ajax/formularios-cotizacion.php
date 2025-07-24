<?php
include_once '../../controllers/cotizacionController.php';
session_start();
// Instancias

$controller = new cotizacionesController();

$lastID = $controller->getLastID();

// Acceder al valor correcto
if ($lastID != 'error') {
    $cot_id = $lastID[0]['last_id'];
}


function codigoOrden($i)
{
    $consecutivo = intval($i) + 1;
    $year = date('Y');
    $codigo = "COT-FS-" . $year . "-" . str_pad($consecutivo, 4, '0', STR_PAD_LEFT);
    return $codigo;
}

if (isset($_POST['estado'])) {
    $estado = $_POST['estado'];

    switch ($estado) {
        case '4': // Agregar
            $codigoGenerado = codigoOrden($cot_id);
            $hoy = date('Y-m-d');
?>
            <form action="" method="post">
                <div class="modal-body">

                    <div class="row g-2">
                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="num_coti" class="form-label">N° Cotización</label>
                            <input type="text" class="form-control" id="num_coti" name="num_coti" value="<?= $codigoGenerado ?>" readonly>
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="fecha_emite" class="form-label">Fecha de Emición</label>
                            <input type="date" class="form-control" id="fecha_emite" name="fecha_emite" value="<?= $hoy ?>" required>
                        </div>

                        <?php
                        $fecha_valida = date('Y-m-d', strtotime('+15 days'));
                        ?>
                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="fecha_valida" class="form-label">Valida hasta</label>
                            <input type="date" class="form-control" id="fecha_valida" name="fecha_valida" value="<?= $fecha_valida ?>" required>
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="Vendor" class="form-label">Vendedor</label>
                            <input type="text" class="form-control" id="Vendor" name="Vendor" value="<?= $_SESSION['nombre']; ?>" require>
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="cliente" class="form-label">Cliente</label>
                            <input type="text" class="form-control" id="cliente" name="cliente" require>
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono">
                        </div>

                    </div>

                    <hr class="line mt-2 mb-2 pb-2">

                    <div class="row g-2">
                        <div id="equiposContainer">
                            <!-- Aquí se cargan los equipos asignados a la visita -->
                        </div>
                        <button type="button" class="btn btn-outline-primary mb-3" onclick="agregarEquipo()">➕ Agregar Equipo</button>
                    </div>

                    <hr class="line mt-2 mb-2 pb-2">

                    <div class="row g-2">

                        <div class="col-md-3">
                            <label class="form-label" for="subtotal_general">Subtotal</label>
                            <input type="text" class="form-control" name="subtotal_general" id="subtotal_general" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="iva_general">Total IVA</label>
                            <input type="text" class="form-control" name="iva_general" id="iva_general" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="descuento_general">Total Descuento</label>
                            <input type="text" class="form-control" name="descuento_general" id="descuento_general" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="total_general">TOTAL</label>
                            <input type="text" class="form-control" name="total_general" id="total_general" readonly>
                        </div>
                    </div>

                </div>

                <div class="modal-footer py-1 px-2">
                    <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="nueva_coti" class="btn btn-success fs-6">Cotizar</button>
                </div>
            </form>
        <?php
            break;

        case '5': // Editar
        ?>
            <form action="" method="post">
                <div class="modal-body">

                    <div class="row g-2">
                        <input type="hidden" name="id" id="id">
                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="num_coti" class="form-label">N° Cotización</label>
                            <input type="text" class="form-control" id="num_coti" name="num_coti" readonly>
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="fecha_emite" class="form-label">Fecha de Emición</label>
                            <input type="date" class="form-control" id="fecha_emite" name="fecha_emite" required>
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="fecha_valida" class="form-label">Valida hasta</label>
                            <input type="date" class="form-control" id="fecha_valida" name="fecha_valida" required>
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="Vendor" class="form-label">Vendedor</label>
                            <input type="text" class="form-control" id="Vendor" name="Vendor" require>
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="cliente" class="form-label">Cliente</label>
                            <input type="text" class="form-control" id="cliente" name="cliente" require>
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono">
                        </div>

                    </div>

                    <hr class="line mt-2 mb-2 pb-2">

                    <div class="row g-2">
                        <div id="equiposContainer">
                            <!-- Aquí se cargan los equipos asignados a la visita -->
                        </div>
                        <button type="button" class="btn btn-outline-primary mb-3" onclick="agregarEquipo()">➕ Agregar Equipo</button>
                    </div>

                    <hr class="line mt-2 mb-2 pb-2">

                    <div class="row g-2">

                        <div class="col-md-3">
                            <label class="form-label" for="subtotal_general">Subtotal</label>
                            <input type="text" class="form-control" name="subtotal_general" id="subtotal_general" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="iva_general">Total IVA</label>
                            <input type="text" class="form-control" name="iva_general" id="iva_general" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="descuento_general">Total Descuento</label>
                            <input type="text" class="form-control" name="descuento_general" id="descuento_general" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="total_general">TOTAL</label>
                            <input type="text" class="form-control" name="total_general" id="total_general" readonly>
                        </div>
                    </div>

                </div>

                <div class="modal-footer py-1 px-2">
                    <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="nueva_coti" class="btn btn-success fs-6">Cotizar</button>
                </div>
            </form>
        <?php
            break;
        case '6': // Eliminar
        ?>
            <form id="formVerificarPass" action="" method="post">
                <input type="hidden" id="eliminarId" name="id">

                <div class="modal-body m-2 p-2">
                    <p class="mb-3">
                        ¿Desea eliminar la cotización con el código: <strong id="codigoEliminarTexto">#CODIGO</strong>?
                    </p>
                </div>

                <div class="modal-footer py-1 px-2">
                    <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="btnEliminar" class="btn btn-success fs-6">Continuar</button>
                </div>
            </form>
<?php
            break;
    }
}



?>
<?php
include_once '../../controllers/reportSalidasControllers.php';

// Instancias

$controller = new ordenConroller();
$controller = new ordenConroller();
$lastID = $controller->getLastID();
$vehiculos = $controller->get_buseta();

$consecutivo = intval($lastID) + 1;

function codigoOrden($consecutivo)
{
    $year = date('Y');
    $codigo = "ORD-" . $year . "-" . str_pad($consecutivo, 4, '0', STR_PAD_LEFT);
    return $codigo;
}

if (isset($_POST['estado'])) {
    $estado = $_POST['estado'];

    switch ($estado) {
        case '4': // Agregar
            $codigoGenerado = codigoOrden($consecutivo);
            $hoy = date('Y-m-d');
?>
            <form action="" method="post">
                <div class="modal-body">

                    <div class="row g-2">
                        <div class="col-12 col-sm-4 col-md-3">
                            <label for="num_orden" class="form-label">N° Orden</label>
                            <input type="text" class="form-control" id="num_orden" name="num_orden" value="<?= $codigoGenerado ?>" readonly>
                        </div>

                        <div class="col-12 col-sm-4 col-md-3">
                            <label for="fecha_sale" class="form-label">Fecha de Salida</label>
                            <input type="date" class="form-control" id="fecha_sale" name="fecha_sale" value="<?= $hoy ?>" required>
                        </div>

                        <div class="col-12 col-sm-4 col-md-3">
                            <label for="vehiculo" class="form-label">Vehículo</label>
                            <select name="vehiculo" id="vehiculo" class="form-select">
                                <option value="">Seleccione un vehículo</option>
                                <?php foreach ($vehiculos as $v): ?>
                                    <option value="<?= htmlspecialchars($v['veh_id']) ?>">
                                        <?= htmlspecialchars($v['veh_placa'] . ' ' . $v['veh_modelo']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12 col-sm-4 col-md-3">
                            <label for="tecnico" class="form-label">Técnico</label>
                            <input type="text" class="form-control" id="tecnico" name="tecnico" require>
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="asistente_1" class="form-label">Asistente 1</label>
                            <input type="text" class="form-control" id="asistente_1" name="asistente_1" require>
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="asistente_2" class="form-label">Asistente 2</label>
                            <input type="text" class="form-control" id="asistente_2" name="asistente_2">
                        </div>

                        <div class="col-12 col-sm-4">
                            <label for="tipo_trabajo" class="form-label">Tipo de Trabajo</label>
                            <select class="form-select" name="tipo_trabajo" id="tipo_trabajo" require>
                                <option value="">Seleccione</option>
                                <option value="1">Instalación</option>
                                <option value="2">Mantenimiento</option>
                                <option value="3">Revisión</option>
                            </select>
                        </div>
                    </div>

                    <hr class="line mt-2 mb-2 pb-2">

                    <div class="row g-2">
                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="cliente" class="form-label">Cliente</label>
                            <input type="text" class="form-control" id="cliente" name="cliente" require>
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion">
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="telefono" class="form-label">Télefono</label>
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
                        <div class="col-12 col-sm-12 col-md-12">
                            <label for="trabajo" class="form-label">Descripción del Trabajo</label>
                            <textarea class="form-control" id="trabajo" name="trabajo" require></textarea>
                        </div>
                    </div>

                </div>

                <div class="modal-footer py-1 px-2">
                    <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="nueva_orden" class="btn btn-success fs-6">Guardar Orden</button>
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
                        <div class="col-12 col-sm-4 col-md-3">
                            <label for="num_orden" class="form-label">N° Orden</label>
                            <input type="text" class="form-control" id="num_orden" name="num_orden" readonly>
                        </div>

                        <div class="col-12 col-sm-4 col-md-3">
                            <label for="fecha_sale" class="form-label">Fecha de Salida</label>
                            <input type="date" class="form-control" id="fecha_sale" name="fecha_sale" require>
                        </div>

                        <div class="col-12 col-sm-4 col-md-3">
                            <label for="vehiculo" class="form-label">Vehículo</label>
                            <select name="vehiculo" id="vehiculo" class="form-select">
                                <option value="">Seleccione un vehículo</option>
                                <?php foreach ($vehiculos as $v): ?>
                                    <option value="<?= htmlspecialchars($v['veh_id']) ?>">
                                        <?= htmlspecialchars($v['veh_placa'] . ' ' . $v['veh_modelo']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12 col-sm-4 col-md-3">
                            <label for="tecnico" class="form-label">Técnico</label>
                            <input type="text" class="form-control" id="tecnico" name="tecnico" require>
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="asistente_1" class="form-label">Asistente 1</label>
                            <input type="text" class="form-control" id="asistente_1" name="asistente_1" require>
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="asistente_2" class="form-label">Asistente 2</label>
                            <input type="text" class="form-control" id="asistente_2" name="asistente_2">
                        </div>

                        <div class="col-12 col-sm-4">
                            <label for="tipo_trabajo" class="form-label">Tipo de Trabajo</label>
                            <select class="form-select" name="tipo_trabajo" id="tipo_trabajo" require>
                                <option value="">Seleccione</option>
                                <option value="1">Instalación</option>
                                <option value="2">Mantenimiento</option>
                                <option value="3">Revisión</option>
                            </select>
                        </div>
                    </div>

                    <hr class="line mt-2 mb-2 pb-2">

                    <div class="row g-2">
                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="cliente" class="form-label">Cliente</label>
                            <input type="text" class="form-control" id="cliente" name="cliente" require>
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion">
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="telefono" class="form-label">Télefono</label>
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
                        <div class="col-12 col-sm-12 col-md-12">
                            <label for="trabajo" class="form-label">Descripción del Trabajo</label>
                            <textarea class="form-control" id="trabajo" name="trabajo" require></textarea>
                        </div>
                    </div>

                </div>

                <div class="modal-footer py-1 px-2">
                    <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="editar_orden" class="btn btn-success fs-6">Editar Orden</button>
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
                        ¿Desea eliminar la orden con el código: <strong id="ordenEliminar">#CODIGO</strong>?
                    </p>

                    <div id="reintegrarContainer" class="mb-3 d-none">
                        <label for="reintegrarSelect" class="form-label">¿Reintegrar equipos al stock?</label>
                        <select name="reintegrar" id="reintegrarSelect" class="form-select">
                            <option value="" selected disabled>Seleccione una opción</option>
                            <option value="1">Sí</option>
                            <option value="2">No</option>
                        </select>
                        <div id="alertaReintegrar" class="text-danger mt-2 d-none">Debe seleccionar si desea reintegrar los equipos.</div>
                    </div>
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
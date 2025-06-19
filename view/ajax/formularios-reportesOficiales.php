<?php
if (isset($_POST['estado'])) {
    $estado = $_POST['estado'];

    switch ($estado) {
        case '4': // Agregar
?>
            <form action="" method="post">
                <div class="modal-body">

                    <input type="hidden" class="form-control" id="id" name="id">

                    <div class="row g-2">
                        <div class="col-12 col-sm-4 col-md-6">
                            <label for="cedula" class="form-label">Cédula <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="cedula" name="cedula" onclick="abrirModalOficiales()" readonly required>
                        </div>

                        <div class="col-12 col-sm-4 col-md-6">
                            <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" readonly required>
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="delta" class="form-label">Código Delta <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="delta" name="delta" readonly required>
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="puesto" class="form-label">Puesto <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="puesto" name="puesto" readonly required>
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
                            <select name="estado" id="estado" class="form-select" required>
                                <option value="">Seleccione un estado</option>
                                <option value="35">Oficial Excelente</option>
                                <option value="36">Oficial Atención</option>
                                <option value="37">Oficial Crítico</option>
                            </select>
                        </div>

                        <div class="col-12 col-sm-12 col-md-12">
                            <label for="motivo" class="form-label">Motivo <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="motivo" name="motivo"></textarea>
                        </div>

                        <div class="col-12 col-sm-12 col-md-12">
                            <label for="justi" class="form-label">Justificación <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="justi" name="justi"></textarea>
                        </div>
                    </div>

                </div>

                <div class="modal-footer py-1 px-2">
                    <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="nuevo_comentario" class="btn btn-success fs-6">Guardar</button>
                </div>
            </form>
        <?php
            break;

        case '5': // Editar
        ?>
            <form action="" method="post">
                <div class="modal-body">

                    <input type="hidden" class="form-control" id="id" name="id">
                    <input type="hidden" class="form-control" id="id_reporte" name="id_reporte">

                    <div class="row g-2">
                        <div class="col-12 col-sm-4 col-md-6">
                            <label for="cedula" class="form-label">Cédula <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="cedula" name="cedula" onclick="abrirModalOficialesEditar()" required>
                        </div>

                        <div class="col-12 col-sm-4 col-md-6">
                            <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="delta" class="form-label">Código Delta <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="delta" name="delta" required>
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="puesto" class="form-label">Puesto <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="puesto" name="puesto" required>
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
                            <select name="estado" id="estado" class="form-select" required>
                                <option value="">Seleccione un estado</option>
                                <option value="35">Oficial Excelente</option>
                                <option value="36">Oficial Atención</option>
                                <option value="37">Oficial Crítico</option>
                            </select>
                        </div>

                        <div class="col-12 col-sm-12 col-md-12">
                            <label for="motivo" class="form-label">Motivo <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="motivo" name="motivo"></textarea>
                        </div>

                        <div class="col-12 col-sm-12 col-md-12">
                            <label for="justi" class="form-label">Justificación <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="justi" name="justi"></textarea>
                        </div>
                    </div>

                </div>

                <div class="modal-footer py-1 px-2">
                    <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="editar_comentario" class="btn btn-success fs-6">Guardar</button>
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
                        ¿Desea eliminar el comentario con realizado a: <strong id="CODIGO">#CODIGO</strong>?
                    </p>
                </div>

                <div class="modal-footer py-1 px-2">
                    <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="eliminar_comentario" class="btn btn-danger fs-6">Eliminar</button>
                </div>
            </form>
<?php
            break;
    }
}



?>
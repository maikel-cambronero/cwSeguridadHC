<?php
include_once '../../controllers/usuariosController.php';

// Instancias
$colab_controller = new usuariosController();
$deptos = $colab_controller->get_depto();
$roles = $colab_controller->get_rol();

$accesos = $colab_controller->get_nivel_acceso();


if (isset($_POST['estado'])) {
    $estado = $_POST['estado'];

    switch ($estado) {
        case '4': // Agregar
?>
            <form action="" method="post">
                <div class="modal-body">
                    <div class="row g-2">
                        <!-- Identificación -->
                        <div class="col-12 col-sm-6 col-md-6">
                            <label for="cedula" class="form-label">Cédula</label>
                            <input type="text" class="form-control" id="cedula" name="cedula" placeholder="Digite la cédula del colaborador">
                        </div>

                        <!-- Nivel Acceso -->
                        <div class="col-12 col-sm-6 col-md-6">
                            <label for="acceso" class="form-label">Nivel de Acceso</label>
                            <?php if ($accesos != 'error'): ?>
                                <select class="form-select" name="acceso" id="acceso">
                                    <option value="">Seleccione</option>
                                    <?php foreach ($accesos as $acces): ?>
                                        <option value="<?= $acces['acs_id'] ?>">
                                            <?= $acces['acs_nombre'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <p>No se encontraron departamentos disponibles.</p>
                            <?php endif; ?>
                        </div>

                        <div class="col-12 col-sm-6 col-md-12">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Digite la contraseña">
                        </div>
                    </div>

                    <div class="modal-footer py-1 px-2">
                        <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="nuevo" class="btn btn-success fs-6">Guardar</button>
                    </div>
                </div>
            </form>
        <?php
            break;

        case '5': // Editar
        ?>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">

                    <input type="hidden" name="id" id="id">

                    <!-- Nombre -->
                    <div class="row g-2">
                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="name" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="name" name="name" readonly>
                        </div>

                        <!-- Usuario -->
                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="user" class="form-label">Usuario</label>
                            <input type="text" class="form-control" id="user" name="user" readonly>
                        </div>

                        <!-- Nivel Acceso -->
                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="acceso" class="form-label">Nivel de Acceso</label>
                            <?php if ($accesos != 'error'): ?>
                                <select class="form-select" name="acceso" id="acceso">
                                    <option value="">Seleccione</option>
                                    <?php foreach ($accesos as $acces): ?>
                                        <option value="<?= $acces['acs_id'] ?>">
                                            <?= $acces['acs_nombre'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <p>No se encontraron departamentos disponibles.</p>
                            <?php endif; ?>
                        </div>

                        <!-- Usuario -->
                        <div class="col-12 col-sm-12 col-md-12">
                            <label for="user" class="form-label">Usuario</label>
                            <textarea name="observaciones" id="observaciones"></textarea>
                        </div>

                        <div class="modal-footer py-1 px-2">
                            <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" name="accesobtn" class="btn btn-success fs-6">Guardar</button>
                        </div>
                    </div>
                </div>
            </form>
        <?php
            break;
        case '6': // Cambiar Estado
        ?>
            <form id="formVerificarPass" action="" method="post">
                <input type="hidden" id="eliminarId" name="id">
                <input type="hidden" id="estado" name="estado">
                <div class="modal-body m-2 p-2">
                    <p class="mb-3">¿Desea descativar al usuario: <strong id="usernameEliminar">#CODIGO</strong>?</p>
                </div>

                <div class="modal-footer py-1 px-2">
                    <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="btnEstado" class="btn btn-success fs-6">Continuar</button>
                </div>
            </form>
<?php
            break;
    }
}



?>
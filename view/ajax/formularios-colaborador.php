<?php
include_once '../../controllers/seguridadCotroller.php';
include_once '../../controllers/usuariosController.php';

// Instancias
$controller = new seguridadConroller();
$listaColaboradores = $controller->getColaboradores();
$listaCategorias = $controller->getCategoria();

$colab_controller = new usuariosController();
$deptos = $colab_controller->get_depto();
$roles = $colab_controller->get_rol();


if (isset($_POST['estado'])) {
    $estado = $_POST['estado'];

    switch ($estado) {
        case '4': // Agregar
?>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row g-2">

                        <!-- Nombre -->
                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="name" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>

                        <!-- Apellidos -->
                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="apellido" class="form-label">Apellidos</label>
                            <input type="text" class="form-control" id="apellido" name="apellido">
                        </div>

                        <!-- Identificación -->
                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="cedula" class="form-label">Cédula</label>
                            <input type="text" class="form-control" id="cedula" name="cedula">
                        </div>

                        <!-- Teléfono -->
                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono">
                        </div>

                        <!-- Correo -->
                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="email" class="form-label">Correo</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>

                        <!-- Fecha Ingreso -->
                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="fecha_ingreso" class="form-label">Fecha Ingreso</label>
                            <input type="text" class="form-control" id="fecha_ingreso" name="fecha_ingreso">
                        </div>

                        <!-- Dirección -->
                        <div class="col-12 col-sm-12 col-md-8">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion">
                        </div>

                        <!-- Salario -->
                        <div class="col-12 col-sm-12 col-md-4">
                            <label for="salario" class="form-label">Salario</label>
                            <input type="text" class="form-control" id="salario" name="salario">
                        </div>

                        <!-- Número de Cuenta -->
                        <div class="col-12 col-sm-6 col-md-6">
                            <label for="cuenta" class="form-label">Num. Cuenta</label>
                            <input type="text" class="form-control" id="cuenta" name="cuenta">
                        </div>

                        <!-- Departamento -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <label for="depto" class="form-label">Departamento</label>
                            <?php if ($deptos != 'error'): ?>
                                <select class="form-select" name="depto" id="depto">
                                    <option value="">Seleccione</option>
                                    <?php foreach ($deptos as $depto): ?>
                                        <option value="<?= $depto['dep_id'] ?>">
                                            <?= $depto['dep_detalle'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <p>No se encontraron departamentos disponibles.</p>
                            <?php endif; ?>
                        </div>

                        <!-- Rol -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <label for="rol" class="form-label">Rol</label>
                            <select class="form-select" name="rol" id="rol">
                                <option value="">Seleccione un Depto</option>
                            </select>
                        </div>

                        <!-- Rango de Vacaciones -->
                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="fecha_vacaciones" class="form-label">Vacaciones</label>
                            <input type="text" class="form-control" id="fecha_vacaciones" name="fecha_vacaciones">
                        </div>

                        <!-- Licencias de Conducir -->
                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="licencias" class="form-label">Licencias de Conducir</label>
                            <input type="text" class="form-control" id="licencias" name="licencias">
                        </div>

                        <!-- Foto Perfil -->
                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="foto" class="form-label">Foto de Perfil</label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                        </div>


                        <div id="fechas-seguridad" class="row d-none">
                            <!-- Fecha Carnet Agente -->
                            <div class="col-12 col-sm-6 col-md-4">
                                <label for="fecha_agente" class="form-label">Carnet Agente</label>
                                <input type="text" class="form-control" id="fecha_agente" name="fecha_agente">
                            </div>

                            <!-- Fecha Carnet Armas -->
                            <div class="col-12 col-sm-6 col-md-4">
                                <label for="fecha_arma" class="form-label">Portación Armas</label>
                                <input type="text" class="form-control" id="fecha_arma" name="fecha_arma">
                            </div>

                            <!-- Fecha Examen Psicologico -->
                            <div class="col-12 col-sm-6 col-md-4">
                                <label for="fecha_psicologico" class="form-label">Exame Psicológico</label>
                                <input type="text" class="form-control" id="fecha_psicologico" name="fecha_psicologico">
                            </div>

                            <!-- Fecha Huellas -->
                            <div class="col-12 col-sm-6 col-md-4">
                                <label for="fecha_huellas" class="form-label">Huellas</label>
                                <input type="text" class="form-control" id="fecha_huellas" name="fecha_huellas">
                            </div>

                            <!-- Codigo Delta -->
                            <div class="col-12 col-sm-6 col-md-4">
                                <label for="delta" class="form-label">Código Delta</label>
                                <input type="text" class="form-control" id="delta" name="delta">
                            </div>

                            <!-- Puesto -->
                            <div class="col-12 col-sm-6 col-md-4">
                                <label for="puesto" class="form-label">Puesto</label>
                                <input type="text" class="form-control" id="puesto" name="puesto">
                            </div>
                        </div>

                        <div class="modal-footer py-1 px-2">
                            <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" name="nuevo" class="btn btn-success fs-6">Guardar</button>
                        </div>
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
                    <input type="hidden" name="imagen_actual" id="imagen_actual">

                    <!-- Nombre -->
                    <div class="row g-2">
                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="name" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>

                        <!-- Apellidos -->
                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="apellido" class="form-label">Apellidos</label>
                            <input type="text" class="form-control" id="apellido" name="apellido">
                        </div>

                        <!-- Identificación -->
                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="cedula" class="form-label">Cédula</label>
                            <input type="text" class="form-control" id="cedula" name="cedula">
                        </div>

                        <!-- Teléfono -->
                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono">
                        </div>

                        <!-- Correo -->
                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="email" class="form-label">Correo</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>

                        <!-- Fecha Ingreso -->
                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="fecha_ingreso" class="form-label">Fecha Ingreso</label>
                            <input type="text" class="form-control" id="fecha_ingreso" name="fecha_ingreso">
                        </div>

                        <!-- Dirección -->
                        <div class="col-12 col-sm-12 col-md-8">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion">
                        </div>

                        <!-- Salario -->
                        <div class="col-12 col-sm-12 col-md-4">
                            <label for="salario" class="form-label">Salario</label>
                            <input type="text" class="form-control" id="salario" name="salario">
                        </div>

                        <!-- Número de Cuenta -->
                        <div class="col-12 col-sm-6 col-md-6">
                            <label for="cuenta" class="form-label">Num. Cuenta</label>
                            <input type="text" class="form-control" id="cuenta" name="cuenta">
                        </div>

                        <!-- Departamento -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <label for="depto" class="form-label">Departamento</label>
                            <?php if ($deptos != 'error'): ?>
                                <select class="form-select" name="depto" id="depto">
                                    <option value="">Seleccione</option>
                                    <?php foreach ($deptos as $depto): ?>
                                        <option value="<?= $depto['dep_id'] ?>">
                                            <?= $depto['dep_detalle'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <p>No se encontraron departamentos disponibles.</p>
                            <?php endif; ?>
                        </div>

                        <!-- Rol -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <label for="rol" class="form-label">Rol</label>
                            <select class="form-select" name="rol" id="rol">
                                <option value="">Seleccione un Depto</option>
                            </select>
                        </div>

                        <!-- Rango de Vacaciones -->
                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="fecha_vacaciones" class="form-label">Vacaciones</label>
                            <input type="text" class="form-control" id="fecha_vacaciones" name="fecha_vacaciones">
                        </div>

                        <!-- Licencias de Conducir -->
                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="licencias" class="form-label">Licencias de Conducir</label>
                            <input type="text" class="form-control" id="licencias" name="licencias">
                        </div>

                        <!-- Foto Perfil -->
                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="foto" class="form-label">Foto de Perfil</label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                        </div>

                         <div id="fechas-seguridad" class="row d-none">
                            <!-- Fecha Carnet Agente -->
                            <div class="col-12 col-sm-6 col-md-4">
                                <label for="fecha_agente" class="form-label">Carnet Agente</label>
                                <input type="text" class="form-control" id="fecha_agente" name="fecha_agente">
                            </div>

                            <!-- Fecha Carnet Armas -->
                            <div class="col-12 col-sm-6 col-md-4">
                                <label for="fecha_arma" class="form-label">Portación Armas</label>
                                <input type="text" class="form-control" id="fecha_arma" name="fecha_arma">
                            </div>

                            <!-- Fecha Examen Psicologico -->
                            <div class="col-12 col-sm-6 col-md-4">
                                <label for="fecha_psicologico" class="form-label">Exame Psicológico</label>
                                <input type="text" class="form-control" id="fecha_psicologico" name="fecha_psicologico">
                            </div>

                            <!-- Fecha Huellas -->
                            <div class="col-12 col-sm-6 col-md-4">
                                <label for="fecha_huellas" class="form-label">Huellas</label>
                                <input type="text" class="form-control" id="fecha_huellas" name="fecha_huellas">
                            </div>

                            <!-- Codigo Delta -->
                            <div class="col-12 col-sm-6 col-md-4">
                                <label for="delta" class="form-label">Código Delta</label>
                                <input type="text" class="form-control" id="delta" name="delta">
                            </div>

                            <!-- Puesto -->
                            <div class="col-12 col-sm-6 col-md-4">
                                <label for="puesto" class="form-label">Puesto</label>
                                <input type="text" class="form-control" id="puesto" name="puesto">
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
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">

                    <input type="hidden" name="id" id="id">

                    <div class="row g-2">
                        <!-- Nombre -->
                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="name" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="name" name="name" readonly>
                        </div>

                        <!-- Apellidos -->
                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="apellido" class="form-label">Apellidos</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" readonly>
                        </div>

                        <!-- Identificación -->
                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="cedula" class="form-label">Cédula</label>
                            <input type="text" class="form-control" id="cedula" name="cedula" readonly>
                        </div>

                        <!-- Teléfono -->
                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" readonly>
                        </div>

                        <!-- Fecha Ingreso -->
                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="fecha_ingreso_situacion" class="form-label">Fecha Ingreso</label>
                            <input type="text" class="form-control" id="fecha_ingreso_situacion" name="fecha_ingreso_situacion" readonly>
                        </div>

                        <!-- Situacion -->
                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="estado" class="form-label">Situación</label>
                            <select class="form-select" name="estado" id="estado">
                                <option value="25">Colaborador Activo</option>
                                <option value="26">Colaborador Inactivo</option>
                                <option value="27">Colaborador Despedido</option>
                            </select>
                        </div>

                        <div class="col-12 col-sm-12 col-md-12">
                            <label for="estado" class="form-label">Observaciones</label>
                            <textarea name="observaciones" id="observaciones"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer py-1 px-2">
                        <button type="button" class="btn btn-secondary me-2 fs-6" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="situacion" class="btn btn-success fs-6">Guardar</button>
                    </div>
            </form>
<?php
            break;
    }
}



?>
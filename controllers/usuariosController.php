<?php
require_once __DIR__ . '/../models/usuariosModel.php';

class usuariosController
{

    /**
     * FUNCIONES PARA LEER
     */

    public function get_empleado()
    {
        $model = new usuarioModel();
        $colaboradores = $model->get_empleado();

        if ($colaboradores != 'error') {
            return $colaboradores;
        } else {
            return 'error';
        }
    }

    public function get_depto()
    {
        $model = new usuarioModel();
        $depto = $model->get_depto();

        if ($depto != 'error') {
            return $depto;
        } else {
            return 'error';
        }
    }

    public function get_rol()
    {
        $model = new usuarioModel();
        $rol = $model->get_rol();

        if ($rol != 'error') {
            return $rol;
        } else {
            return 'error';
        }
    }

    public function get_empleados()
    {
        $model = new usuarioModel();
        $empleados = $model->get_empleados();

        if ($empleados != 'error') {
            return $empleados;
        } else {
            return 'error';
        }
    }

    public function get_usuarios_activos()
    {
        $model = new usuarioModel();
        $usuarios = $model->get_usuarios_activos();

        if ($usuarios != 'error') {
            return $usuarios;
        } else {
            return 'error';
        }
    }

    public function get_nivel_acceso()
    {
        $model = new usuarioModel();
        $acceso = $model->get_nivel_acceso();

        if ($acceso != 'error') {
            return $acceso;
        } else {
            return 'error';
        }
    }

    public function get_usuario_inactivo()
    {
        $model = new usuarioModel();
        $usuarios = $model->get_usuario_inactivo();

        if ($usuarios != 'error') {
            return $usuarios;
        } else {
            return 'error';
        }
    }

    public function get_colaborador_inactivo()
    {
        $model = new usuarioModel();
        $colaboradores = $model->get_colaborador_inactivo();

        if ($colaboradores != 'error') {
            return $colaboradores;
        } else {
            return 'error';
        }
    }

    public function get_colaborador_despedido()
    {
        $model = new usuarioModel();
        $colaboradores = $model->get_colaborador_despedido();

        if ($colaboradores != 'error') {
            return $colaboradores;
        } else {
            return 'error';
        }
    }
    public function get_usuario_general()
    {
        $model = new usuarioModel();
        $user = $model->get_usuario_general();

        if ($user != 'error') {
            return $user;
        } else {
            return 'error';
        }
    }

    /**
     * FUNCIONES PARA AGREGAR
     */

    public function addColab($nombre, $apellido, $cedula, $telefono, $correo, $fecha_ingreso, $direccion, $salario, $cuenta, $depto, $rol, $vacaciones, $licencias, $carnet_agente, $carnet_armas, $psicologico, $huellas, $nombreArchivoFinal, $delta, $puesto)
    {
        $model = new usuarioModel();
        $add = $model->addColab($nombre, $apellido, $cedula, $telefono, $correo, $fecha_ingreso, $direccion, $salario, $cuenta, $depto, $rol, $vacaciones, $licencias, $carnet_agente, $carnet_armas, $psicologico, $huellas, $nombreArchivoFinal, $delta, $puesto);

        if ($add == 'success') {
            return "success";
        } else {
            return 'error';
        }
    }

    public function addUser($cedula, $acceso, $password)
    {
        $model = new usuarioModel();
        $add = $model->addUser($cedula, $acceso, $password);

        if ($add == 'success') {
            return "success";
        } else {
            return 'error';
        }
    }

    /**
     * FUNCIONES PARA EDITAR
     */

    public function updateColab($id, $nombre, $apellido, $cedula, $telefono, $correo, $fecha_ingreso, $direccion, $cuenta, $depto, $rol, $vacaciones, $licencias, $carnet_agente, $carnet_armas, $psicologico, $huellas, $nombreArchivoFinal)
    {
        $model = new usuarioModel();
        $update = $model->updateColab($id, $nombre, $apellido, $cedula, $telefono, $correo, $fecha_ingreso, $direccion, $cuenta, $depto, $rol, $vacaciones, $licencias, $carnet_agente, $carnet_armas, $psicologico, $huellas, $nombreArchivoFinal);

        if ($update == 'success') {
            return "success";
        } else {
            return 'error';
        }
    }

    public function updateEstado($id, $situacion, $usuario, $observacion)
    {
        $model = new usuarioModel();
        $estado = $model->updateEstado($id, $situacion, $usuario, $observacion);

        if ($estado == 'success') {
            return "success";
        } else {
            return 'error';
        }
    }

    public function updateEstadoUser($id, $estado)
    {
        $model = new usuarioModel();
        $estado = $model->updateEstadoUser($id, $estado);

        if ($estado == 'success') {
            return "success";
        } else {
            return 'error';
        }
    }

    public function update_acceso($id, $usuario, $acceso, $observaciones)
    {
        $model = new usuarioModel();
        $user = $model->update_acceso($id, $usuario, $acceso, $observaciones);

        if ($user == 'success') {
            return "success";
        } else {
            return 'error';
        }
    }

    public function updatePass($id, $pass)
    {
        $model = new usuarioModel();
        $password = $model->updatePass($id, $pass);

        if ($password == 'success') {
            return "success";
        } else {
            return 'error';
        }
    }
}

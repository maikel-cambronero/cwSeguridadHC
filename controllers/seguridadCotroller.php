<?php
require_once __DIR__ . '/../models/seguridadModel.php';

class seguridadConroller
{

    /**
     ********** FUNCIONES PARA OBTENER DATOS **********
     */

    public function getCategoria()
    {
        $seguridadModel = new seguridadModel();
        $categoria = $seguridadModel->getCategoria();

        if ($categoria != 'error') {
            return $categoria;
        } else {
            return 'error';
        }
    }

    public function getsubCategoria()
    {
        $seguridadModel = new seguridadModel();
        $subCategoria = $seguridadModel->getsubCategoria();

        if ($subCategoria != 'error') {
            return $subCategoria;
        } else {
            return 'error';
        }
    }

    public function getColaboradores()
    {
        $seguridadModel = new seguridadModel();
        $campo = $seguridadModel->getColaboradores();

        if ($campo != 'error') {
            return $campo;
        } else {
            return 'error';
        }
    }

    public function getEquipo()
    {
        $seguridadModel = new seguridadModel();
        $herramientas = $seguridadModel->getEquipo();

        if ($herramientas != 'error') {
            return $herramientas;
        } else {
            return 'error';
        }
    }

    public function getEquipoAsignado(){
        $seguridadModel = new seguridadModel();
        $herramientas = $seguridadModel->getEquipoAsignado();

        if ($herramientas != 'error') {
            return $herramientas;
        } else {
            return 'error';
        }
    }

    /**
     ********** FUNCIONES PARA AGREGAR **********
     */
    public function addEquipo($stock, $condicion, $categoria, $subCategoria, $colaborador, $detalle)
    {
        $seguridadModel = new seguridadModel();
        $addHerramienta = $seguridadModel->addEquipo($stock, $condicion, $categoria, $subCategoria, $colaborador, $detalle);

        if ($addHerramienta === "success") {
            return "success";
        } else {
            return "error";
        }
    }

    /**
     ********** FUNCIONES PARA ELIMINAR **********
     */

    public function deleteEquipo($id)
    {
        $seguridadModel = new seguridadModel();
        $deleteEquipo = $seguridadModel->deleteEquipo($id);

        if ($deleteEquipo === "success") {
            return "success";
        } else {
            return "error";
        }
    }

    /**
     ********** FUNCIONES PARA EDITAR **********
     */

     public function updateEquipo($stock, $condicion, $categoria, $subCategoria, $colaborador, $detalle, $id){
        $seguridadModel = new seguridadModel();
        $updateHerramienta = $seguridadModel->updateEquipo($stock, $condicion, $categoria, $subCategoria, $colaborador, $detalle, $id);

        if ($updateHerramienta === "success") {
            return "success";
        } else {
            return "error";
        }
    }

    /**
     * OTRAS FUNCIONES
     */

    public function asignaEquipo($id, $stock, $condicion, $colaborador, $detalle)
    {
        $model = new seguridadModel();
        $function = $model->asignaEquipo($id, $stock, $condicion, $colaborador, $detalle);

        if ($function === "success") {
            return "success";
        } else {
            return "error";
        }
    }

}

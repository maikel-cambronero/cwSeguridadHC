<?php
require_once __DIR__ . '/../models/campoModel.php';

class campoConroller
{

    /**
     ********** FUNCIONES PARA OBTENER DATOS **********
     */

    public function getCategoriaCampo()
    {
        $campoModel = new campoModel();
        $categoria = $campoModel->getCategoriaCampo();

        if ($categoria != 'error') {
            return $categoria;
        } else {
            return 'error';
        }
    }

    public function getsubCategoriaCampo()
    {
        $campoModel = new campoModel();
        $subCategoria = $campoModel->getsubCategoriaCampo();

        if ($subCategoria != 'error') {
            return $subCategoria;
        } else {
            return 'error';
        }
    }

    public function getMarcasCampo()
    {
        $campoModel = new campoModel();
        $electronico = $campoModel->getMarcasCampo();

        if ($electronico != 'error') {
            return $electronico;
        } else {
            return 'error';
        }
    }

    public function getColaboradores()
    {
        $campoModel = new campoModel();
        $campo = $campoModel->getColaboradores();

        if ($campo != 'error') {
            return $campo;
        } else {
            return 'error';
        }
    }

    public function getHerramientas()
    {
        $campoModel = new campoModel();
        $herramientas = $campoModel->getHerramientas();

        if ($herramientas != 'error') {
            return $herramientas;
        } else {
            return 'error';
        }
    }

    public function getHerramientasAsignadas()
    {
        $campoModel = new campoModel();
        $herramientas = $campoModel->getHerramientasAsignadas();

        if ($herramientas != 'error') {
            return $herramientas;
        } else {
            return 'error';
        }
    }

    public function getHerramientasGeneral()
    {
        $campoModel = new campoModel();
        $herramientas = $campoModel->getHerramientasGeneral();

        if ($herramientas != 'error') {
            return $herramientas;
        } else {
            return 'error';
        }
    }

    /**
     ********** FUNCIONES PARA AGREGAR **********
     */
    public function addHerramineta($stock, $marca, $categoria, $subCategoria, $colaborador, $detalle)
    {
        $campoModel = new campoModel();
        $addHerramienta = $campoModel->addHerramineta($stock, $marca, $categoria, $subCategoria, $colaborador, $detalle);

        if ($addHerramienta === "success") {
            return "success";
        } else {
            return "error";
        }
    }

    /**
     ********** FUNCIONES PARA ELIMINAR **********
     */

    public function deleteHerramienta($id)
    {
        $campoModel = new campoModel();
        $deleteHerramienta = $campoModel->deleteHerramienta($id);

        if ($deleteHerramienta === "success") {
            return "success";
        } else {
            return "error";
        }
    }

    /**
     ********** FUNCIONES PARA EDITAR **********
     */

     public function updateHerramineta($stock, $marca, $categoria, $subCategoria, $colaborador, $detalle, $id){
        $campoModel = new campoModel();
        $updateHerramienta = $campoModel->upadateHerramienta($stock, $marca, $categoria, $subCategoria, $colaborador, $detalle, $id);

        if ($updateHerramienta === "success") {
            return "success";
        } else {
            return "error";
        }
    }

}

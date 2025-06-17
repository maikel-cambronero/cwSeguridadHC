<?php
require_once __DIR__ . '/../models/electronicoModel.php';

class electronicoConroller
{

    /**
     * FUNCIONES PARA LEER
     */

    public function getElectronicos_Agrupados($estado)
    {
        $electronicoModel = new ElectronicoModel();
        $electronico = $electronicoModel->getElectronicos_Agrupados($estado);

        if ($electronico != 'error') {
            return $electronico;
        } else {
            return 'error';
        }
    }

    public function getElectronicos_Agrupados_general()
    {
        $electronicoModel = new ElectronicoModel();
        $electronico = $electronicoModel->getElectronicos_Agrupados_general();

        if ($electronico != 'error') {
            return $electronico;
        } else {
            return 'error';
        }
    }

    public function getElectronicos_Todos($codigo)
    {
        $electronicoModel = new electronicoModel();
        $electronico = $electronicoModel->getElectronicos_Todos($codigo);

        if ($electronico != 'error') {
            return $electronico;
        } else {
            return 'error';
        }
    }

    public function getElectronicos_General()
    {
        $electronicoModel = new electronicoModel();
        $electronico = $electronicoModel->getElectronicos_General();

        if ($electronico != 'error') {
            return $electronico;
        } else {
            return 'error';
        }
    }

    public function getElectronicoAdvertencia()
    {
        $electronicoModel = new electronicoModel();
        $electronico = $electronicoModel->getElectroinco_advertencia();

        if ($electronico != 'error') {
            return $electronico;
        } else {
            return 'error';
        }
    }

    public function getProveedor()
    {
        $electronicoModel = new ElectronicoModel();
        $proveedores = $electronicoModel->getProveedores();

        if ($proveedores != 'error') {
            return $proveedores;
        } else {
            return 'error';
        }
    }

    public function getCategoria()
    {
        $electronicoModel = new ElectronicoModel();
        $categoria = $electronicoModel->getCategoria();

        if ($categoria != 'error') {
            return $categoria;
        } else {
            return 'error';
        }
    }

    public function getsubCategoria()
    {
        $electronicoModel = new ElectronicoModel();
        $subCategoria = $electronicoModel->getsubCategoria();

        if ($subCategoria != 'error') {
            return $subCategoria;
        } else {
            return 'error';
        }
    }

    public function getMarcas()
    {
        $electronicoModel = new ElectronicoModel();
        $electronico = $electronicoModel->getMarcas();

        if ($electronico != 'error') {
            return $electronico;
        } else {
            return 'error';
        }
    }

    /**
     * FUNCIONES PARA AGREGAR
     */

    public function addEquipo($detalle, $codigo, $stock, $limite, $buffer, $marca, $categoria, $subcategoria, $proveedor, $consecutivo, $compra, $utilidad, $venta)
    {
        $electronicoModel = new electronicoModel();
        $add = $electronicoModel->addEquipo($detalle, $codigo, $stock, $limite, $buffer, $marca, $categoria, $subcategoria, $proveedor, $consecutivo, $compra, $utilidad, $venta);

        if ($add === "success") {
            return "success";
        } else {
            return "error";
        }
    }


    /**
     * FUNCIONES PARA EDITAR
     */

    public function updateEquipo($id, $detalle, $codigo, $stock, $limite, $buffer, $marca, $categoria, $subcategoria, $proveedor, $consecutivo, $compra, $utilidad, $venta)
    {
        $electronicoModel = new electronicoModel();
        $update = $electronicoModel->updateEquipo($id, $detalle, $codigo, $stock, $limite, $buffer, $marca, $categoria, $subcategoria, $proveedor, $consecutivo, $compra, $utilidad, $venta);

        if ($update === "success") {
            return "success";
        } else {
            return "error";
        }
    }

    /**
     * FUNCIONES PARA ELIMINAR
     */

    public function deleteEquipo($id)
    {
        $electronicoModel = new electronicoModel();
        $delete = $electronicoModel->deleteEquipo($id);

        if ($delete === "success") {
            return "success";
        } else {
            return "error";
        }
    }
}

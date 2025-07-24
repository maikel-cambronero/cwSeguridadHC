<?php
require_once __DIR__ . '/../models/cotizacionesModel.php';

class cotizacionesController
{

    /**
     * FUNCIONES PARA LEER
     */

    public function getProductos()
    {
        $model = new cotizacionesModel();
        $productos = $model->getProductos();

        if ($productos != 'error') {
            return $productos;
        } else {
            return 'error';
        }
    }

    public function getLastID()
    {
        $model = new cotizacionesModel();
        $productos = $model->getLastID();

        if ($productos != 'error') {
            return $productos;
        } else {
            return 'error';
        }
    }

    public function get_coti_codigo($numCoti)
    {
        $model = new cotizacionesModel();
        $cotizacion = $model->get_coti_codigo($numCoti);

        if ($cotizacion != 'error') {
            return $cotizacion;
        } else {
            return 'error';
        }
    }

    public function get_equiposCoti_codigo($cot_id)
    {
        $model = new cotizacionesModel();
        $equipos = $model->get_equiposCoti_codigo($cot_id);

        if ($equipos != 'error') {
            return $equipos;
        } else {
            return 'error';
        }
    }

    public function getCotizaciones()
    {
        $model = new cotizacionesModel();
        $cotizaciones = $model->getCotizaciones();

        if ($cotizaciones != 'error') {
            return $cotizaciones;
        } else {
            return 'error';
        }
    }

    public function get_equiposCoti()
    {
        $model = new cotizacionesModel();
        $equipos = $model->get_equiposCoti();

        if ($equipos != 'error') {
            return $equipos;
        } else {
            return 'error';
        }
    }

    /**
     * FUNCIONES PARA AGREGAR
     */

    public function addCoti($cotizacion, $dateEmite, $dateValida, $saler, $cliente, $tell, $subtotal_general, $iva_general, $descuento_general, $total_general, $equipos)
    {
        $model = new cotizacionesModel();
        $cotizacion = $model->addCoti($cotizacion, $dateEmite, $dateValida, $saler, $cliente, $tell, $subtotal_general, $iva_general, $descuento_general, $total_general, $equipos);

        if ($cotizacion == 'success') {
            return 'success';
        } else {
            return 'error';
        }
    }

    /**
     * FUNCIONES PARA ELIMINAR
     */

    public function deleteCoti($id)
    {
        $model = new cotizacionesModel();
        $deleteCoti = $model->deleteCoti($id);

        if ($deleteCoti == 'success') {
            return 'success';
        } else {
            return 'error';
        }
    }



























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

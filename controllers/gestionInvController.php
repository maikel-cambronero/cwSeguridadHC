<?php
require_once __DIR__ . '/../models/gestionInvModel.php';

class gestionConroller
{

    /**
     ********** FUNCIONES PARA OBTENER DATOS **********
     */

    public function getCategorias()
    {
        $gestion = new gestionModel();
        $categorias = $gestion->getCategorias();

        if ($categorias != 'error') {
            return $categorias;
        } else {
            return 'error';
        }
    }

    public function getEstados()
    {
        $gestion = new gestionModel();
        $estados = $gestion->getEstados();

        if ($estados != 'error') {
            return $estados;
        } else {
            return 'error';
        }
    }

    public function getSubcategoria()
    {
        $gestion = new gestionModel();
        $subcategoria = $gestion->getSubcategoria();

        if ($subcategoria != 'error') {
            return $subcategoria;
        } else {
            return 'error';
        }
    }

    public function getMarca()
    {
        $gestion = new gestionModel();
        $marca = $gestion->getMarca();

        if ($marca != 'error') {
            return $marca;
        } else {
            return 'error';
        }
    }

    public function getProveedor()
    {
        $gestion = new gestionModel();
        $proveedor = $gestion->getProveedor();

        if ($proveedor != 'error') {
            return $proveedor;
        } else {
            return 'error';
        }
    }

    public function getVehiculo()
    {
        $gestion = new gestionModel();
        $vehiculo = $gestion->getVehiculo();

        if ($vehiculo != 'error') {
            return $vehiculo;
        } else {
            return 'error';
        }
    }

    /**
     ********** FUNCIONES PARA AGREGAR **********
     */
    public function addCategoria($detalle, $estado)
    {
        $gestion = new gestionModel();
        $estados = $gestion->addCategoria($detalle, $estado);

        if ($estados == 'success') {
            return "success";
        } else {
            return 'error';
        }
    }

    public function addSubcategoria($detalle, $catPadre, $estado)
    {
        $gestion = new gestionModel();
        $subcat = $gestion->addSubcategoria($detalle, $catPadre, $estado);

        if ($subcat == 'success') {
            return "success";
        } else {
            return 'error';
        }
    }

    public function addMarca($detalle, $estado)
    {
        $gestion = new gestionModel();
        $marca = $gestion->addMarca($detalle, $estado);

        if ($marca == 'success') {
            return "success";
        } else {
            return 'error';
        }
    }

    public function addProveedor($nombre_empresa, $cedula_empresa, $pago_empresa, $ubicacion_empresa, $nombre_proveedor, $telefono_proveedor, $correo_proveedor, $moneda)
    {
        $gestion = new gestionModel();
        $proveedor = $gestion->addProveedor($nombre_empresa, $cedula_empresa, $pago_empresa, $ubicacion_empresa, $nombre_proveedor, $telefono_proveedor, $correo_proveedor, $moneda);

        if ($proveedor == 'success') {
            return "success";
        } else {
            return 'error';
        }
    }

    public function addVehiculo($placa, $marca, $modelo, $fabricacion, $tipo_vehiculo, $chasis, $motor, $km, $seguro, $revision, $observaciones)
    {
        $gestion = new gestionModel();
        $vehiculo = $gestion->addVehiculo($placa, $marca, $modelo, $fabricacion, $tipo_vehiculo, $chasis, $motor, $km, $seguro, $revision, $observaciones);

        if ($vehiculo == 'success') {
            return "success";
        } else {
            return 'error';
        }
    }


    /**
     ********** FUNCIONES PARA ELIMINAR **********
     */

    public function deleteCategoria($id)
    {
        $gestion = new gestionModel();
        $estados = $gestion->deleteCategoria($id);

        if ($estados == 'success') {
            return "success";
        } else {
            return 'error';
        }
    }

    public function deleteSubcategoria($id)
    {
        $gestion = new gestionModel();
        $estados = $gestion->deleteSubcategoria($id);

        if ($estados == 'success') {
            return "success";
        } else {
            return 'error';
        }
    }

    public function deleteMarca($id)
    {
        $gestion = new gestionModel();
        $delete = $gestion->deleteMarca($id);

        if ($delete == 'success') {
            return "success";
        } else {
            return 'error';
        }
    }

    public function deleteProveedor($id)
    {
        $gestion = new gestionModel();
        $delete = $gestion->deleteProveedor($id);

        if ($delete == 'success') {
            return "success";
        } else {
            return 'error';
        }
    }

    public function deleteVehiculo($id)
    {
        $gestion = new gestionModel();
        $delete = $gestion->deleteVehiculo($id);

        if ($delete == 'success') {
            return "success";
        } else {
            return 'error';
        }
    }

    /**
     ********** FUNCIONES PARA EDITAR **********
     */

    public function updateCategoria($detalle, $estado, $id)
    {
        $gestion = new gestionModel();
        $categoria = $gestion->updateCategoria($detalle, $estado, $id);

        if ($categoria == 'success') {
            return "success";
        } else {
            return 'error';
        }
    }

    public function updateSubcategoria($id, $detalle, $catPadre, $estado)
    {
        $gestion = new gestionModel();
        $upadate = $gestion->updateSubcategoria($id, $detalle, $catPadre, $estado);

        if ($upadate == 'success') {
            return "success";
        } else {
            return 'error';
        }
    }

    public function updateMarca($detalle, $estado, $id)
    {
        $gestion = new gestionModel();
        $upadate = $gestion->updateMarca($id, $detalle, $estado);

        if ($upadate == 'success') {
            return "success";
        } else {
            return 'error';
        }
    }

    public function updateProveedor($id, $nombre_empresa, $cedula_empresa, $pago_empresa, $ubicacion_empresa, $nombre_proveedor, $telefono_proveedor, $correo_proveedor, $moneda)
    {
        $gestion = new gestionModel();
        $proveedor = $gestion->updateProveedor($id, $nombre_empresa, $cedula_empresa, $pago_empresa, $ubicacion_empresa, $nombre_proveedor, $telefono_proveedor, $correo_proveedor, $moneda);

        if ($proveedor == 'success') {
            return "success";
        } else {
            return 'error';
        }
    }

    public function updateVehiculo($id, $placa, $marca, $modelo, $fabricacion, $tipo_vehiculo, $chasis, $motor, $km, $seguro, $revision, $observaciones)
    {
        $gestion = new gestionModel();
        $vehiculo = $gestion->updateVehiculo($id, $placa, $marca, $modelo, $fabricacion, $tipo_vehiculo, $chasis, $motor, $km, $seguro, $revision, $observaciones);

        if ($vehiculo == 'success') {
            return "success";
        } else {
            return 'error';
        }
    }
}

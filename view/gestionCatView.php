<?php
require_once '../routes/rutas.php';
session_start();

// Verificación de sesión
if (!isset($_SESSION['usuario'])) {
    header('Location: ' . BASE_PATH . '/index.php');
    exit;
}

// Verificación de nivel de acceso
if (($_SESSION['nivel_acceso'] != 1 && $_SESSION['nivel_acceso'] != 7)) {
?>
    <style>
        #container {
            display: none;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            title: 'Acceso denegado',
            text: 'Permisos insuficientes.',
            icon: 'error',
            timer: 2000,
            timerProgressBar: true,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            backdrop: 'rgba(0, 0, 0, 0.9)'
        }).then(() => {
            window.location.href = '<?= BASE_PATH ?>/dashboard.php';
        });
    </script>
    <?php
    exit; // Para asegurarte que no siga cargando contenido
}

// Incluir el header
require_once 'layout/header.php';
require_once '../controllers/gestionInvController.php';

$controller = new gestionConroller();

$categorias = $controller->getCategorias();
$sub_categorias = $controller->getSubcategoria();
$marcas = $controller->getMarca();
$proveedores = $controller->getProveedor();
$vehiculos = $controller->getVehiculo();

/**
 * Categorias
 */
if (isset($_POST['nuevo'])) {
    $errores = [];
    $camposValidar = [
        'detalle' => 'Detalle',
        'estado' => 'Estado'
    ];

    foreach ($camposValidar as $campo => $nombreCampo) {
        if (empty($_POST[$campo])) {
            $errores[] = $nombreCampo;
        }
    }

    if (empty($errores)) {

        $detalle = $_POST['detalle'];
        $estado = $_POST['estado'];

        $addCategoria = $controller->addCategoria($detalle, $estado);


        if ($addCategoria == 'success') {
?>
            <script>
                let errores = <?= json_encode(implode("\n", $errores)); ?>;
                swal.fire({
                    title: 'Felecicdades',
                    text: 'Se ha registrado la categoría',
                    icon: 'success',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                    }
                })
            </script>
        <?php
        } else {
        ?>
            <script>
                let errores = <?= json_encode(implode("\n", $errores)); ?>;
                swal.fire({
                    title: 'Error',
                    text: 'No se pudo registrar la categoría.',
                    icon: 'error',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                    }
                })
            </script>
        <?php
        }
    } else {
        ?>
        <script>
            let errores = <?= json_encode(implode("\n", $errores)); ?>;
            swal.fire({
                title: 'Error',
                text: 'Los siguientes campos son requeridos: \n ' + errores,
                icon: 'error',
                confirmButtonText: 'Volver',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                }
            })
        </script>
    <?php
    }
}

if (isset($_POST['eliminar'])) {
    $id = $_POST['id'];

    if (empty($id)) {
    ?>
        <script>
            swal.fire({
                title: 'Error',
                text: 'No se recibió el código de la categoria a eliminar',
                icon: 'error',
                confirmButtonText: 'Volver',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                }
            })
        </script>
        <?php
    } else {
        $eliminarHerramienta = $controller->deleteCategoria($id);

        if ($eliminarHerramienta == 'success') {
        ?>
            <script>
                swal.fire({
                    title: 'Felecicdades',
                    text: 'La categoria fue eliminada satisfactoriamente',
                    icon: 'success',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                    }
                })
            </script>
        <?php
        } else {
        ?>
            <script>
                swal.fire({
                    title: 'Lo Sentimos',
                    text: 'No se pudo realizar la solicitud',
                    icon: 'error',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                    }
                })
            </script>
        <?php
        }
    }
}

if (isset($_POST['editar'])) {
    $errores = [];
    $camposValidar = [
        'detalle' => 'Detalle',
        'estado' => 'Estado'
    ];

    foreach ($camposValidar as $campo => $nombreCampo) {
        if (empty($_POST[$campo])) {
            $errores[] = $nombreCampo;
        }
    }

    if (empty($errores)) {

        $detalle = $_POST['detalle'];
        $estado = $_POST['estado'];
        $id = $_POST['id'];

        echo $estado;

        $updateCatregoria = $controller->updateCategoria($detalle, $estado, $id);

        if ($updateCatregoria == 'success') {
        ?>
            <script>
                let errores = <?= json_encode(implode("\n", $errores)); ?>;
                swal.fire({
                    title: 'Felecicdades',
                    text: 'La caegoria fue editada satisfactoriamente',
                    icon: 'success',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                    }
                })
            </script>
        <?php
        } else {
        ?>
            <script>
                let errores = <?= json_encode(implode("\n", $errores)); ?>;
                swal.fire({
                    title: 'Error',
                    text: 'No se pudo editar la categoria.',
                    icon: 'error',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                    }
                })
            </script>
        <?php
        }
    } else {
        ?>
        <script>
            let errores = <?= json_encode(implode("\n", $errores)); ?>;
            swal.fire({
                title: 'Error',
                text: 'Los siguientes campos son requeridos: \n ' + errores,
                icon: 'error',
                confirmButtonText: 'Volver',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                }
            })
        </script>
        <?php
    }
}

/**
 * Subcategorias
 */

if (isset($_POST['nuevo_subcategoria'])) {
    $errores = [];
    $camposValidar = [
        'detalle_subcategoria' => 'Detalle',
        'cat_padre_subcategoria' => 'Categoria Padre',
        'estado_subcategoria' => 'Estado'
    ];

    foreach ($camposValidar as $campo => $nombreCampo) {
        if (empty($_POST[$campo])) {
            $errores[] = $nombreCampo;
        }
    }

    if (empty($errores)) {

        $detalle = $_POST['detalle_subcategoria'];
        $catPadre = $_POST['cat_padre_subcategoria'];
        $estado = $_POST['estado_subcategoria'];

        $addCategoria = $controller->addSubcategoria($detalle, $catPadre, $estado);


        if ($addCategoria == 'success') {
        ?>
            <script>
                let errores = <?= json_encode(implode("\n", $errores)); ?>;
                swal.fire({
                    title: 'Felecicdades',
                    text: 'Se ha registrado la Subcategoría',
                    icon: 'success',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                    }
                })
            </script>
        <?php
        } else {
        ?>
            <script>
                let errores = <?= json_encode(implode("\n", $errores)); ?>;
                swal.fire({
                    title: 'Error',
                    text: 'No se pudo registrar la Subcategoría.',
                    icon: 'error',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                    }
                })
            </script>
        <?php
        }
    } else {
        ?>
        <script>
            let errores = <?= json_encode(implode("\n", $errores)); ?>;
            swal.fire({
                title: 'Error',
                text: 'Los siguientes campos son requeridos: \n ' + errores,
                icon: 'error',
                confirmButtonText: 'Volver',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                }
            })
        </script>
        <?php
    }
}

if (isset($_POST['editar_subcategoria'])) {
    $errores = [];
    $camposValidar = [
        'detalle_subcategoria' => 'Detalle',
        'cat_padre_subcategoria' => 'Categoria Padre',
        'estado_subcategoria' => 'Estado'
    ];

    foreach ($camposValidar as $campo => $nombreCampo) {
        if (empty($_POST[$campo])) {
            $errores[] = $nombreCampo;
        }
    }

    if (empty($errores)) {
        $id = $_POST['id'];
        $detalle = $_POST['detalle_subcategoria'];
        $catPadre = $_POST['cat_padre_subcategoria'];
        $estado = $_POST['estado_subcategoria'];

        $updateSubcategoria = $controller->updateSubcategoria($id, $detalle, $catPadre, $estado);

        if ($updateSubcategoria == 'success') {
        ?>
            <script>
                let errores = <?= json_encode(implode("\n", $errores)); ?>;
                swal.fire({
                    title: 'Felecicdades',
                    text: 'Se ha editado la Subcategoría',
                    icon: 'success',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                    }
                })
            </script>
        <?php
        } else {
        ?>
            <script>
                let errores = <?= json_encode(implode("\n", $errores)); ?>;
                swal.fire({
                    title: 'Error',
                    text: 'No se pudo editar la Subcategoría.',
                    icon: 'error',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                    }
                })
            </script>
        <?php
        }
    } else {
        ?>
        <script>
            let errores = <?= json_encode(implode("\n", $errores)); ?>;
            swal.fire({
                title: 'Error',
                text: 'Los siguientes campos son requeridos: \n ' + errores,
                icon: 'error',
                confirmButtonText: 'Volver',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                }
            })
        </script>
    <?php
    }
}

if (isset($_POST['eliminar'])) {
    $id = $_POST['id'];

    if (empty($id)) {
    ?>
        <script>
            swal.fire({
                title: 'Error',
                text: 'No se recibió el código de la subcategoria a eliminar',
                icon: 'error',
                confirmButtonText: 'Volver',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                }
            })
        </script>
        <?php
    } else {
        $eliminarHerramienta = $controller->deleteSubcategoria($id);

        if ($eliminarHerramienta == 'success') {
        ?>
            <script>
                swal.fire({
                    title: 'Felecicdades',
                    text: 'La subcategoria fue eliminada satisfactoriamente',
                    icon: 'success',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                    }
                })
            </script>
        <?php
        } else {
        ?>
            <script>
                swal.fire({
                    title: 'Lo Sentimos',
                    text: 'No se pudo realizar la solicitud',
                    icon: 'error',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                    }
                })
            </script>
        <?php
        }
    }
}

/**
 * Marcas
 */
if (isset($_POST['nuevo_marca'])) {
    $errores = [];
    $camposValidar = [
        'detalle_marca' => 'Detalle',
        'estado_marca' => 'Categoria Padre'
    ];

    foreach ($camposValidar as $campo => $nombreCampo) {
        if (empty($_POST[$campo])) {
            $errores[] = $nombreCampo;
        }
    }

    if (empty($errores)) {

        $detalle = $_POST['detalle_marca'];
        $estado = $_POST['estado_marca'];

        $addMarca = $controller->addMarca($detalle, $estado);


        if ($addMarca == 'success') {
        ?>
            <script>
                let errores = <?= json_encode(implode("\n", $errores)); ?>;
                swal.fire({
                    title: 'Felecicdades',
                    text: 'Se ha registrado la marca',
                    icon: 'success',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                    }
                })
            </script>
        <?php
        } else {
        ?>
            <script>
                let errores = <?= json_encode(implode("\n", $errores)); ?>;
                swal.fire({
                    title: 'Error',
                    text: 'No se pudo registrar la marca.',
                    icon: 'error',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                    }
                })
            </script>
        <?php
        }
    } else {
        ?>
        <script>
            let errores = <?= json_encode(implode("\n", $errores)); ?>;
            swal.fire({
                title: 'Error',
                text: 'Los siguientes campos son requeridos: \n ' + errores,
                icon: 'error',
                confirmButtonText: 'Volver',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                }
            })
        </script>
        <?php
    }
}

if (isset($_POST['editar_marca'])) {
    $errores = [];
    $camposValidar = [
        'detalle_marca' => 'Detalle',
        'estado_marca' => 'Estado'
    ];

    foreach ($camposValidar as $campo => $nombreCampo) {
        if (empty($_POST[$campo])) {
            $errores[] = $nombreCampo;
        }
    }

    if (empty($errores)) {

        $detalle = $_POST['detalle_marca'];
        $estado = $_POST['estado_marca'];
        $id = $_POST['id'];

        echo $estado;

        $updateMarca = $controller->updateMarca($detalle, $estado, $id);

        if ($updateMarca == 'success') {
        ?>
            <script>
                let errores = <?= json_encode(implode("\n", $errores)); ?>;
                swal.fire({
                    title: 'Felecicdades',
                    text: 'La caegoria fue editada satisfactoriamente',
                    icon: 'success',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                    }
                })
            </script>
        <?php
        } else {
        ?>
            <script>
                let errores = <?= json_encode(implode("\n", $errores)); ?>;
                swal.fire({
                    title: 'Error',
                    text: 'No se pudo editar la categoria.',
                    icon: 'error',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                    }
                })
            </script>
        <?php
        }
    } else {
        ?>
        <script>
            let errores = <?= json_encode(implode("\n", $errores)); ?>;
            swal.fire({
                title: 'Error',
                text: 'Los siguientes campos son requeridos: \n ' + errores,
                icon: 'error',
                confirmButtonText: 'Volver',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                }
            })
        </script>
    <?php
    }
}

if (isset($_POST['eliminar_marca'])) {
    $id = $_POST['id'];

    if (empty($id)) {
    ?>
        <script>
            swal.fire({
                title: 'Error',
                text: 'No se recibió el código de la marca a eliminar',
                icon: 'error',
                confirmButtonText: 'Volver',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                }
            })
        </script>
        <?php
    } else {
        $deleteMara = $controller->deleteMarca($id);

        if ($deleteMara == 'success') {
        ?>
            <script>
                swal.fire({
                    title: 'Felecicdades',
                    text: 'La marca fue eliminada satisfactoriamente',
                    icon: 'success',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                    }
                })
            </script>
        <?php
        } else {
        ?>
            <script>
                swal.fire({
                    title: 'Lo Sentimos',
                    text: 'No se pudo realizar la solicitud',
                    icon: 'error',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                    }
                })
            </script>
        <?php
        }
    }
}

/**
 * Proveedores
 */

if (isset($_POST['nuevo_prov'])) {
    $errores = [];
    $camposValidar = [
        'nombre_empr' => 'Nombre de la Empresa',
        'cedula_empr' => 'Identificación',
        'correo_empr' => 'Correo de la Empresa',
        'condiciones_pago' => 'Condiciones de Pago',
        'ubicacion' => 'Ubicación',
        'nombre_prov' => 'Nombre del Proveedor',
        'telefono_prov' => 'Teléfono del Proveedor',
        'correo_prov' => 'Correo del Proveedor',
        'moneda' => 'Tipo de Moneda'
    ];

    foreach ($camposValidar as $campo => $nombreCampo) {
        if (empty($_POST[$campo])) {
            $errores[] = $nombreCampo;
        }
    }

    if (empty($errores)) {

        $nombre_empresa = $_POST['nombre_empr'];
        $cedula_empresa = $_POST['cedula_empr'];
        $pago_empresa = $_POST['condiciones_pago'];
        $ubicacion_empresa = $_POST['ubicacion'];
        $nombre_proveedor = $_POST['nombre_prov'];
        $telefono_proveedor = $_POST['telefono_prov'];
        $correo_proveedor = $_POST['correo_prov'];
        $moneda = $_POST['moneda'];

        $proveedor = $controller->addProveedor($nombre_empresa, $cedula_empresa, $pago_empresa, $ubicacion_empresa, $nombre_proveedor, $telefono_proveedor, $correo_proveedor, $moneda);


        if ($proveedor == 'success') {
        ?>
            <script>
                let errores = <?= json_encode(implode("\n", $errores)); ?>;
                swal.fire({
                    title: 'Felecicdades',
                    text: 'Se ha registrado el proveedor',
                    icon: 'success',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                    }
                })
            </script>
        <?php
        } else {
        ?>
            <script>
                let errores = <?= json_encode(implode("\n", $errores)); ?>;
                swal.fire({
                    title: 'Error',
                    text: 'No se pudo registrar al proveedor.',
                    icon: 'error',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                    }
                })
            </script>
        <?php
        }
    } else {
        ?>
        <script>
            let errores = <?= json_encode(implode("\n", $errores)); ?>;
            swal.fire({
                title: 'Error',
                text: 'Los siguientes campos son requeridos: \n ' + errores,
                icon: 'error',
                confirmButtonText: 'Volver',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                }
            })
        </script>
        <?php
    }
}

if (isset($_POST['editar_prov'])) {
    $errores = [];
    $camposValidar = [
        'nombre_empr' => 'Nombre de la Empresa',
        'cedula_empr' => 'Identificación',
        'correo_empr' => 'Correo de la Empresa',
        'condiciones_pago' => 'Condiciones de Pago',
        'ubicacion' => 'Ubicación',
        'nombre_prov' => 'Nombre del Proveedor',
        'telefono_prov' => 'Teléfono del Proveedor',
        'correo_prov' => 'Correo del Proveedor',
        'moneda' => 'Tipo de Moneda'
    ];

    foreach ($camposValidar as $campo => $nombreCampo) {
        if (empty($_POST[$campo])) {
            $errores[] = $nombreCampo;
        }
    }

    if (empty($errores)) {

        $id = $_POST['id'];
        $nombre_empresa = $_POST['nombre_empr'];
        $cedula_empresa = $_POST['cedula_empr'];
        $pago_empresa = $_POST['condiciones_pago'];
        $ubicacion_empresa = $_POST['ubicacion'];
        $nombre_proveedor = $_POST['nombre_prov'];
        $telefono_proveedor = $_POST['telefono_prov'];
        $correo_proveedor = $_POST['correo_prov'];
        $moneda = $_POST['moneda'];

        $proveedor = $controller->updateProveedor($id, $nombre_empresa, $cedula_empresa, $pago_empresa, $ubicacion_empresa, $nombre_proveedor, $telefono_proveedor, $correo_proveedor, $moneda);


        if ($proveedor == 'success') {
        ?>
            <script>
                let errores = <?= json_encode(implode("\n", $errores)); ?>;
                swal.fire({
                    title: 'Felecicdades',
                    text: 'Se ha registrado el proveedor',
                    icon: 'success',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                    }
                })
            </script>
        <?php
        } else {
        ?>
            <script>
                let errores = <?= json_encode(implode("\n", $errores)); ?>;
                swal.fire({
                    title: 'Error',
                    text: 'No se pudo registrar al proveedor.',
                    icon: 'error',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                    }
                })
            </script>
        <?php
        }
    } else {
        ?>
        <script>
            let errores = <?= json_encode(implode("\n", $errores)); ?>;
            swal.fire({
                title: 'Error',
                text: 'Los siguientes campos son requeridos: \n ' + errores,
                icon: 'error',
                confirmButtonText: 'Volver',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                }
            })
        </script>
    <?php
    }
}

if (isset($_POST['eliminar_prov'])) {
    $id = $_POST['id'];

    if (empty($id)) {
    ?>
        <script>
            swal.fire({
                title: 'Error',
                text: 'No se recibió el código del proveedor a eliminar',
                icon: 'error',
                confirmButtonText: 'Volver',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                }
            })
        </script>
        <?php
    } else {
        $deleteMara = $controller->deleteProveedor($id);

        if ($deleteMara == 'success') {
        ?>
            <script>
                swal.fire({
                    title: 'Felecicdades',
                    text: 'El proveedor fue eliminado satisfactoriamente',
                    icon: 'success',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                    }
                })
            </script>
        <?php
        } else {
        ?>
            <script>
                swal.fire({
                    title: 'Lo Sentimos',
                    text: 'No se pudo realizar la solicitud',
                    icon: 'error',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                    }
                })
            </script>
        <?php
        }
    }
}

/**
 * Vehículos
 */

if (isset($_POST['nuevo_vehiculo'])) {
    $errores = [];
    $camposValidar = [
        'placa' => 'Placa',
        'marca' => 'Marca',
        'modelo' => 'Modelo',
        'anio_fabrica' => 'Año',
        'tipo_vehiculo' => 'Tipo de Vehículo',
        'num_chasis' => 'Número de Chasis',
        'num_motor' => 'Número de Motor',
        'KM_vehicuo' => 'Kilometraje',
        'fecha_revision' => 'Rvisión Técnica'
    ];

    foreach ($camposValidar as $campo => $nombreCampo) {
        if (empty($_POST[$campo])) {
            $errores[] = $nombreCampo;
        }
    }

    if (empty($errores)) {

        $placa = $_POST['placa'];
        $marca = $_POST['marca'];
        $modelo = $_POST['modelo'];
        $fabricacion = $_POST['anio_fabrica'];
        $tipo_vehiculo = $_POST['tipo_vehiculo'];
        $chasis = $_POST['num_chasis'];
        $motor = $_POST['num_motor'];
        $km = $_POST['KM_vehicuo'];
        $seguro = $_POST['fecha_seguro'];
        $revision = $_POST['fecha_revision'];
        $observaciones = $_POST['observaciones'];

        $vehiculo = $controller->addVehiculo($placa, $marca, $modelo, $fabricacion, $tipo_vehiculo, $chasis, $motor, $km, $seguro, $revision, $observaciones);


        if ($vehiculo == 'success') {
        ?>
            <script>
                let errores = <?= json_encode(implode("\n", $errores)); ?>;
                swal.fire({
                    title: 'Felecicdades',
                    text: 'Se ha registrado el vehículo',
                    icon: 'success',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                    }
                })
            </script>
        <?php
        } else {
        ?>
            <script>
                let errores = <?= json_encode(implode("\n", $errores)); ?>;
                swal.fire({
                    title: 'Error',
                    text: 'No se pudo registrar el vehículo.',
                    icon: 'error',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                    }
                })
            </script>
        <?php
        }
    } else {
        ?>
        <script>
            let errores = <?= json_encode(implode("\n", $errores)); ?>;
            swal.fire({
                title: 'Error',
                text: 'Los siguientes campos son requeridos: \n ' + errores,
                icon: 'error',
                confirmButtonText: 'Volver',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                }
            })
        </script>
        <?php
    }
}

if (isset($_POST['editar_vehiculo'])) {
    $errores = [];
    $camposValidar = [
        'placa' => 'Placa',
        'marca' => 'Marca',
        'modelo' => 'Modelo',
        'anio_fabrica' => 'Año',
        'tipo_vehiculo' => 'Tipo de Vehículo',
        'num_chasis' => 'Número de Chasis',
        'num_motor' => 'Número de Motor',
        'KM_vehicuo' => 'Kilometraje',
        'fecha_revision' => 'Rvisión Técnica'
    ];

    foreach ($camposValidar as $campo => $nombreCampo) {
        if (empty($_POST[$campo])) {
            $errores[] = $nombreCampo;
        }
    }

    if (empty($errores)) {

        $id = $_POST['id'];
        $placa = $_POST['placa'];
        $marca = $_POST['marca'];
        $modelo = $_POST['modelo'];
        $fabricacion = $_POST['anio_fabrica'];
        $tipo_vehiculo = $_POST['tipo_vehiculo'];
        $chasis = $_POST['num_chasis'];
        $motor = $_POST['num_motor'];
        $km = $_POST['KM_vehicuo'];
        $seguro = $_POST['fecha_seguro'];
        $revision = $_POST['fecha_revision'];
        $observaciones = $_POST['observaciones'];

        $vehiculo = $controller->updateVehiculo($id, $placa, $marca, $modelo, $fabricacion, $tipo_vehiculo, $chasis, $motor, $km, $seguro, $revision, $observaciones);


        if ($vehiculo == 'success') {
        ?>
            <script>
                let errores = <?= json_encode(implode("\n", $errores)); ?>;
                swal.fire({
                    title: 'Felecicdades',
                    text: 'Se ha editado el vehículo',
                    icon: 'success',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                    }
                })
            </script>
        <?php
        } else {
        ?>
            <script>
                let errores = <?= json_encode(implode("\n", $errores)); ?>;
                swal.fire({
                    title: 'Error',
                    text: 'No se pudo editar el vehículo.',
                    icon: 'error',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                    }
                })
            </script>
        <?php
        }
    } else {
        ?>
        <script>
            let errores = <?= json_encode(implode("\n", $errores)); ?>;
            swal.fire({
                title: 'Error',
                text: 'Los siguientes campos son requeridos: \n ' + errores,
                icon: 'error',
                confirmButtonText: 'Volver',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                }
            })
        </script>
    <?php
    }
}

if (isset($_POST['eliminar_vehiculo'])) {
    $id = $_POST['id'];

    if (empty($id)) {
    ?>
        <script>
            swal.fire({
                title: 'Error',
                text: 'No se recibió el código del vehiculo a eliminar',
                icon: 'error',
                confirmButtonText: 'Volver',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                }
            })
        </script>
        <?php
    } else {
        $deleteVehiculo = $controller->deleteVehiculo($id);

        if ($deleteVehiculo == 'success') {
        ?>
            <script>
                swal.fire({
                    title: 'Felecicdades',
                    text: 'El vehículo fue eliminado satisfactoriamente',
                    icon: 'success',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                    }
                })
            </script>
        <?php
        } else {
        ?>
            <script>
                swal.fire({
                    title: 'Lo Sentimos',
                    text: 'No se pudo realizar la solicitud',
                    icon: 'error',
                    confirmButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_PATH ?>/getionCat.php';
                    }
                })
            </script>
<?php
        }
    }
}

?>

<!-- ! Main -->
<main class="main users chart-page" id="skip-target">
    <div class="container">

        <h2 class="main-title text-center">Gestión del Inventario</h2>

        <div class="row mb-3 align-items-end">
            <!-- Columna para el select, alineado siempre a la derecha -->
            <div class="col-12">
                <div class="gestion-inventario d-flex justify-content-end">
                    <select class="form-select form-select-sm w-auto select-elegante" id="filtroSelect">
                        <option value="17" data-estado-asi="17">Categoría</option>
                        <option value="18" data-estado-asi="18">Subcategoría</option>
                        <option value="19" data-estado-asi="19">Marcas</option>
                        <option value="20" data-estado-asi="20">Proveedores</option>
                        <option value="21" data-estado-asi="21">Busetas</option>
                    </select>
                </div>

            </div>
        </div>

        <div class="tabla-inventarios" id="tabla-inventarios">

            <div class="row stat-cards">
                <div class="col-md-2 col-xl-3">
                    <!-- El estado 4 indica que es para agregar -->
                    <button class="nueva_categoria" data-bs-toggle="modal" data-bs-target="#modalNuevaHerramienta" data-estado-agregar="4" data-identificador-agregar="1" style="border: none; background: none;">
                        <article class="stat-cards-item">
                            <div class="icono_nuevo">
                                <i data-feather="plus" style="color: white;"></i>
                            </div>
                            <div class="stat-cards-info">
                                <p class="stat-cards-info__num m-2">Nueva Categoria</p>
                            </div>
                        </article>
                    </button>
                </div>
            </div>

            <hr class="line mt-1 mb-2 pb-2">

            <h6 class="indicador m-2 p-2"><b><i>Categorias.</i></b></h6>

            <div class="row mb-3">
                <!-- Buscador al centro -->
                <div class="col-md-2 text-center contenedor-busqueda" id="contenedor-busqueda"></div>

                <!-- Botones + length en la misma columna -->
                <div class="col-md-2 text-end contenedor-botones">
                    <div id="contenedor-botones"></div>
                </div>
            </div>

            <table id="tablaTodos" class="display mt-2" style="width: 100%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Detalle</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (is_array($categorias)): ?>
                        <?php foreach ($categorias as $cat): ?>
                            <tr>
                                <td data-label="ID"><?= $cat['catg_id'] ?></td>
                                <td data-label="Detalle"><?= $cat['catg_detalle'] ?></td>
                                <td data-label="Estado"><?= $cat['est_detalle'] ?></td>
                                <td class="text-center align-middle" data-label="Acciones">
                                    <div class="d-flex justify-content-center align-items-center gap-1">
                                        <button class="editar_categoria btn btn-sm btn-outline-primary" title="Editar"
                                            data-bs-toggle="modal" data-bs-target="#modalEditar"
                                            data-estado-editar="5" data-identificador-editar="1" data-editar-id="<?= $cat['catg_id'] ?>">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <button class="eliminar_categoria btn btn-sm btn-outline-danger" title="Eliminar"
                                            data-bs-toggle="modal" data-bs-target="#modalEliminar"
                                            data-estado-eliminar="6" data-identificador-eliminar="1" data-id="<?= $cat['catg_id'] ?>" data-codigo="<?= $cat['catg_detalle'] ?>">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No hay categorias para mostrar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>

            <div class="row mt-3">
                <div class="col-md-6 contenedor-info" id="contenedor-info"></div>
                <div class="col-md-6 text-end contenedor-paginacion" id="contenedor-paginacion"></div>
            </div>
        </div>

    </div>

    <!-- Inicio Modal Producto Nuevo -->
    <div class="modal fade" id="modalNuevaHerramienta" tabindex="-1" aria-labelledby="modalNuevaHerramientaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header p-2">
                    <h6 class="modal-title" id="modalNuevaHerramientaLabel"><b>Formulario para Agregar</b></h6>
                    <button type="button" class="btn-close p-1 me-2 mt-1" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div id="formulario-nuevo">

                </div>
            </div>
        </div>
    </div>
    <!-- Fin Modal Producto Nuevo -->

    <!-- Inicio Modal Editar -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">

                <div class="modal-header p-2">
                    <h6 class="modal-title" id="modalNuevoProductoLabel"><b>Formulario para Editar</b></h6>
                    <button type="button" class="btn-close p-1 me-2 mt-1" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div id="formulario-editar">

                </div>

            </div>
        </div>
    </div>
    <!-- Fin Modal Editar -->

    <!-- Inicio Modal Eliminar -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header py-1 px-2">
                    <h5 class="modal-title d-flex align-items-center">
                        <i class="me-2" data-feather="alert-triangle"></i> ¿Estás seguro de eliminar?
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div id="formularioEliminar">

                </div>
            </div>
        </div>
    </div>
    <!-- Inicio Modal Eliminar -->


</main>

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- Botones -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

<!-- Exportación -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<!-- Código JavaScript -->
<script>
    $(document).ready(function() {

        const tabla = $('#tablaTodos').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
                paginate: {
                    previous: '<', // reemplaza "Anterior" por "<"
                    next: '>' // reemplaza "Siguiente" por ">"
                }
            },
            dom: '<"wrapper"Bfrtip>',
            buttons: [{
                    extend: 'copy',
                    text: 'Copiar'
                },
                {
                    extend: 'excel',
                    text: 'Excel'
                },
                {
                    extend: 'pdf',
                    text: 'PDF'
                }
            ],
            pageLength: 10,
        });

        // Mover componentes a sus contenedores
        tabla.buttons().container().appendTo('#contenedor-botones');
        $('#tablaTodos_filter').appendTo('#contenedor-busqueda');
        $('#tablaTodos_info').appendTo('#contenedor-info');
        $('#tablaTodos_paginate').appendTo('#contenedor-paginacion');
    });
</script>

<!-- Petición Ajax para la tabla filtros -->
<script>
    // Configurar los botones para hacer la petición AJAX
    // Configurar el filtro para hacer la petición AJAX cuando se seleccione una opción
    document.querySelector('#filtroSelect').addEventListener('change', function() {
        const estado = this.value; // Obtiene el valor de la opción seleccionada
        const contenedor = document.getElementById('tabla-inventarios');

        const valor = this.value;
        if (valor === '17') {
            window.location.href = window.location.pathname + '?filtro=' + valor;
        }

        // Muestra "Cargando..." mientras obtiene el contenido
        contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

        fetch('ajax/filtroGestionInv.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'estado=' + encodeURIComponent(estado)
            })
            .then(res => res.text())
            .then(data => {
                contenedor.innerHTML = data;

                const tabla = $('#tablaFiltro').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
                        paginate: {
                            previous: '<', // reemplaza "Anterior" por "<"
                            next: '>' // reemplaza "Siguiente" por ">"
                        }
                    },
                    dom: '<"wrapper"Bfrtip>',
                    buttons: [{
                            extend: 'copy',
                            text: 'Copiar'
                        },
                        {
                            extend: 'excel',
                            text: 'Excel'
                        },
                        {
                            extend: 'pdf',
                            text: 'PDF'
                        }
                    ],
                    pageLength: 10,
                    columnDefs: [{
                        targets: [0, -1],
                        orderable: false,
                        searchable: false
                    }],
                    drawCallback: function() {
                        // Re-inicializa tooltips, dropdowns o eventos cuando cambia la página
                        $('[data-bs-toggle="dropdown"]').dropdown();
                    }
                });

                // Mover componentes a sus contenedores
                tabla.buttons().container().appendTo('#contenedor-botones');
                $('#tablaAsignado_filter').appendTo('#contenedor-busqueda');
                $('#tablaAsignado_info').appendTo('#contenedor-info');
                $('#tablaAsignado_paginate').appendTo('#contenedor-paginacion');

            })
            .catch(err => {
                contenedor.innerHTML = '<div class="text-danger">Error al cargar la tabla.</div>';
                console.error('Error AJAX:', err);
            });
    });
</script>

<!-- Petición Ajax Nueva Categoria -->
<script>
    // Petición a Formulario Nuevo Producto.
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.nueva_categoria');
        if (btn) {
            const estado = btn.getAttribute('data-estado-agregar');
            const identificador = btn.getAttribute('data-identificador-agregar');
            const contenedor = document.getElementById('formulario-nuevo');

            contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

            fetch('ajax/formulariosGestionInv.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'estado=' + encodeURIComponent(estado) + '&identificador=' + encodeURIComponent(identificador)
                })
                .then(res => res.text())
                .then(data => {
                    contenedor.innerHTML = data;

                    flatpickr("#fecha_seguro", {
                        dateFormat: "Y-m-d"
                    });

                    flatpickr("#fecha_revision", {
                        dateFormat: "Y-m-d"
                    });

                    // CKEditor
                    ClassicEditor
                        .create(document.querySelector('#observaciones'))
                        .catch(error => {
                            console.error(error);
                        });

                    // Mostrar modal si aplica
                    requestAnimationFrame(() => {
                        const modalNuevoElement = document.getElementById('modalNuevo');
                        if (modalNuevoElement) {
                            const modal = bootstrap.Modal.getOrCreateInstance(modalNuevoElement);

                            modalNuevoElement.addEventListener('shown.bs.modal', function() {
                                const btnClose = modalNuevoElement.querySelector('.btn-close');
                                if (btnClose) {
                                    btnClose.focus();
                                }
                            });

                            modal.show();
                        }
                    });
                })
                .catch(err => {
                    contenedor.innerHTML = '<div class="text-danger">Error al cargar el formulario.</div>';
                    console.error('Error AJAX:', err);
                });
        }
    });
</script>

<!-- Petición Ajax Editar Categoria -->
<script>
    // Petición a Editar Producto
    document.addEventListener('click', function(e) {
        if (e.target.closest('.editar_categoria')) {
            const btn = e.target.closest('.editar_categoria');
            const estado = btn.getAttribute('data-estado-editar');
            const identificador = btn.getAttribute('data-identificador-editar');
            const editarID = parseInt(btn.getAttribute('data-editar-id'), 10);

            console.log('ID:', editarID);
            console.log('Identificador:', identificador);

            const contenedor = document.getElementById('formulario-editar');
            contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

            fetch('ajax/formulariosGestionInv.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'estado=' + encodeURIComponent(estado) + '&identificador=' + encodeURIComponent(identificador)
                })
                .then(res => res.text())
                .then(data => {
                    contenedor.innerHTML = data;

                    requestAnimationFrame(() => {
                        funcionesFormulario_Editar(editarID, identificador);

                        flatpickr("#fecha_seguro", {
                            dateFormat: "Y-m-d"
                        });

                        flatpickr("#fecha_revision", {
                            dateFormat: "Y-m-d"
                        });

                        // CKEditor
                        ClassicEditor
                            .create(document.querySelector('#observaciones'))
                            .catch(error => {
                                console.error(error);
                            });

                        const modalEditarElement = document.getElementById('modalEditar');
                        const modal = bootstrap.Modal.getOrCreateInstance(modalEditarElement);

                        modalEditarElement.addEventListener('shown.bs.modal', function() {
                            const btnClose = modalEditarElement.querySelector('.btn-close');
                            if (btnClose) {
                                btnClose.focus();
                            }
                        });

                        modal.show();
                    });
                })
                .catch(err => {
                    contenedor.innerHTML = '<div class="text-danger">Error al cargar el formulario.</div>';
                    console.error('Error AJAX:', err);
                });
        }
    });

    function funcionesFormulario_Editar(editarID, identificador) {
        switch (parseInt(identificador)) {
            case 1: {
                const dato_editar = <?= json_encode($categorias) ?>;
                const categoria = dato_editar.find(p => p.catg_id == editarID);

                if (!categoria) {
                    console.error('Categoría no encontrada:', editarID);
                    return;
                }

                document.getElementById('id').value = categoria.catg_id;
                document.getElementById('detalle').value = categoria.catg_detalle;
                document.getElementById('estado').value = categoria.catg_est_idEstado;
                break;
            }

            case 2: {
                const dato_editar = <?= json_encode($sub_categorias) ?>;
                const subcategoria = dato_editar.find(p => p.scat_id == editarID); // 👈 corregido aquí

                if (!subcategoria) {
                    console.error('Subcategoría no encontrada:', editarID);
                    return;
                }

                document.getElementById('id').value = subcategoria.scat_id;
                document.getElementById('detalle_subcategoria').value = subcategoria.scat_detalle;
                document.getElementById('cat_padre_subcategoria').value = subcategoria.scat_catg_catgPadre;
                document.getElementById('estado_subcategoria').value = subcategoria.scat_est_idEstado;
                break;
            }

            case 3: {
                const dato_editar = <?= json_encode($marcas) ?>;
                const marca = dato_editar.find(p => p.marc_id == editarID);


                if (!marca) {
                    console.error('Marca no encontrada:', editarID);
                    return;
                }

                document.getElementById('id').value = marca.marc_id;
                document.getElementById('detalle_marca').value = marca.marc_detalle;
                document.getElementById('estado_marca').value = marca.marc_est_idEstado;
                break;
            }

            case 4: {
                const dato_editar = <?= json_encode($proveedores) ?>;
                const provedor = dato_editar.find(p => p.prov_id == editarID);

                if (!provedor) {
                    console.error('Marca no encontrada:', editarID);
                    return;
                }

                document.getElementById('id').value = provedor.prov_id;
                document.getElementById('nombre_empr').value = provedor.prov_empresa;
                document.getElementById('cedula_empr').value = provedor.prov_identificacion;
                document.getElementById('correo_empr').value = provedor.prov_correo;
                document.getElementById('condiciones_pago').value = provedor.prov_condiciones_pago;
                document.getElementById('ubicacion').value = provedor.prov_direccion;
                document.getElementById('nombre_prov').value = provedor.prov_contacto_nombre;
                document.getElementById('telefono_prov').value = provedor.prov_contacto_telefono;
                document.getElementById('correo_prov').value = provedor.prov_contacto_correo;
                document.getElementById('moneda').value = provedor.prov_moneda_preferida;

                break;
            }

            case 5: {
                const dato_editar = <?= json_encode($vehiculos) ?>;
                const vehiculo = dato_editar.find(p => p.veh_id == editarID);

                if (!vehiculo) {
                    console.error('Vehiculo no encontrado:', editarID);
                    return;
                }

                document.getElementById('id').value = vehiculo.veh_id;
                document.getElementById('placa').value = vehiculo.veh_placa;
                document.getElementById('marca').value = vehiculo.veh_marca;
                document.getElementById('modelo').value = vehiculo.veh_modelo;
                document.getElementById('anio_fabrica').value = vehiculo.veh_anio;
                document.getElementById('tipo_vehiculo').value = vehiculo.veh_tipo;
                document.getElementById('num_chasis').value = vehiculo.veh_num_chasis;
                document.getElementById('num_motor').value = vehiculo.veh_num_motor;
                document.getElementById('KM_vehicuo').value = vehiculo.veh_kilometraje;
                document.getElementById('fecha_seguro').value = vehiculo.veh_fecha_vencimiento_seguro;
                document.getElementById('fecha_revision').value = vehiculo.veh_fecha_revision;
                document.getElementById('observaciones').value = vehiculo.veh_observaciones;

                break;
            }



            default:
                console.warn('Identificador no manejado:', identificador);
                break;
        }
    }
</script>

<!-- Petición Ajax Eliminar Categoria -->
<script>
    document.addEventListener('click', function(e) {
        // Verificamos si el elemento clickeado es un botón de eliminación
        if (e.target.closest('.eliminar_categoria')) {
            const btn = e.target.closest('.eliminar_categoria'); // El botón de eliminación que fue clickeado
            const estado = btn.getAttribute('data-estado-eliminar');
            const identificador = btn.getAttribute('data-identificador-eliminar');
            const id = btn.getAttribute('data-id');
            const codigo = btn.getAttribute('data-codigo');
            const contenedor = document.getElementById('formularioEliminar');

            console.log(codigo);

            // Mostrar mensaje de carga
            contenedor.innerHTML = '<div class="text-center">Cargando...</div>';

            // Hacer petición AJAX para cargar el formulario de eliminación
            fetch('ajax/formulariosGestionInv.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'estado=' + encodeURIComponent(estado) + '&identificador=' + encodeURIComponent(identificador)
                })
                .then(res => res.text())
                .then(data => {
                    // Insertar el contenido recibido en el contenedor
                    contenedor.innerHTML = data;

                    // Llamar a la función que maneja el formulario del modal
                    funcioneFormulario_eliminar(id, codigo);

                    // Mostrar el modal de eliminación
                    const modalEliminarElement = document.getElementById('modalEliminar');
                    const modal = bootstrap.Modal.getOrCreateInstance(modalEliminarElement);
                    modal.show();
                })
                .catch(err => {
                    contenedor.innerHTML = '<div class="text-danger">Error al cargar el formulario de eliminación.</div>';
                    console.error('Error AJAX:', err);
                });
        }
    });

    function funcioneFormulario_eliminar(id, codigo) {
        // Aquí puedes gestionar el formulario de eliminación con los valores que recibas

        document.getElementById('eliminarId').value = id;
        document.getElementById('codigoEliminarTexto').textContent = codigo;
    }
</script>


<?php
// Incluir el footer
require_once 'layout/footer.php';
?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/cwSeguridadHC/routes/rutas.php';
$usuario = $_SESSION['usuario'];

if (!empty($usuario)) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/cwSeguridadHC/controllers/loginController.php';
    //require_once BASE_PATH . '/controllers/loginController.php';

    $controller = new loginController();
    $getUser = $controller->getUsario($usuario);

    if ($getUser != 'error') {
        $datos_usuario = $getUser;
    } else {
        $datos_usuario = '';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguridad HC</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= IMG_SVG_PATH ?>/logo.png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons"></script>
    <!-- FontAwesome desde CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Bootstraps 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- CSS del DataTable -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <!-- jQuery y JS de DataTables -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- Editor del TextArea -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <!-- SweatAler 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- Bootstrap estilos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
    <!-- Custom styles -->
    <link rel="stylesheet" href="<?= CSS_PATH ?>/style.min.css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>/style.css">
</head>

<body>
    <div class="layer"></div>
    <!-- ! Body -->
    <a class="skip-link sr-only" href="#skip-target">Skip to content</a>
    <div class="page-flex">
        <!-- ! Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-start">

                <!-- Inicio del encabezado del menú-->
                <div class="sidebar-head">
                    <!-- logo en el menu, también lleva al dashboard -->
                    <div class="logo-wrapper text-center">
                        <a href="<?= VIEW_PATH ?> /dashboardView.php" title="Home">
                            <img src="<?= IMG_SVG_PATH ?>/logo.png" alt="Logo" style="width: 50px; height: 50px; margin-right: 8px; vertical-align: middle;">

                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="logo-text">
                                    <span class="logo-title">Seguridad HC</span>
                                    <span class="logo-subtitle">Panel de Control</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <button class="sidebar-toggle transparent-btn mt-2" title="Menu" type="button">
                        <span class="sr-only">Toggle menu</span>
                        <span class="icon menu-toggle" aria-hidden="true"></span>
                    </button>
                </div>
                <!-- Fin del encabezado del menú-->

                <div class="sidebar-body">

                    <!-- Inicio del cuerpo del menú -->
                    <ul class="sidebar-body-menu">

                        <!-- inicio ir al panel de control-->
                        <li>
                            <a class="active" href="<?= VIEW_PATH ?> /dashboardView.php"><span class="icon home" aria-hidden="true"></span>Panel de Control</a>
                        </li>
                        <!-- fin ir al panel de control -->

                        <!-- Inicio del modulo de inventario -->
                        <li>
                            <a class="show-cat-btn" href="##">
                                <span class="icon document" aria-hidden="true"></span>Inventario
                                <span class="category__btn transparent-btn" title="Open list">
                                    <span class="sr-only">Open list</span>
                                    <span class="icon arrow-down" aria-hidden="true"></span>
                                </span>
                            </a>
                            <ul class="cat-sub-menu">
                                <li>
                                    <a href="<?= BASE_PATH ?> /invElectronicos.php">Inventario Eletrónico</a>
                                </li>
                                <li>
                                    <a href="<?= BASE_PATH ?> /invCampo.php">Inventario Campo</a>
                                </li>
                                <li>
                                    <a href="<?= BASE_PATH ?> /invSeguridad.php">Inventario Seguridad</a>
                                </li>
                                <li>
                                    <a href="<?= BASE_PATH ?> /getionCat.php">Gestión Catálogo</a>

                                </li>
                            </ul>
                        </li>
                        <!-- Fin del modulo de inventario -->

                        <!-- Incio del modulo de coordinación -->
                        <li>
                            <a class="show-cat-btn" href="##">
                                <span class="icon document" aria-hidden="true"></span>Coordinación
                                <span class="category__btn transparent-btn" title="Open list">
                                    <span class="sr-only">Open list</span>
                                    <span class="icon arrow-down" aria-hidden="true"></span>
                                </span>
                            </a>
                            <ul class="cat-sub-menu">
                                <li>
                                    <a href="<?= BASE_PATH ?> /reportSalidas.php">Visitas Técnicas</a>
                                </li>
                            </ul>
                        </li>
                        <!-- Fin del modulo de coordinación -->

                        <!-- Incio del modulo de Supervisión -->
                        <li>
                            <a class="show-cat-btn" href="##">
                                <span class="icon document" aria-hidden="true"></span>Supervisión
                                <span class="category__btn transparent-btn" title="Open list">
                                    <span class="sr-only">Open list</span>
                                    <span class="icon arrow-down" aria-hidden="true"></span>
                                </span>
                            </a>
                            <ul class="cat-sub-menu">
                                <li>
                                    <a href="<?= BASE_PATH ?> /ofiales.php">Oficiales de Seguridad</a>
                                    <a href="<?= BASE_PATH ?> /reportesOficiales.php">Reportes</a>
                                </li>
                            </ul>
                        </li>
                        <!-- Fin del modulo de Supervisión -->

                        <!-- Incio del modulo de Usuarios -->
                        <li>
                            <a class="show-cat-btn" href="##">
                                <span class="icon document" aria-hidden="true"></span>RRHH
                                <span class="category__btn transparent-btn" title="Open list">
                                    <span class="sr-only">Open list</span>
                                    <span class="icon arrow-down" aria-hidden="true"></span>
                                </span>
                            </a>
                            <ul class="cat-sub-menu">
                                <li>
                                    <a href="<?= BASE_PATH ?> /usuarios.php">Usuarios</a>
                                    <a href="<?= BASE_PATH ?> /empleadosHC.php">Colaboradores</a>
                                </li>
                            </ul>
                        </li>
                        <!-- Fin del modulo de Usuarios -->

                    </ul>
                    <!-- Fin del cuerpo del menú-->
                </div>
            </div>
            <!-- Inicio del Footer del menú -->
            <div class="sidebar-footer">
                <a href="perfil.php" class="sidebar-user">
                    <span class="sidebar-user-img">
                        <picture>
                            <source srcset="<?= IMG_EMPLEADO_PATH . '/' . $datos_usuario[0]['emp_foto']; ?>" type="image/webp">
                            <img src="<?= IMG_EMPLEADO_PATH . '/' . $datos_usuario[0]['emp_foto']; ?>" alt="User name">
                        </picture>
                    </span>
                    <div class="sidebar-user-info">
                        <span class="sidebar-user__title"> <?= $datos_usuario[0]['emp_nombre'] ?></span>
                        <span class="sidebar-user__subtitle"> <?= $datos_usuario[0]['dep_detalle'] ?></span>

                    </div>
                </a>
            </div>
            <!-- Fin del Footer del menú -->
        </aside>

        <div class="main-wrapper">
            <!-- ! Main nav -->
            <nav class="main-nav--bg">
                <div class="container main-nav d-flex justify-content-end">

                    <!-- Inicio menú superior -->
                    <div class="main-nav-end">

                        <!-- Botón para ver el menú -->
                        <button class="sidebar-toggle transparent-btn" title="Menu" type="button">
                            <span class="sr-only">Toggle menu</span>
                            <span class="icon menu-toggle--gray" aria-hidden="true"></span>
                        </button>
                        <!-- Botón para ver el menú -->

                        <!-- Inicio switch tema -->
                        <button class="theme-switcher gray-circle-btn" type="button" title="Switch theme">
                            <span class="sr-only">Switch theme</span>
                            <i class="sun-icon" data-feather="sun" aria-hidden="true"></i>
                            <i class="moon-icon" data-feather="moon" aria-hidden="true"></i>
                        </button>
                        <!-- Fin switch tema -->

                        <!-- Inicio panel de notificaciones -->
                        <div class="notification-wrapper">
                            <button class="gray-circle-btn dropdown-btn" title="To messages" type="button">
                                <span class="sr-only">To messages</span>
                                <span class="icon notification active" aria-hidden="true"></span>
                            </button>
                            <ul class="users-item-dropdown notification-dropdown dropdown">
                                <li>
                                    <a href="##">
                                        <div class="notification-dropdown-icon info">
                                            <i data-feather="check"></i>
                                        </div>
                                        <div class="notification-dropdown-text">
                                            <span class="notification-dropdown__title">System just updated</span>
                                            <span class="notification-dropdown__subtitle">The system has been successfully upgraded. Read more
                                                here.</span>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="##">
                                        <div class="notification-dropdown-icon danger">
                                            <i data-feather="info" aria-hidden="true"></i>
                                        </div>
                                        <div class="notification-dropdown-text">
                                            <span class="notification-dropdown__title">The cache is full!</span>
                                            <span class="notification-dropdown__subtitle">Unnecessary caches take up a lot of memory space and
                                                interfere ...</span>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="##">
                                        <div class="notification-dropdown-icon info">
                                            <i data-feather="check" aria-hidden="true"></i>
                                        </div>
                                        <div class="notification-dropdown-text">
                                            <span class="notification-dropdown__title">New Subscriber here!</span>
                                            <span class="notification-dropdown__subtitle">A new subscriber has subscribed.</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- Fin panel de notificaciones -->

                        <!-- Inicio perfil de usuario -->
                        <div class="nav-user-wrapper">
                            <button href="##" class="nav-user-btn dropdown-btn" title="My profile" type="button">
                                <span class="sr-only">My profile</span>
                                <span class="nav-user-img">
                                    <picture>
                                        <source srcset="<?= IMG_EMPLEADO_PATH . '/' . $datos_usuario[0]['emp_foto']; ?>" type="image/webp"><img src="<?= IMG_EMPLEADO_PATH . '/' . $datos_usuario[0]['emp_foto']; ?>" alt="User name">
                                    </picture>
                                </span>
                            </button>
                            <ul class="users-item-dropdown nav-user-dropdown dropdown">
                                <li><a href="##">
                                        <i data-feather="user" aria-hidden="true"></i>
                                        <span>Perfil</span>
                                    </a></li>
                                <li><a class="danger" href="<?= BASE_PATH ?> /logOut.php">
                                        <i data-feather="log-out" aria-hidden="true"></i>
                                        <span>Cerrar Sesión</span>
                                    </a></li>
                            </ul>
                        </div>
                        <!-- Fin perfil de usuario -->

                    </div>
                    <!-- Fin menú superior -->
                </div>
            </nav>
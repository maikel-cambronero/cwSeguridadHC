<?php

// Detecta en que host esta el proyeco
$esLocal = in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1']);

// Si esta en local usa la carpeta
$basePath = $esLocal ? '/cwSeguridadHC' : ''; // en producción esta en carpeta raíz

// Rutas base del proyecto
define('BASE_PATH', $basePath);

// Rustas para la carpeta de assets
define('CSS_PATH', BASE_PATH . '/assets/css');
define('FONTS_PATH', BASE_PATH . '/assets/fonts');
define('IMAGES_PATH', BASE_PATH . '/assets/images');
define('IMG_AVATAR_PATH', BASE_PATH . '/assets/images/avatar');
define('IMG_CATEGORIES_PATH', BASE_PATH . '/assets/images/categories');
define('IMG_EMPLEADO_PATH', BASE_PATH . '/assets/images/empleado');
define('IMG_SVG_PATH', BASE_PATH . '/assets/images/svg');
define('JS_PATH', BASE_PATH . '/assets/js');

// Rutas para la carpeta de configuración
define('CONFIG_PATH', BASE_PATH . '/config');

// Rutas para la carpeta de controladores
define('CONTROLLERS_PATH', BASE_PATH . '/controllers');

// Rutas para la carpeta de core
define('CORE_PATH', BASE_PATH . '/core');

// Rutas para la carpeta de model
define('MODEL_PATH', BASE_PATH . '/models');

// Rutas para la carpeta de plugins
define('PLUGINS_PATH', BASE_PATH . '/plugins');

// Rutas para la carpeta de view
define('VIEW_PATH', BASE_PATH . '/view');
define('VIEW_LAYOUT_PATH', BASE_PATH . '/view/layout');
<?php
/**
 * AiaAcademy - Bootstrap principal
 * ARCHIVO BASE compartido. Carga helpers, registra autoloader, registra modulos y despacha.
 */
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__, 2));

// Cargar configuracion global
$GLOBALS['config'] = require BASE_PATH . '/app/Config/config.php';

// Cargar helpers globales compartidos
foreach (glob(BASE_PATH . '/app/Helpers/*.php') as $helper) {
    require_once $helper;
}

// Cargar nucleo (View, Router)
require_once BASE_PATH . '/app/Core/View.php';
require_once BASE_PATH . '/app/Core/Router.php';

// Autoloader: mapea Aia\Core\* y Aia\Modules\<modulo>\* hacia sus archivos
spl_autoload_register(function ($class) {
    $prefix = 'Aia\\';
    if (strpos($class, $prefix) !== 0) {
        return;
    }
    $relative = substr($class, strlen($prefix));

    if (strpos($relative, 'Core\\') === 0) {
        $file = BASE_PATH . '/app/Core/' . str_replace('\\', '/', substr($relative, 5)) . '.php';
    } elseif (preg_match('#^Modules\\\\([^\\\\]+)\\\\(.+)$#', $relative, $m)) {
        $module = $m[1];
        $file = BASE_PATH . '/modules/' . $module . '/Controllers/' . $m[2] . '.php';
    } else {
        return;
    }

    if (file_exists($file)) {
        require_once $file;
    }
});

// Registrar modulos activos
$router = new Aia\Core\Router();
foreach ($GLOBALS['config']['modules']['active'] as $module) {
    $router->registerModule($module, BASE_PATH . '/modules/' . $module);
}

// Despachar la peticion
$router->dispatch($_SERVER['REQUEST_URI'] ?? '/');

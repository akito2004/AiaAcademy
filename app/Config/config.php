<?php
/**
 * AiaAcademy - Configuracion global compartida
 * ARCHIVO BASE: NO MODIFICAR sin aprobacion del equipo nucleo.
 * Cada desarrollador define sus valores especificos en config.local.php
 */

function loadEnvFile(): void
{
    $envFile = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . '.env';
    if (!is_file($envFile)) {
        return;
    }

    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return;
    }

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') {
            continue;
        }

        $parts = explode('=', $line, 2);
        if (count($parts) !== 2) {
            continue;
        }

        $key   = trim($parts[0]);
        $value = trim($parts[1]);
        $value = preg_replace('/^(["\'])(.*)\1$/s', '$2', $value) ?? $value;

        if ($key === '') {
            continue;
        }

        putenv($key . '=' . $value);
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

loadEnvFile();

$scheme = (php_sapi_name() === 'cli') ? 'http'
        : ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http');
$host   = $_SERVER['HTTP_HOST'] ?? 'localhost';

// --- Deteccion de la ruta de instalacion en la URL (base_path) ---
// 1) Metodo fiable: comparar la carpeta real del proyecto con DOCUMENT_ROOT.
//    Ej: DOCUMENT_ROOT=C:/xampp/htdocs, proyecto=C:/xampp/htdocs/AiaAcademy -> /AiaAcademy
$docRoot = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? ''), '/');
$appRoot = str_replace('\\', '/', dirname(__DIR__, 2));
$basePath = '';

if ($docRoot !== '') {
    if (strcasecmp($appRoot, $docRoot) === 0) {
        $basePath = ''; // proyecto en la raiz del servidor web
    } elseif (stripos($appRoot, $docRoot . '/') === 0) {
        $basePath = substr($appRoot, strlen($docRoot));
    }
}

// 2) Fallback: derivar de SCRIPT_NAME (entradas como public/index.php)
if ($basePath === '') {
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
    $dir    = rtrim(dirname($script), '/');
    if ($dir !== '' && $dir !== '/' && $dir !== '.') {
        $basePath = preg_replace('#/public$#', '', $dir);
    }
}

// 3) En Windows dirname() puede devolver rutas con "\" -> normalizar todo
$basePath = str_replace('\\', '/', $basePath ?? '');
$basePath = preg_replace('#/public$#', '', $basePath);
$basePath = trim($basePath ?? '', '/');
$basePath = ($basePath === '' || $basePath === '.') ? '' : '/' . $basePath;

return [
    'app' => [
        'name'           => 'AiaAcademy',
        'url'            => $scheme . '://' . $host,
        'base_url'       => $scheme . '://' . $host . $basePath,
        'base_path'      => $basePath,          // '/AiaAcademy' o ''
        'root_path'      => dirname(__DIR__, 2), // ruta real en disco
        'timezone'       => 'America/Lima',
        'debug'          => true,   // cambiar a false en produccion
        'default_module' => 'home',
    ],

    'database' => [
        'host'     => getenv('DB_HOST') ?: 'localhost',
        'dbname'   => getenv('DB_NAME') ?: 'aia_academy',
        'user'     => getenv('DB_USER') ?: 'root',
        'password' => getenv('DB_PASS') ?: '',
        'charset'  => 'utf8mb4',
    ],

    'modules' => [
        // Modulos activos. Cada modulo es independiente.
        'active' => ['home', 'nosotros', 'docentes', 'contactos', 'cursos', 'blog'],
    ],
];
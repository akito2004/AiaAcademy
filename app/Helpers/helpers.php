<?php
/**
 * helpers.php - Funciones globales compartidas por todos los modulos.
 * Cualquier funcion de uso comun debe agregarse aqui (no duplicarse en modulos).
 */

if (!function_exists('e')) {
    /** Escapa output para evitar XSS. */
    function e(?string $value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('config')) {
    /** Obtiene un valor de configuracion global. */
    function config(string $key, $default = null)
    {
        $value = $GLOBALS['config'];
        foreach (explode('.', $key) as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }
        return $value;
    }
}

if (!function_exists('redirect')) {
    /** Redirige a una URL interna del sitio. */
    function redirect(string $path): void
    {
        header('Location: ' . config('app.url') . $path);
        exit;
    }
}

if (!function_exists('asset')) {
    /** Genera URL de un asset publico. */
    function asset(string $path): string
    {
        return \Aia\Core\View::asset($path);
    }
}

if (!function_exists('site_url')) {
    /** Genera una URL interna de la aplicacion respetando el prefijo de instalacion.
     *  Ej: site_url('/contactos') -> /AiaAcademy/contactos */
    function site_url(string $path = ''): string
    {
        $base = $GLOBALS['config']['app']['base_path'] ?? '';
        if ($path === '' || $path === '/') {
            return $base === '' ? '/' : $base . '/';
        }
        return rtrim($base, '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('old')) {
    /** Recupera un valor previo de formulario (para validaciones). */
    function old(string $key, ?string $default = ''): string
    {
        return e($_SESSION['old'][$key] ?? $default);
    }
}

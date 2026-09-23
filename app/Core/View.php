<?php
namespace Aia\Core;

/**
 * View - Motor de vistas compartido.
 * Cada modulo define su propia vista, pero reutiliza los parciales globales
 * (header, footer, nav) ubicados en app/Core/partials o public/templates.
 */
class View
{
    public static function render(string $viewFile, array $data = []): void
    {
        extract($data, EXTR_SKIP);

        // Cabecera global compartida
        require BASE_PATH . '/app/Core/partials/header.php';

        // Contenido especifico del modulo
        require $viewFile;

        // Pie global compartido
        require BASE_PATH . '/app/Core/partials/footer.php';
    }

    public static function asset(string $path): string
    {
        $base = ($GLOBALS['config']['app']['base_url'] ?? '') ?: '';
        return rtrim($base, '/') . '/' . ltrim($path, '/');
    }
}

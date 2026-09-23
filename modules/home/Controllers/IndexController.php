<?php
namespace Aia\Modules\home;

use Aia\Core\View;

/**
 * IndexController - Controlador del modulo Home (pagina de inicio).
 * Plantilla de referencia para todos los demas modulos.
 */
class IndexController
{
    public function index(): void
    {
        $data = [
            'pageTitle' => 'Inicio | AiaAcademy',
            'metaDescription' => 'Academia de cursos online: aprende con expertos.',
            // Carga assets propios del modulo (rutas relativas a BASE_PATH)
            'moduleCss' => '/modules/home/Assets/css/home.css',
            'moduleJs'  => '/modules/home/Assets/js/home.js',
        ];
        View::render(BASE_PATH . '/modules/home/Views/index.php', $data);
    }
}

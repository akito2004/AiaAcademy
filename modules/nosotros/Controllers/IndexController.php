<?php
namespace Aia\Modules\nosotros;

use Aia\Core\View;

class IndexController
{
    public function index(): void
    {
        $data = [
            'pageTitle' => 'Nosotros | AiaAcademy',
            'moduleCss' => '/modules/nosotros/Assets/css/nosotros.css',
            'moduleJs'  => '/modules/nosotros/Assets/js/nosotros.js',
        ];
        View::render(BASE_PATH . '/modules/nosotros/Views/index.php', $data);
    }
}


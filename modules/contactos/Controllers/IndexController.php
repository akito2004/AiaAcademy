<?php
namespace Aia\Modules\contactos;

use Aia\Core\View;

class IndexController
{
    public function index(): void
    {
        $errors = [];
        $sent = false;
        $old = [];

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $old = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'email'  => trim($_POST['email'] ?? ''),
                'mensaje' => trim($_POST['mensaje'] ?? ''),
            ];

            if ($old['nombre'] === '') {
                $errors[] = 'El nombre es obligatorio.';
            }
            if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Ingresa un correo valido.';
            }
            if (mb_strlen($old['mensaje']) < 10) {
                $errors[] = 'El mensaje debe tener al menos 10 caracteres.';
            }

            if (empty($errors)) {
                $this->guardarMensaje($old);
                $sent = true;
            }
        }

        $data = [
            'pageTitle' => 'Contactos | AiaAcademy',
            'metaDescription' => 'Contacta con AiaAcademy: dudas, sugerencias o inscripciones.',
            'moduleCss' => '/modules/contactos/Assets/css/contactos.css',
            'moduleJs'  => '/modules/contactos/Assets/js/contactos.js',
            'errors'    => $errors,
            'sent'      => $sent,
            'old'       => $old,
        ];
        View::render(BASE_PATH . '/modules/contactos/Views/index.php', $data);
    }

    private function guardarMensaje(array $data): void
    {
        $dir = BASE_PATH . '/storage/uploads/contactos';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $linea = date('Y-m-d H:i:s') . " | " . $data['nombre'] . " | " . $data['email'] . " | " . str_replace(["\r", "\n"], ' ', $data['mensaje']) . PHP_EOL;
        file_put_contents($dir . '/mensajes.log', $linea, FILE_APPEND);
    }
}
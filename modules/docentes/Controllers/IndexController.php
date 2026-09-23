<?php
namespace Aia\Modules\docentes;
use Aia\Core\View;
require_once __DIR__ . '/../../../app/Config/conexion.php';

class IndexController
{
    public function index(): void
    {
        $db = conectarDB();
        
        // Traer todos los docentes desde la base
        $stmt = $db->query("SELECT * FROM docentes ORDER BY id");
        $docentes = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $data = [
            'pageTitle'  => 'Nuestros Docentes | AiaAcademy',
            'moduleCss'  => '/modules/docentes/Assets/css/docentes.css',
            'moduleJs'   => '/modules/docentes/Assets/js/docentes.js',
            'docentes'   => $docentes,
        ];
        
        // ✅ Ruta CORRECTA — apunta directo al archivo
        View::render(__DIR__ . '/../Views/docentes.php', $data);
    }

    public function verDetalle(int $docente_id): void
    {
        $db = conectarDB();
        
        // Datos del docente
        $stmt = $db->prepare("SELECT * FROM docentes WHERE id = ?");
        $stmt->execute([$docente_id]);
        $docente = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$docente) {
            http_response_code(404);
            die("Docente no encontrado");
        }

        // Cursos que imparte
        $stmtCursos = $db->prepare("SELECT * FROM course WHERE user_id = ? ORDER BY id DESC");
        $stmtCursos->execute([$docente_id]);
        $cursos = $stmtCursos->fetchAll(\PDO::FETCH_ASSOC);

        $data = [
            'pageTitle'  => $docente['nombre'] . ' ' . $docente['apellido'] . ' | AiaAcademy',
            'moduleCss'  => '/modules/docentes/Assets/css/docentes_detalle.css',
            'moduleJs'   => '/modules/docentes/Assets/js/docentes_detalle.js',
            'docente'    => $docente,
            'cursos'     => $cursos,
        ];
        
        // ✅ Igual aquí
        View::render(__DIR__ . '/../Views/docentes_detalle.php', $data);
    }
}
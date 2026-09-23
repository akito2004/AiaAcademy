<?php
namespace Aia\Modules\cursos;
use Aia\Core\View;
require_once __DIR__ . '/../../../app/Config/conexion.php';

class IndexController
{
    public function index(): void
    {
        $db = conectarDB();
        $stmt = $db->query("SELECT * FROM course ORDER BY id DESC");
        $cursos = $stmt->fetchAll();
        
        $data = [
            'cursos'              => $cursos,
            'cursos_destacados'   => $cursos,
            'cursos_disponibles'  => $cursos,
            'cursos_proximos'     => [],
            'pageTitle'           => 'Cursos | AiaAcademy'
        ];
        
        View::render(BASE_PATH . '/modules/cursos/Views/index.php', $data);
    }

    public function ver($id = null): void
    {
        if (!$id) {
            $base = $GLOBALS['config']['app']['base_path'] ?? '';
            header('Location: ' . rtrim($base, '/') . '/cursos');
            exit;
        }
        
        $db = conectarDB();
        
        $stmt = $db->prepare("
            SELECT c.*, 
                   d.nombre AS docente_nombre, 
                   d.apellido AS docente_apellido, 
                   d.especialidad AS docente_especialidad,
                   d.imagen_url AS docente_foto,
                   d.email AS docente_email,
                   d.telefono AS docente_telefono,
                   d.estado AS docente_estado,
                   d.fecha_creacion AS docente_fecha_registro
            FROM course c
            LEFT JOIN docentes d ON CAST(c.user_id AS UNSIGNED) = d.id
            WHERE c.id = ?
        ");
        $stmt->execute([$id]);
        $curso = $stmt->fetch();
        
        if (!$curso) {
            http_response_code(404);
            echo "Curso no encontrado";
            exit;
        }
        
        $stmt = $db->prepare("SELECT * FROM section WHERE course_id = ? ORDER BY `order` ASC");
        $stmt->execute([$id]);
        $secciones = $stmt->fetchAll();
        
        foreach ($secciones as &$seccion) {
            $stmt = $db->prepare("SELECT * FROM lesson WHERE section_id = ? ORDER BY `order` ASC");
            $stmt->execute([$seccion['id']]);
            $seccion['lecciones'] = $stmt->fetchAll();
        }
        
        $data = [
            'curso'      => $curso,
            'secciones'  => $secciones,
            'pageTitle'  => $curso['title'] ?? 'Curso'
        ];
        
        View::render(BASE_PATH . '/modules/cursos/Views/ver.php', $data);
    }

    public function avance($id = null): void
    {
        session_start();
        $user_id = $_SESSION['user_id'] ?? 1;

        // Detección del ID del curso
        if (!$id) {
            $id = $_GET['id'] ?? $_GET['curso_id'] ?? null;
        }
        if (!$id) {
            $base = $GLOBALS['config']['app']['base_path'] ?? '';
            header('Location: ' . rtrim($base, '/') . '/cursos');
            exit;
        }

        $db = conectarDB();
        
        // Datos del curso y docente
        $stmt = $db->prepare("
            SELECT c.*, 
                   d.nombre AS docente_nombre, 
                   d.apellido AS docente_apellido, 
                   d.especialidad AS docente_especialidad,
                   d.imagen_url AS docente_foto
            FROM course c
            LEFT JOIN docentes d ON CAST(c.user_id AS UNSIGNED) = d.id
            WHERE c.id = ?
        ");
        $stmt->execute([$id]);
        $curso = $stmt->fetch();
        
        if (!$curso) {
            http_response_code(404);
            echo "Curso no encontrado";
            exit;
        }
        
        // Secciones y lecciones
        $stmt = $db->prepare("SELECT * FROM section WHERE course_id = ? ORDER BY `order` ASC");
        $stmt->execute([$id]);
        $secciones = $stmt->fetchAll();
        
        $lecciones = [];
        foreach ($secciones as &$seccion) {
            $stmt = $db->prepare("SELECT * FROM lesson WHERE section_id = ? ORDER BY `order` ASC");
            $stmt->execute([$seccion['id']]);
            $seccion['lecciones'] = $stmt->fetchAll();
            $lecciones = array_merge($lecciones, $seccion['lecciones']);
        }
        
        // === CÁLCULO DE PROGRESO REAL ===
        $total_lecciones = count($lecciones);
        $total_elementos = $total_lecciones * 2; // video + cuestionario

        // Videos vistos
        $stmt_videos = $db->prepare("
            SELECT COUNT(*) AS cantidad
            FROM progreso_leccion
            WHERE user_id = ? AND course_id = ? AND video_visto = 1
        ");
        $stmt_videos->execute([$user_id, $id]);
        $videos_vistos = $stmt_videos->fetch()['cantidad'] ?? 0;

        // Cuestionarios aprobados
        $stmt_quices = $db->prepare("
            SELECT COUNT(*) AS cantidad
            FROM progreso_leccion
            WHERE user_id = ? AND course_id = ? AND cuestionario_aprobado = 1
        ");
        $stmt_quices->execute([$user_id, $id]);
        $quices_aprobados = $stmt_quices->fetch()['cantidad'] ?? 0;

        $elementos_completados = $videos_vistos + $quices_aprobados;
        $porcentaje_avance = $total_elementos > 0 ? ($elementos_completados / $total_elementos) * 100 : 0;
        $completo = ($porcentaje_avance >= 100);
        
        // Lección activa
        $leccionActiva = null;
        $idLeccionActiva = $_GET['leccion'] ?? null;
        if ($idLeccionActiva) {
            foreach ($lecciones as $lec) {
                if ($lec['id'] == $idLeccionActiva) {
                    $leccionActiva = $lec;
                    break;
                }
            }
        }
        
        $data = [
            'curso'             => $curso,
            'secciones'         => $secciones,
            'lecciones'         => $lecciones,
            'leccionActiva'     => $leccionActiva,
            'porcentaje_avance'  => $porcentaje_avance,
            'completo'          => $completo,
            'pageTitle'         => 'Avance: ' . ($curso['title'] ?? 'Curso') . ' | AiaAcademy'
        ];
        
        View::render(BASE_PATH . '/modules/cursos/Views/avance.php', $data);
    }

    // ✅ Marcar video como visto
    public function marcarVideoVisto(): void
    {
        session_start();
        $db = conectarDB();
        $user_id = $_SESSION['user_id'] ?? 1;
        
        $leccion_id = $_POST['leccion_id'] ?? 0;
        $course_id = $_POST['curso_id'] ?? 0;
        
        if (!$leccion_id || !$course_id) {
            echo json_encode(['ok' => false, 'mensaje' => 'Faltan datos']);
            return;
        }
        
        $stmt = $db->prepare("
            INSERT INTO progreso_leccion (user_id, course_id, leccion_id, video_visto, fecha_actualizacion)
            VALUES (?, ?, ?, 1, UNIX_TIMESTAMP())
            ON DUPLICATE KEY UPDATE 
                video_visto = 1,
                fecha_actualizacion = UNIX_TIMESTAMP()
        ");
        $stmt->execute([$user_id, $course_id, $leccion_id]);
        
        echo json_encode(['ok' => true]);
    }

    // ✅ Marcar cuestionario como aprobado
    public function marcarCuestionarioAprobado(): void
    {
        session_start();
        $db = conectarDB();
        $user_id = $_SESSION['user_id'] ?? 1;
        
        $leccion_id = $_POST['leccion_id'] ?? 0;
        $course_id = $_POST['curso_id'] ?? 0;
        
        if (!$leccion_id || !$course_id) {
            echo json_encode(['ok' => false, 'mensaje' => 'Faltan datos']);
            return;
        }
        
        $stmt = $db->prepare("
            INSERT INTO progreso_leccion (user_id, course_id, leccion_id, cuestionario_aprobado, fecha_actualizacion)
            VALUES (?, ?, ?, 1, UNIX_TIMESTAMP())
            ON DUPLICATE KEY UPDATE 
                cuestionario_aprobado = 1,
                fecha_actualizacion = UNIX_TIMESTAMP()
        ");
        $stmt->execute([$user_id, $course_id, $leccion_id]);
        
        echo json_encode(['ok' => true]);
    }

    // ✅ Página de Exámenes / Preguntas
    public function preguntas(): void
    {
        $db = conectarDB();
        $curso_id = $_GET['curso_id'] ?? 0;
        
        $stmt = $db->prepare("SELECT * FROM course WHERE id = ?");
        $stmt->execute([$curso_id]);
        $curso = $stmt->fetch();
        
        if (!$curso) {
            http_response_code(404);
            echo "Curso no encontrado";
            return;
        }
        
        $stmt = $db->prepare("
            SELECT l.*, qz.title AS titulo_examen, qz.description AS desc_examen,
                   COUNT(q.id) AS total_preguntas
            FROM lesson l
            LEFT JOIN quiz qz ON l.id = qz.lesson_id
            LEFT JOIN question q ON qz.id = q.quiz_id
            WHERE l.course_id = ?
            GROUP BY l.id
            ORDER BY l.id
        ");
        $stmt->execute([$curso_id]);
        $lecciones = $stmt->fetchAll();
        
        $data = [
            'curso'       => $curso,
            'lecciones'   => $lecciones,
            'pageTitle'   => 'Exámenes: ' . ($curso['title'] ?? 'Curso') . ' | AiaAcademy'
        ];
        
        View::render(BASE_PATH . '/modules/cursos/Views/preguntas.php', $data);
    }

    // ✅ Página de Certificado
    public function certificado($id = null): void
    {
        session_start();
        $db = conectarDB();
        $user_id = $_SESSION['user_id'] ?? 1;
        
        if (!$id) {
            $base = $GLOBALS['config']['app']['base_path'] ?? '';
            header('Location: ' . rtrim($base, '/') . '/cursos');
            exit;
        }
        
        // Datos del curso
        $stmt = $db->prepare("SELECT * FROM course WHERE id = ?");
        $stmt->execute([$id]);
        $curso = $stmt->fetch();
        
        if (!$curso) {
            http_response_code(404);
            echo "Curso no encontrado";
            return;
        }
        
        // Total de lecciones
        $stmt_total = $db->prepare("SELECT COUNT(*) AS total FROM lesson WHERE course_id = ?");
        $stmt_total->execute([$id]);
        $total_lecciones = $stmt_total->fetch()['total'] ?? 0;
        
        // Lecciones completadas (video + cuestionario)
        $stmt_completadas = $db->prepare("
            SELECT COUNT(*) AS cantidad
            FROM progreso_leccion
            WHERE user_id = ? AND course_id = ? 
              AND video_visto = 1 AND cuestionario_aprobado = 1
        ");
        $stmt_completadas->execute([$user_id, $id]);
        $completadas = $stmt_completadas->fetch()['cantidad'] ?? 0;
        
        $completo = ($total_lecciones > 0 && $completadas >= $total_lecciones);
        
        $data = [
            'curso'         => $curso,
            'completo'      => $completo,
            'total'         => $total_lecciones,
            'completadas'   => $completadas,
            'pageTitle'     => 'Certificado: ' . ($curso['title'] ?? 'Curso') . ' | AiaAcademy'
        ];
        
        View::render(BASE_PATH . '/modules/cursos/Views/certificado.php', $data);
    }
}
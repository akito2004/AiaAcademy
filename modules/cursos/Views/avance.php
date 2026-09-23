<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();
$base = $GLOBALS['config']['app']['base_path'] ?? '';
echo '<link rel="stylesheet" href="' . $base . '/assets/css/general.css">';
echo '<script src="' . $base . '/assets/js/general.js" defer></script>';
echo '<link rel="stylesheet" href="' . $base . '/modules/cursos/Assets/css/avance.css">';
echo '<script src="' . $base . '/modules/cursos/Assets/js/avance.js" defer></script>';
extract($data);

// ✅ CONEXIÓN Y USUARIO
$db = conectarDB();
$user_id = $_SESSION['user_id'] ?? 1;

// ✅ CALCULAR PROGRESO REAL
$porcentaje_avance = 0;
$completado = 0;
if (!empty($curso['id'])) {
    // Contar lecciones
    $stmt_total = $db->prepare("SELECT COUNT(*) AS total FROM lesson WHERE course_id = ?");
    $stmt_total->execute([$curso['id']]);
    $total_lecciones = $stmt_total->fetch()['total'] ?? 0;
    $total_elementos = $total_lecciones * 2;

    // Videos vistos
    $stmt_videos = $db->prepare("
        SELECT COUNT(*) AS cantidad
        FROM progreso_leccion
        WHERE user_id = ? AND course_id = ? AND video_visto = 1
    ");
    $stmt_videos->execute([$user_id, $curso['id']]);
    $videos_vistos = $stmt_videos->fetch()['cantidad'] ?? 0;

    // Cuestionarios aprobados
    $stmt_quices = $db->prepare("
        SELECT COUNT(*) AS cantidad
        FROM progreso_leccion
        WHERE user_id = ? AND course_id = ? AND cuestionario_aprobado = 1
    ");
    $stmt_quices->execute([$user_id, $curso['id']]);
    $quices_aprobados = $stmt_quices->fetch()['cantidad'] ?? 0;

    $elementos_completados = $videos_vistos + $quices_aprobados;
    if ($total_elementos > 0) {
        $porcentaje_avance = ($elementos_completados / $total_elementos) * 100;
    }
    $completado = ($porcentaje_avance >= 100) ? 1 : 0;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($curso['title'] ?? 'Curso', ENT_QUOTES, 'UTF-8') ?> — Avance</title>
</head>
<body>
<div class="pagina-avance">
    <!-- ENCABEZADO -->
    <header class="avance-encabezado">
        <div class="encabezado-izquierda">
            <a href="<?= $base ?>/cursos" class="enlace-volver">← Volver a Cursos</a>
            <h1 class="titulo-curso"><?= htmlspecialchars($curso['title'] ?? 'Curso', ENT_QUOTES, 'UTF-8') ?></h1>
        </div>
        <div class="bloque-progreso-general">
            <div class="barra-fondo">
                <div class="barra-llenada" style="width: <?= min(100, $porcentaje_avance) ?>%"></div>
            </div>
            <span class="texto-progreso">
                <?= number_format($porcentaje_avance, 0) ?>% completado
            </span>
            <?php if ($completado): ?>
            <a href="<?= $base ?>/cursos/certificado/<?= $curso['id'] ?>" class="btn-certificado">
                🎓 Obtener Certificado
            </a>
            <?php endif; ?>
        </div>
    </header>

    <!-- BOTONES DE NAVEGACIÓN -->
    <div style="margin-bottom: 24px; display: flex; gap: 12px; flex-wrap: wrap;">
        <a href="<?= $base ?>/cursos/avance/<?= $curso['id'] ?>" 
           class="btn-navegacion btn-contenido">
            📚 Contenido del Curso
        </a>
        <a href="<?= $base ?>/cursos/preguntas.php?curso_id=<?= $curso['id'] ?>" 
           class="btn-navegacion btn-examenes">
            📝 Exámenes
        </a>
    </div>

    <!-- CLASE EN VIVO -->
    <?php if (!empty($curso['meet_link'])): ?>
    <section class="seccion-clase-vivo">
        <div class="icono-clase">📅</div>
        <div class="contenido-clase">
            <h3>Clase en Vivo</h3>
            <?php if (!empty($curso['meet_datetime'])): ?>
            <p class="fecha-clase">
                Próxima clase: <strong><?= date('d/m/Y — H:i', strtotime($curso['meet_datetime'])) ?></strong>
            </p>
            <?php endif; ?>
            <div class="enlaces-clase">
                <a href="<?= htmlspecialchars($curso['meet_link'], ENT_QUOTES, 'UTF-8') ?>" 
                   target="_blank" class="boton-ingresar-meet">
                    Ingresar a la reunión
                </a>
                <?php if (!empty($curso['meet_recording'])): ?>
                <a href="<?= htmlspecialchars($curso['meet_recording'], ENT_QUOTES, 'UTF-8') ?>" 
                   target="_blank" class="enlace-grabacion">
                    📹 Ver grabación
                </a>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- CONTENEDOR DOBLE -->
    <div class="contenedor-doble">
        <!-- LADO IZQUIERDO: LECCIÓN ACTIVA -->
        <main class="zona-contenido-activo">
            <?php if (!empty($leccionActiva)): ?>
                <!-- VIDEO -->
                <div class="caja-reproductor">
                    <h2 class="titulo-bloque">🎬 Video Introductorio</h2>
                    <?php if (!empty($leccionActiva['video_url'])): ?>
                    <video id="video-principal" class="reproductor-estilo" controls
                           data-leccion-id="<?= (int)$leccionActiva['id'] ?>">
                        <source src="<?= htmlspecialchars($leccionActiva['video_url'], ENT_QUOTES, 'UTF-8') ?>" type="video/mp4">
                        Tu navegador no soporta video.
                    </video>
                    <?php else: ?>
                    <div class="reproductor-estilo sin-video">
                        <p>Sin video disponible</p>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- INFO LECCIÓN -->
                <div class="caja-info-leccion">
                    <h2 class="nombre-leccion">
                        <?= htmlspecialchars($leccionActiva['title'] ?? 'Lección', ENT_QUOTES, 'UTF-8') ?>
                    </h2>
                    <span class="etiqueta-duracion">
                        ⏱️ <?= htmlspecialchars($leccionActiva['duration'] ?? '00:00', ENT_QUOTES, 'UTF-8') ?>
                    </span>

                    <?php if (!empty($leccionActiva['summary'])): ?>
                    <div class="bloque-contenido">
                        <h3>📖 Introducción</h3>
                        <p class="texto-introduccion">
                            <?= nl2br(htmlspecialchars($leccionActiva['summary'], ENT_QUOTES, 'UTF-8')) ?>
                        </p>
                    </div>
                    <?php endif; ?>

                    <!-- ARCHIVOS -->
                    <?php if (!empty($leccionActiva['attachment'])): ?>
                    <div class="bloque-contenido">
                        <h3>📁 Archivos</h3>
                        <ul class="lista-archivos">
                            <li>
                                <a href="<?= htmlspecialchars($leccionActiva['attachment'], ENT_QUOTES, 'UTF-8') ?>" 
                                   target="_blank" class="enlace-archivo">
                                    📎 Ver material adjunto
                                </a>
                            </li>
                        </ul>
                    </div>
                    <?php endif; ?>

                    <!-- NOTAS -->
                    <div class="bloque-notas-personales">
                        <h3>📝 Mis Notas</h3>
                        <textarea id="cuadro-notas" placeholder="Escribe aquí..."></textarea>
                        <button id="boton-guardar-notas" class="boton-guardar-notas">
                            Guardar Notas
                        </button>
                    </div>

                    <!-- CUESTIONARIO -->
                    <?php
                    $preguntas = [];
                    if (!empty($leccionActiva['id'])) {
                        $stmt_qz = $db->prepare("SELECT * FROM quiz WHERE lesson_id = ? LIMIT 1");
                        $stmt_qz->execute([(int)$leccionActiva['id']]);
                        $quiz = $stmt_qz->fetch();
                        if ($quiz) {
                            $stmt_p = $db->prepare("SELECT * FROM question WHERE quiz_id = ? ORDER BY sort_order");
                            $stmt_p->execute([(int)$quiz['id']]);
                            $preguntas = $stmt_p->fetchAll();
                        }
                    }
                    ?>
                    <?php if (!empty($preguntas)): ?>
                    <div class="seccion-examen">
                        <h3>📋 <?= htmlspecialchars($quiz['title'] ?? 'Evaluación', ENT_QUOTES, 'UTF-8') ?></h3>
                        <?php if (!empty($quiz['description'])): ?>
                        <p style="color:#94a3b8;margin-bottom:20px;">
                            <?= htmlspecialchars($quiz['description'], ENT_QUOTES, 'UTF-8') ?>
                        </p>
                        <?php endif; ?>

                        <form id="form-examen" data-leccion-id="<?= (int)$leccionActiva['id'] ?>">
                            <?php foreach ($preguntas as $idx => $p):
                                $opciones = json_decode($p['options'], true) ?: [];
                                $respuesta_correcta = json_decode($p['correct_answers'], true);
                                $correcta = is_array($respuesta_correcta) ? ($respuesta_correcta[0] ?? -1) : $respuesta_correcta;
                            ?>
                            <div class="pregunta">
                                <p class="texto-pregunta">
                                    <?= ($idx + 1) ?>. <?= htmlspecialchars($p['title'], ENT_QUOTES, 'UTF-8') ?>
                                </p>
                                <?php foreach ($opciones as $i => $op): ?>
                                <label class="opcion">
                                    <input type="radio" name="preg_<?= (int)$p['id'] ?>" value="<?= $i ?>" 
                                           data-correcta="<?= (int)$correcta ?>" required>
                                    <?= htmlspecialchars($op, ENT_QUOTES, 'UTF-8') ?>
                                </label>
                                <?php endforeach; ?>
                            </div>
                            <?php endforeach; ?>
                            <button type="submit" class="boton-enviar-examen">
                                ✅ Enviar Respuestas
                            </button>
                        </form>
                        <div id="resultado-examen" class="resultado-examen"></div>
                    </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="sin-contenido">
                    <p>Selecciona una lección del temario</p>
                </div>
            <?php endif; ?>
        </main>

        <!-- LADO DERECHO: TEMARIO -->
        <aside class="panel-lista-lecciones">
            <h3 class="titulo-temario">Temario</h3>
            <ul class="lista-temas">
                <?php if (!empty($secciones)): ?>
                    <?php foreach ($secciones as $seccion): ?>
                    <li class="item-seccion">
                        <strong><?= htmlspecialchars($seccion['title'] ?? 'Sección', ENT_QUOTES, 'UTF-8') ?></strong>
                        <?php if (!empty($seccion['lecciones'])): ?>
                        <ul class="lista-lecciones">
                            <?php foreach ($seccion['lecciones'] as $lec): ?>
                            <li class="item-tema">
                                <a href="<?= $base ?>/cursos/avance/<?= $curso['id'] ?>?leccion=<?= (int)$lec['id'] ?>"
                                   class="enlace-tema <?= (isset($leccionActiva['id']) && $lec['id'] == $leccionActiva['id']) ? 'activa' : '' ?>">
                                    <span class="indicador-estado">
                                        <?= (isset($leccionActiva['id']) && $lec['id'] == $leccionActiva['id']) ? '🔵' : '⚪' ?>
                                    </span>
                                    <span class="texto-tema">
                                        <?= htmlspecialchars($lec['title'] ?? 'Lección', ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                    <span class="tiempo-tema">
                                        <?= htmlspecialchars($lec['duration'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php else: ?>
                        <p class="sin-lecciones">Lecciones por agregar</p>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="sin-lecciones">Aún no hay módulos</li>
                <?php endif; ?>
            </ul>
        </aside>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const base = '<?= $base ?>';
    const cursoId = <?= (int)($curso['id'] ?? 0) ?>;

    // Marcar video como visto al terminar
    const video = document.getElementById('video-principal');
    let yaMarcado = false;
    if (video) {
        video.addEventListener('ended', function () {
            if (yaMarcado) return;
            yaMarcado = true;
            const leccionId = video.dataset.leccionId;
            fetch(base + '/cursos/marcar-video-visto', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'leccion_id=' + encodeURIComponent(leccionId) + '&curso_id=' + cursoId
            }).then(() => location.reload());
        });
    }

    // Guardar notas
    const btnNotas = document.getElementById('boton-guardar-notas');
    if (btnNotas) {
        btnNotas.addEventListener('click', function () {
            const notas = document.getElementById('cuadro-notas').value;
            localStorage.setItem('notas_leccion_' + (video?.dataset.leccionId || 0), notas);
            alert('✅ Notas guardadas');
        });
    }

    // Cargar notas guardadas
    const cajaNotas = document.getElementById('cuadro-notas');
    if (cajaNotas) {
        const leccionId = video?.dataset.leccionId || 0;
        cajaNotas.value = localStorage.getItem('notas_leccion_' + leccionId) || '';
    }

    // Procesar cuestionario
    const form = document.getElementById('form-examen');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const nombres = new Set(Array.from(form.querySelectorAll('input[type="radio"]')).map(r => r.name));
            const totalPreguntas = nombres.size;
            let correctas = 0;
            form.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
                if (parseInt(radio.value) === parseInt(radio.dataset.correcta)) correctas++;
            });
            const aprobado = (correctas / totalPreguntas >= 0.6);
            const res = document.getElementById('resultado-examen');
            res.style.display = 'block';
            res.className = 'resultado-examen ' + (aprobado ? 'resultado-aprobado' : 'resultado-reprobado');
            res.textContent = aprobado
                ? `✅ Aprobado — ${correctas} de ${totalPreguntas} — ¡Bien hecho!`
                : `❌ No aprobado — ${correctas} de ${totalPreguntas} — Vuelve a intentar`;
            
            if (aprobado) {
                const leccionId = form.dataset.leccionId;
                fetch(base + '/cursos/marcar-cuestionario-aprobado', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'leccion_id=' + encodeURIComponent(leccionId) + '&curso_id=' + cursoId
                }).then(() => setTimeout(() => location.reload(), 1500));
            }
        });
    }
});
</script>
</body>
</html>
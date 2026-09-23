<?php extract($data);
$base = $GLOBALS['config']['app']['base_path'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Exámenes | AiaAcademy') ?></title>
    <link rel="stylesheet" href="<?= $base ?>/assets/css/general.css">
    <link rel="stylesheet" href="<?= $base ?>/modules/cursos/Assets/css/preguntas.css">
</head>
<body>
<div class="pagina-preguntas">
    <div class="contenedor">
        <!-- Botón Volver -->
        <a href="<?= $base ?>/cursos/avance/<?= $curso['id'] ?>" class="btn-volver">
            ← Volver al Avance del Curso
        </a>

        <h1 class="titulo-pagina">
            📝 Exámenes: <?= htmlspecialchars($curso['title'] ?? 'Curso') ?>
        </h1>

        <?php if (empty($lecciones)): ?>
            <div class="aviso-vacio">
                No hay lecciones con evaluaciones disponibles para este curso.
            </div>
        <?php else: ?>
            <div class="lista-examenes">
                <?php foreach ($lecciones as $i => $leccion): ?>
                    <div class="tarjeta-examen">
                        <h3 class="nombre-leccion">
                            Lección <?= $i + 1 ?>: <?= htmlspecialchars($leccion['title'] ?? 'Sin nombre') ?>
                        </h3>

                        <?php if (!empty($leccion['titulo_examen'])): ?>
                            <p class="subtitulo-examen">
                                📋 <?= htmlspecialchars($leccion['titulo_examen']) ?>
                            </p>
                            <?php if (!empty($leccion['desc_examen'])): ?>
                                <p class="descripcion-examen">
                                    <?= htmlspecialchars($leccion['desc_examen']) ?>
                                </p>
                            <?php endif; ?>
                            <p class="cantidad-preguntas">
                                Preguntas: <strong><?= $leccion['total_preguntas'] ?? 0 ?></strong>
                            </p>
                            <a href="<?= $base ?>/cursos/avance/<?= $curso['id'] ?>?leccion=<?= $leccion['id'] ?>" 
                               class="btn-primario">
                                ▶️ Realizar Evaluación
                            </a>
                        <?php else: ?>
                            <p class="sin-examen">
                                ⏳ Esta lección no tiene evaluación configurada aún.
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
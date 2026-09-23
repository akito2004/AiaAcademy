<?php
extract($data);
$base = $GLOBALS['config']['app']['base_path'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="<?= $base ?>/assets/css/general.css">
    <link rel="stylesheet" href="<?= $base ?><?= $moduleCss ?>">
</head>
<body>

<!-- === NAVEGACIÓN IGUAL QUE EN CURSOS === -->


<!-- === CONTENIDO === -->
<div class="pagina-docentes">
    <div class="contenedor-principal">
        <header class="seccion-encabezado">
            <h1 class="titulo-principal">Nuestros Docentes</h1>
            <p class="subtitulo">Expertos que te guiarán paso a paso en tu aprendizaje</p>
        </header>

        <div class="grid-docentes">
            <?php if (!empty($docentes)): ?>
                <?php foreach ($docentes as $d): ?>
                <a href="<?= $base ?>/docentes/ver/<?= $d['id'] ?>" class="tarjeta-docente-enlace">
                    <div class="tarjeta-docente">
                        <div class="foto-contenedor">
                            <?php if (!empty($d['imagen_url'])): ?>
                            <img src="<?= htmlspecialchars($d['imagen_url']) ?>" 
                                 alt="Foto de <?= htmlspecialchars($d['nombre'] . ' ' . $d['apellido']) ?>"
                                 class="foto-docente">
                            <?php else: ?>
                            <div class="foto-placeholder">
                                <?= strtoupper(substr($d['nombre'], 0, 1)) . strtoupper(substr($d['apellido'], 0, 1)) ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="info-tarjeta">
                            <h3 class="nombre-docente">
                                <?= htmlspecialchars($d['nombre'] . ' ' . $d['apellido']) ?>
                            </h3>
                            <p class="especialidad">
                                <?= htmlspecialchars($d['especialidad'] ?? 'Docente') ?>
                            </p>
                            <span class="boton-ver-perfil">Ver perfil →</span>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="sin-resultados">
                    <p>Aún no se han agregado docentes.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- === PIE DE PÁGINA IGUAL QUE EN CURSOS === -->
<footer class="pie-pagina">
    <div class="contenedor-pie">
        <p>&copy; <?= date('Y') ?> AiaAcademy. Todos los derechos reservados.</p>
    </div>
</footer>

<script src="<?= $base ?><?= $moduleJs ?>" defer></script>
</body>
</html>
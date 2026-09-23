<?php
extract($data);
$base = $GLOBALS['config']['app']['base_path'] ?? '';
$d = $docente;
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



<div class="pagina-detalle-docente">
    <div class="contenedor-principal">
        
        <!-- Botón Volver -->
        <a href="<?= $base ?>/docentes" class="boton-volver">← Volver a Docentes</a>

        <!-- PERFIL PRINCIPAL -->
        <div class="seccion-perfil">
            <div class="tarjeta-perfil">
                <div class="bloque-foto">
                    <?php if (!empty($d['imagen_url'])): ?>
                    <img src="<?= htmlspecialchars($d['imagen_url']) ?>" 
                         alt="Foto de <?= htmlspecialchars($d['nombre'] . ' ' . $d['apellido']) ?>"
                         class="foto-perfil">
                    <?php else: ?>
                    <div class="foto-placeholder-grande">
                        <?= strtoupper(substr($d['nombre'], 0, 1)) . strtoupper(substr($d['apellido'], 0, 1)) ?>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="bloque-info">
                    <h1 class="nombre-completo">
                        <?= htmlspecialchars($d['nombre'] . ' ' . $d['apellido']) ?>
                    </h1>
                    
                    <p class="especialidad-principal">
                        <?= htmlspecialchars($d['especialidad'] ?? 'Docente') ?>
                    </p>

                    <div class="linea-divisoria"></div>

                    <div class="datos-contacto">
                        <?php if (!empty($d['email'])): ?>
                        <div class="dato-fila">
                            <span class="icono">✉️</span>
                            <a href="mailto:<?= htmlspecialchars($d['email']) ?>" class="enlace-dato">
                                <?= htmlspecialchars($d['email']) ?>
                            </a>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($d['telefono'])): ?>
                        <div class="dato-fila">
                            <span class="icono">📞</span>
                            <a href="tel:<?= htmlspecialchars($d['telefono']) ?>" class="enlace-dato">
                                <?= htmlspecialchars($d['telefono']) ?>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="estadisticas-docente">
                        <div class="dato-estadistica">
                            <span class="numero"><?= count($cursos) ?></span>
                            <span class="etiqueta">Cursos</span>
                        </div>
                        <div class="dato-estadistica">
                            <span class="numero">Activo</span>
                            <span class="etiqueta">Estado</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CURSOS QUE IMPARTE -->
        <div class="seccion-cursos">
            <h2 class="titulo-seccion">Cursos dictados</h2>
            
            <?php if (!empty($cursos)): ?>
            <div class="grid-cursos-docente">
                <?php foreach ($cursos as $curso): ?>
                <a href="<?= $base ?>/cursos/ver/<?= $curso['id'] ?>" class="tarjeta-curso-mini">
                    <div class="miniatura-curso">
                        <?php if (!empty($curso['thumbnail'])): ?>
                        <img src="<?= htmlspecialchars($curso['thumbnail']) ?>" 
                             alt="<?= htmlspecialchars($curso['title']) ?>"
                             class="img-miniatura">
                        <?php else: ?>
                        <div class="sin-imagen">📚</div>
                        <?php endif; ?>
                    </div>
                    <div class="info-curso-mini">
                        <h3 class="titulo-curso-mini">
                            <?= htmlspecialchars($curso['title']) ?>
                        </h3>
                        <?php if (!empty($curso['short_description'])): ?>
                        <p class="desc-curso-mini">
                            <?= htmlspecialchars(mb_substr($curso['short_description'], 0, 90)) ?>...
                        </p>
                        <?php endif; ?>
                        <span class="ver-curso-enlace">Ver curso →</span>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="sin-cursos">
                <p>Este docente aún no tiene cursos asignados.</p>
            </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<footer class="pie-pagina">
    <div class="contenedor-pie">
        <p>&copy; <?= date('Y') ?> AiaAcademy. Todos los derechos reservados.</p>
    </div>
</footer>

<script src="<?= $base ?><?= $moduleJs ?>" defer></script>
</body>
</html>
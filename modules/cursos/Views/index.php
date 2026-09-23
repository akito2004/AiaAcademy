<?php
$base = $GLOBALS['config']['app']['base_path'] ?? '';
echo '<link rel="stylesheet" href="'.$base.'/Assets/css/general.css">';
echo '<script src="'.$base.'/Assets/js/general.js" defer></script>';
echo '<link rel="stylesheet" href="'.$base.'/modules/cursos/Assets/css/catalogo.css">';
echo '<script src="'.$base.'/modules/cursos/Assets/js/catalogo.js" defer></script>';

extract($data);

$cursos_gratuitos = array_filter($cursos, function($c) {
    return !empty($c['is_free_course']);
});
$cursos_disponibles = $cursos;
$cursos_proximos = [];
$cursos_destacados = $cursos;
?>

<div class="pagina-catalogo">
    <section class="seccion-encabezado">
        <div class="contenido-encabezado">
            <span class="etiqueta-evento">¡APRENDE A TU RITMO!</span>
            <h1>Formación continua AiaAcademy</h1>
            <p class="subtitulo-encabezado">Cursos, contenidos y recursos para avanzar en tu carrera profesional</p>
            
            <div class="contador-superior">
                <div class="bloque-dato">
                    <span class="numero-dato"><?= count($cursos) ?></span>
                    <span class="etiqueta-dato">Cursos</span>
                </div>
                <div class="bloque-dato">
                    <span class="numero-dato"><?= count($cursos_disponibles) ?></span>
                    <span class="etiqueta-dato">Disponibles</span>
                </div>
                <div class="bloque-dato">
                    <span class="numero-dato"><?= count($cursos_gratuitos) ?></span>
                    <span class="etiqueta-dato">Gratis</span>
                </div>
                <div class="bloque-dato">
                    <span class="numero-dato">15+</span>
                    <span class="etiqueta-dato">Docentes</span>
                </div>
            </div>
            
            <a href="<?=$base?>/cursos#todos" class="boton-accion-principal" style="text-decoration:none;display:inline-block;">
                Ver catálogo completo
            </a>
        </div>
    </section>

    <section class="seccion-filtros">
        <div class="contenedor-filtros">
            <div class="buscador-catalogo">
                <input type="text" id="buscar-cursos" placeholder="Buscar cursos...">
            </div>
            <div class="grupo-filtros">
                <button class="filtro-activo" data-filtro="todos">Todos</button>
                <button data-filtro="disponibles">Disponibles</button>
                <button data-filtro="proximos">Próximos</button>
                <button data-filtro="gratuitos">Gratuitos</button>
            </div>
        </div>
    </section>

    <section class="seccion-por-fecha" id="disponibles">
        <div class="encabezado-seccion">
            <h2>Disponibles ahora</h2>
            <p class="descripcion-seccion">Acceso inmediato al contenido completo</p>
        </div>
        <div class="contenedor-tarjetas">
            <?php if (!empty($cursos_disponibles)): ?>
                <?php foreach ($cursos_disponibles as $curso): ?>
                    <a href="<?=$base?>/cursos/ver/<?= $curso['id'] ?>" class="tarjeta-curso">
                        <div class="etiqueta-estado disponible">Disponible</div>
                        <div class="cuerpo-tarjeta">
                            <?php 
                            // MOSTRAR IMAGEN O PLACEHOLDER
                            if (!empty($curso['thumbnail'])): 
                                $imagen = trim($curso['thumbnail']);
                                if (strpos($imagen, 'http') === 0):
                            ?>
                                <img src="<?= htmlspecialchars($imagen) ?>" 
                                     alt="<?= htmlspecialchars($curso['title']) ?>"
                                     loading="lazy"
                                     style="width:100%; height:140px; object-fit:cover; border-radius:6px; margin-bottom:12px; display:block;">
                            <?php else: ?>
                                <div style="width:100%; height:140px; background:#1a237e; border-radius:6px; margin-bottom:12px; display:flex; align-items:center; justify-content:center; color:white; font-size:24px;">
                                    📚
                                </div>
                            <?php endif; else: ?>
                                <div style="width:100%; height:140px; background:#1a237e; border-radius:6px; margin-bottom:12px; display:flex; align-items:center; justify-content:center; color:white; font-size:24px;">
                                    📚
                                </div>
                            <?php endif; ?>
                            
                            <h3><?= htmlspecialchars($curso['title']) ?></h3>
                            <p class="descripcion-corta"><?= htmlspecialchars($curso['short_description'] ?? '') ?></p>
                            <div class="pie-tarjeta">
                                <span class="precio-tarjeta">
                                    <?php if (!empty($curso['is_free_course'])): ?>
                                        Gratis
                                    <?php else: ?>
                                        $<?= number_format($curso['price'] ?? 0, 2) ?> USD
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <section class="seccion-por-fecha" id="todos">
        <div class="encabezado-seccion">
            <h2>Catálogo completo</h2>
            <p class="descripcion-seccion">Explora todos los contenidos disponibles</p>
        </div>
        <div class="contenedor-tarjetas">
            <?php foreach ($cursos as $curso): ?>
                <a href="<?=$base?>/cursos/ver/<?= $curso['id'] ?>" class="tarjeta-curso">
                    <div class="etiqueta-estado disponible">Disponible</div>
                    <div class="cuerpo-tarjeta">
                        <?php 
                        if (!empty($curso['thumbnail'])): 
                            $imagen = trim($curso['thumbnail']);
                            if (strpos($imagen, 'http') === 0):
                        ?>
                            <img src="<?= htmlspecialchars($imagen) ?>" 
                                 alt="<?= htmlspecialchars($curso['title']) ?>"
                                 loading="lazy"
                                 style="width:100%; height:140px; object-fit:cover; border-radius:6px; margin-bottom:12px; display:block;">
                        <?php else: ?>
                            <div style="width:100%; height:140px; background:#1a237e; border-radius:6px; margin-bottom:12px; display:flex; align-items:center; justify-content:center; color:white; font-size:24px;">
                                📚
                            </div>
                        <?php endif; else: ?>
                            <div style="width:100%; height:140px; background:#1a237e; border-radius:6px; margin-bottom:12px; display:flex; align-items:center; justify-content:center; color:white; font-size:24px;">
                                📚
                            </div>
                        <?php endif; ?>
                        
                        <h3><?= htmlspecialchars($curso['title']) ?></h3>
                        <p class="descripcion-corta"><?= htmlspecialchars($curso['short_description'] ?? '') ?></p>
                        <div class="pie-tarjeta">
                            <span class="precio-tarjeta">
                                <?php if (!empty($curso['is_free_course'])): ?>
                                    Gratis
                                <?php else: ?>
                                    $<?= number_format($curso['price'] ?? 0, 2) ?> USD
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
</div>
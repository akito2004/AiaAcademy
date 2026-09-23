<?php
$base = $GLOBALS['config']['app']['base_path'] ?? '';
echo '<link rel="stylesheet" href="' . $base . '/Assets/css/general.css">';
echo '<script src="' . $base . '/Assets/js/general.js" defer></script>';
echo '<link rel="stylesheet" href="' . $base . '/modules/cursos/Assets/css/cursos.css">';
echo '<link rel="stylesheet" href="' . $base . '/modules/cursos/Assets/css/ruta.css">';
echo '<script src="' . $base . '/modules/cursos/Assets/js/cursos.js" defer></script>';
echo '<script src="' . $base . '/modules/cursos/Assets/js/ruta.js" defer></script>';
extract($data);
?>
<div class="detalle-curso">
    <div class="detalle-contenido">
        
        <div class="ruta-navegacion">
            <a href="<?=$base?>/cursos">Cursos</a> / <span><?= htmlspecialchars($curso['title']) ?></span>
        </div>
        <div class="fila-principal">
            <div class="col-izquierda">
                <span class="etiqueta-tipo">Disponible ahora</span>
                <h1 class="titulo-curso"><?= htmlspecialchars($curso['title']) ?></h1>
                <p class="descripcion-corta">
                    <?= htmlspecialchars($curso['short_description'] ?? $curso['description'] ?? 'Sin descripción disponible.') ?>
                </p>
                <div class="botones-accion">
                    <button class="btn-principal" id="btn-inscribir">
                        <?= !empty($curso['is_free_course']) ? 'Empezar gratis' : 'Comprar curso — $'.htmlspecialchars($curso['price'] ?? '0').' USD' ?>
                    </button>
                    <button class="btn-secundario" id="btn-guardar">Guardar</button>
                   <button class="btn-secundario" id="btn-avanzar">Avanzar Cursos</button>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('btn-avanzar');
    if (btn) {
        btn.addEventListener('click', function() {
            const match = window.location.pathname.match(/\/ver\/(\d+)/);
            const id = match ? match[1] : '30';
            window.location.href = (window.location.pathname.includes('/AiaAcademy') ? '/AiaAcademy' : '') + '/cursos/avance/' + id;
        });
    }
});
</script>
                </div>
                <div class="datos-curso">
                    <div class="dato-item">
                        <span class="dato-etiqueta">Módulos:</span>
                        <span id="contador-modulos"><?= count($secciones ?? []) ?> unidades</span>
                    </div>
                    <div class="dato-item">
                        <span class="dato-etiqueta">Duración:</span>
                        <span>~15 horas de contenido</span>
                    </div>
                    <div class="dato-item">
                        <span class="dato-etiqueta">Certificado:</span>
                        <span>Al finalizar</span>
                    </div>
                    <div class="dato-item">
                        <span class="dato-etiqueta">Acceso:</span>
                        <span>Ilimitado</span>
                    </div>
                </div>
            </div>
            <div class="col-derecha">
                <div class="tarjeta-curso-destacada">
                    <!-- VIDEO SI EXISTE -->
                    <?php if (!empty($curso['video_url'])): 
                        $url = trim($curso['video_url']);
                    ?>
                        <div style="width:100%; border-radius:8px; overflow:hidden; min-height:220px;">
                            <?php 
                            // 1. Archivo propio .mp4 / .webm
                            if (preg_match('/\.(mp4|webm|ogg|mov)$/i', $url)): 
                            ?>
                                <video 
                                    src="<?= htmlspecialchars($url) ?>"
                                    controls
                                    style="width:100%; border-radius:8px; min-height:220px;"
                                    preload="metadata">
                                    Tu navegador no puede reproducir este video.
                                </video>
                            <?php 
                            // 2. TikTok
                            elseif (strpos($url, 'tiktok.com') !== false): 
                            ?>
                                <iframe 
                                    src="<?= htmlspecialchars($url) ?>" 
                                    title="Video de TikTok"
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen
                                    style="width:100%; height:420px; border-radius:8px;">
                                </iframe>
                            <?php 
                            // 3. Cualquier otro enlace (Vimeo, etc.)
                            else: 
                            ?>
                                <iframe 
                                    src="<?= htmlspecialchars($url) ?>" 
                                    title="Presentación: <?= htmlspecialchars($curso['title']) ?>"
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen
                                    style="width:100%; height:240px; border-radius:8px;">
                                </iframe>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <!-- IMAGEN SI NO HAY VIDEO -->
                        <div class="tarjeta-imagen-grande">
                            <?php if (!empty($curso['thumbnail'])): ?>
                                <img src="<?= htmlspecialchars($curso['thumbnail']) ?>" 
                                     alt="<?= htmlspecialchars($curso['title']) ?>"
                                     style="width:100%; height:100%; object-fit:cover; border-radius:8px;">
                            <?php else: ?>
                                <span class="inicial-super"><?= mb_strtoupper(mb_substr($curso['title'], 0, 1)) ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="tarjeta-info">
                        <p class="precio-grande">
                            <?php if (!empty($curso['is_free_course'])): ?>
                                <span class="etiqueta-gratis">Gratis</span>
                            <?php else: ?>
                                <span class="precio-anterior">$<?= htmlspecialchars(($curso['price'] ?? 0) * 1.5) ?></span>
                                <span class="precio-actual">$<?= htmlspecialchars($curso['price'] ?? '0') ?> USD</span>
                            <?php endif; ?>
                        </p>
                        <button class="btn-verde" id="btn-acceder">
                            <?= !empty($curso['is_free_course']) ? 'Acceder ahora' : 'Inscribirse ahora' ?>
                        </button>
                        <p class="garantia">Garantía de satisfacción de 30 días</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="contenedor-pestanas">
            <div class="pestañas">
                <button class="pestaña-activa" data-pestaña="aprender">Lo que aprenderás</button>
                <button data-pestaña="temario">Temario</button>
                <button data-pestaña="ruta">Ruta de aprendizaje</button>
                <button data-pestaña="docente">Tu docente</button>
                <button data-pestaña="opiniones">Opiniones</button>
            </div>
            
            <!-- LO QUE APRENDERÁS -->
            <div class="contenido-pestaña" id="aprender">
                <div class="grid-aprender">
                    <div class="col-lista">
                        <h3>Competencias a desarrollar</h3>
                        <ul class="lista-beneficios">
                            <?php if (!empty($curso['outcomes'])): ?>
                                <li><?= str_replace("\n", '</li><li>', htmlspecialchars($curso['outcomes'])) ?></li>
                            <?php else: ?>
                                <li>Comprender los fundamentos y conceptos clave del área</li>
                                <li>Desarrollar proyectos funcionales desde cero</li>
                                <li>Aplicar buenas prácticas y estándares profesionales</li>
                                <li>Resolver problemas de forma autónoma</li>
                                <li>Prepararse para oportunidades laborales</li>
                                <li>Acceder a actualizaciones del contenido</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <div class="col-requisitos">
                        <h3>Requisitos previos</h3>
                        <ul class="lista-requisitos">
                            <li>Conocimientos básicos de uso de computadora</li>
                            <li>Disposición para aprender y practicar</li>
                            <li>Conexión a internet</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- TEMARIO -->
            <div class="contenido-pestaña oculto" id="temario">
                <div class="buscador-temario">
                    <input type="text" id="buscar-temas" placeholder="Buscar en el temario...">
                </div>
                <div class="aviso-modulos">
                    <span>Los módulos se liberan de forma progresiva. Se recomienda avanzar a ritmo constante.</span>
                </div>
                <?php if (!empty($secciones)): ?>
                    <?php foreach ($secciones as $sec): ?>
                        <div class="modulo-temario">
                            <button class="boton-modulo" onclick="toggleModulo(this)">
                                <div>
                                    <strong><?= (int)$sec['order'] ?>. <?= htmlspecialchars($sec['title']) ?></strong>
                                    <span class="estado-modulo">Disponible</span>
                                </div>
                                <span class="indicador">▼</span>
                            </button>
                            <div class="subtemas">
                                <?php if (!empty($sec['lecciones'])): ?>
                                    <?php foreach ($sec['lecciones'] as $lec): ?>
                                        <label class="tema-item">
                                            <input type="checkbox"> 
                                            <?= htmlspecialchars($lec['title']) ?>
                                            <?php if (!empty($lec['duration'])): ?>
                                                <small style="color:#666; margin-left:8px;"><?= htmlspecialchars($lec['duration']) ?></small>
                                            <?php endif; ?>
                                        </label>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>Lecciones por agregar.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Temario en preparación.</p>
                <?php endif; ?>
            </div>
            
            <!-- RUTA DE APRENDIZAJE -->
            <div class="contenido-pestaña oculto" id="ruta">
                <h2 class="ruta-titulo">Ruta de aprendizaje</h2>
                <p class="ruta-subtitulo">Sigue el orden sugerido y avanza paso a paso</p>
                <div class="ruta-contenedor">
                    <div class="ruta-linea"></div>
                    <?php if (!empty($secciones)): ?>
                        <?php foreach ($secciones as $indice => $sec): ?>
                            <div class="paso-item">
                                <div class="paso-numero <?= $indice === 0 ? 'activo' : '' ?>"><?= (int)$sec['order'] ?></div>
                                <div class="paso-tarjeta <?= $indice === 0 ? 'activa' : '' ?>">
                                    <h3 class="paso-titulo"><?= htmlspecialchars($sec['title']) ?></h3>
                                    <p class="paso-descripcion">
                                        <?php 
                                            $totalLecciones = count($sec['lecciones'] ?? []);
                                            echo $totalLecciones > 0 ? "Contiene {$totalLecciones} lecciones prácticas" : "Contenido en preparación";
                                        ?>
                                    </p>
                                    <span class="paso-etiqueta etiqueta-disponible">Disponible</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Contenido en preparación.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- TU DOCENTE -->
            <div class="contenido-pestaña oculto" id="docente">
                <div class="tarjeta-docente-grande">
                    <?php if (!empty($curso['docente_foto'])): ?>
                        <div class="avatar-docente-grande">
                            <img src="<?= htmlspecialchars($curso['docente_foto']) ?>" 
                                 alt="Foto del docente"
                                 style="width:100%; height:100%; object-fit:cover; border-radius:50%;">
                        </div>
                    <?php else: ?>
                        <div class="avatar-docente-grande">
                            <?= mb_strtoupper(mb_substr(!empty($curso['docente_nombre']) ? $curso['docente_nombre'] : 'D', 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="info-docente">
                        <h3>
                            <?= htmlspecialchars(
                                ($curso['docente_nombre'] ?? '') . ' ' . ($curso['docente_apellido'] ?? '')
                            ) ?: 'Docente AiaAcademy' ?>
                        </h3>
                        
                        <p class="rol-docente">Instructor especializado</p>
                        
                        <?php if (!empty($curso['docente_especialidad'])): ?>
                            <p class="bio-docente"><?= htmlspecialchars($curso['docente_especialidad']) ?></p>
                        <?php endif; ?>
                        
                        <div class="estadisticas-docente">
                            <div class="stat">
                                <div class="stat-valor">20+</div>
                                <div class="stat-etiqueta">Cursos dictados</div>
                            </div>
                            <div class="stat">
                                <div class="stat-valor">5.0 / 5</div>
                                <div class="stat-etiqueta">Calificación</div>
                            </div>
                            <div class="stat">
                                <div class="stat-valor">5000+</div>
                                <div class="stat-etiqueta">Estudiantes</div>
                            </div>
                        </div>
                        
                        <button class="btn-enlace" id="btn-contactar">Enviar mensaje al docente</button>
                    </div>
                </div>
            </div>
            
            <!-- OPINIONES -->
            <div class="contenido-pestaña oculto" id="opiniones">
                <div class="resumen-opiniones">
                    <div class="calificacion-principal">5.0</div>
                    <div class="estrellas">★★★★★</div>
                    <p>Sé el primero en valorar este curso.</p>
                </div>
                <button class="btn-verde" id="btn-valorar">Escribir una valoración</button>
            </div>
        </div>
        
        <div class="seccion-conclusion">
            <h2>¿Listo para iniciar?</h2>
            <p>No se requieren conocimientos previos. Avanza a tu propio ritmo y consolida lo aprendido con cada unidad.</p>
            <button class="btn-principal btn-grande" id="btn-acceder-final">
                <?= !empty($curso['is_free_course']) ? 'Comenzar ahora' : 'Inscribirse al curso' ?>
            </button>
        </div>
    </div>
</div>
<div id="ventana" class="ventana-modal oculto">
    <div class="ventana-contenido">
        <button class="cerrar-ventana" id="cerrar-ventana">&times;</button>
        <h4 id="ventana-titulo"></h4>
        <p id="ventana-mensaje"></p>
        <button class="btn-verde" id="ventana-aceptar">Entendido</button>
    </div>
</div>
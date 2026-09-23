<?php
return [
    // Catálogo de cursos
    '/cursos' => ['controller' => 'IndexController', 'action' => 'index'],
    
    // Ver detalle del curso
    '/cursos/ver/(:num)' => ['controller' => 'IndexController', 'action' => 'ver', 'params' => [1]],
    
    // Página de avance
    '/cursos/avance' => ['controller' => 'IndexController', 'action' => 'avance'],
    '/cursos/avance/(:num)' => ['controller' => 'IndexController', 'action' => 'avance', 'params' => [1]],
    
    // Marcar progreso (AJAX)
    '/cursos/marcar-video-visto' => ['controller' => 'IndexController', 'action' => 'marcarVideoVisto'],
    '/cursos/marcar-cuestionario-aprobado' => ['controller' => 'IndexController', 'action' => 'marcarCuestionarioAprobado'],
    
    // Página de exámenes
    '/cursos/preguntas' => ['controller' => 'IndexController', 'action' => 'preguntas'],
    
    // Página de certificado
    '/cursos/certificado/(:num)' => ['controller' => 'IndexController', 'action' => 'certificado', 'params' => [1]],
];
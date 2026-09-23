<?php
namespace Aia\Core;

class Router
{
    private array $modules = [];

    public function registerModule(string $name, string $path): void
    {
        $this->modules[$name] = [
            'path'   => $path,
            'routes' => $this->loadRoutes($path),
        ];
    }

    private function loadRoutes(string $path): array
    {
        $file = $path . '/routes.php';
        if (file_exists($file)) {
            return require $file;
        }
        return [];
    }

    public function dispatch(string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        
        // Quitar base_path si está configurado
        $basePath = $GLOBALS['config']['app']['base_path'] ?? '';
        if ($basePath !== '' && strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
        }
        
        // Quitar /public del inicio si existe
        if (strpos($path, '/public') === 0) {
            $path = substr($path, 7);
        }
        
        $path = rtrim($path, '/');
        if ($path === '') {
            $path = '/';
        }
        
        $segments = explode('/', ltrim($path, '/'));
        $moduleName = $segments[0] ?? '';
        
        if ($moduleName === '') {
            $moduleName = $GLOBALS['config']['app']['default_module'] ?? 'home';
        }
        
        if (!isset($this->modules[$moduleName])) {
            http_response_code(404);
            echo "404 - Módulo no encontrado: " . htmlspecialchars($moduleName);
            return;
        }
        
        $controllerName = 'Aia\\Modules\\' . $moduleName . '\\IndexController';
        
        if (!class_exists($controllerName)) {
            echo "404 - Controlador no existe: " . htmlspecialchars($controllerName);
            return;
        }
        
        $controller = new $controllerName();

        // ==================================================
        // RUTAS DEL MÓDULO DOCENTES
        // ==================================================
        if ($moduleName === 'docentes' && ($segments[1] ?? '') === 'ver') {
            $id = $segments[2] ?? null;
            if (method_exists($controller, 'verDetalle')) {
                $controller->verDetalle($id);
                return;
            }
        }

        // ==================================================
        // RUTAS DEL MÓDULO CURSOS
        // ==================================================
        
        // /cursos/ver/ID → Ver detalle del curso
        if ($moduleName === 'cursos' && ($segments[1] ?? '') === 'ver') {
            $id = $segments[2] ?? null;
            if (method_exists($controller, 'ver')) {
                $controller->ver($id);
                return;
            }
        }

        // /cursos/avance/ID → Página de avance del curso
        if ($moduleName === 'cursos' && ($segments[1] ?? '') === 'avance') {
            $id = $segments[2] ?? null;
            if (method_exists($controller, 'avance')) {
                $controller->avance($id);
                return;
            }
        }

        // /cursos/marcar-video-visto → AJAX: Marcar video como visto
        if ($moduleName === 'cursos' && ($segments[1] ?? '') === 'marcar-video-visto') {
            if (method_exists($controller, 'marcarVideoVisto')) {
                $controller->marcarVideoVisto();
                return;
            }
        }

        // /cursos/marcar-cuestionario-aprobado → AJAX: Marcar cuestionario aprobado
        if ($moduleName === 'cursos' && ($segments[1] ?? '') === 'marcar-cuestionario-aprobado') {
            if (method_exists($controller, 'marcarCuestionarioAprobado')) {
                $controller->marcarCuestionarioAprobado();
                return;
            }
        }

        // /cursos/preguntas → Página de exámenes
        if ($moduleName === 'cursos' && ($segments[1] ?? '') === 'preguntas') {
            if (method_exists($controller, 'preguntas')) {
                $controller->preguntas();
                return;
            }
        }

        // /cursos/certificado/ID → Página para obtener certificado
        if ($moduleName === 'cursos' && ($segments[1] ?? '') === 'certificado') {
            $id = $segments[2] ?? null;
            if (method_exists($controller, 'certificado')) {
                $controller->certificado($id);
                return;
            }
        }

        // /blog/articulo/ID -> Página de detalle de una publicación
        if ($moduleName === 'blog' && ($segments[1] ?? '') === 'articulo') {
            $id = $segments[2] ?? null;
            if (method_exists($controller, 'article')) {
                $controller->article($id);
                return;
            }
        }

        // Ruta por defecto del módulo
        $controller->index();
    }
}
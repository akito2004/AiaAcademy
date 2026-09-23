<?php
namespace Aia\Modules\blog;

use Aia\Core\View;

require_once __DIR__ . '/../../../app/Config/conexion.php';

class IndexController
{
    public function index(): void
    {
        $posts = [];
        $db = conectarDB();
        $query = $db->query(
        "SELECT id, titulo, resumen, contenido, imagen_url, autor_nombre, tiempo_lectura, fecha_creacion
             FROM blog_posts
             WHERE estado = 'publicado'
             ORDER BY fecha_creacion DESC"
        );

        foreach ($query->fetchAll() as $index => $post) {
            $content = trim(strip_tags((string) $post['contenido']));
            $posts[] = [
                'id' => (int) $post['id'],
                'title' => $post['titulo'],
                'excerpt' => $post['resumen'] ?: (function_exists('mb_substr')
                    ? mb_substr($content, 0, 180) . (mb_strlen($content) > 180 ? '...' : '')
                    : substr($content, 0, 180)),
                'category' => 'AiaAcademy',
                'read_time' => $post['tiempo_lectura'] ?: '5 min lectura',
                'author' => $post['autor_nombre'] ?: 'AiaAcademy',
                'imageUrl' => $post['imagen_url'],
                'createdAt' => $post['fecha_creacion'],
                'imageTone' => $index === 0 ? 'hero-red' : 'blue',
                'featured' => $index === 0,
                'url' => ($GLOBALS['config']['app']['base_path'] ?? '') . '/blog/articulo/' . (int) $post['id'],
            ];
        }

        $data = [
            'pageTitle' => 'Blog | AiaAcademy',
            'moduleCss' => '/modules/blog/Assets/css/blog.css',
            'moduleCssVersion' => filemtime(BASE_PATH . '/modules/blog/Assets/css/blog.css'),
            'moduleJs'  => '/modules/blog/Assets/js/blog.js',
            'posts'     => $posts,
        ];
        View::render(BASE_PATH . '/modules/blog/Views/index.php', $data);
    }

    public function article($id): void
    {
        $postId = filter_var($id, FILTER_VALIDATE_INT);
        if (!$postId) {
            http_response_code(404);
            echo 'Artículo no encontrado';
            return;
        }

        $db = conectarDB();
        $statement = $db->prepare(
            "SELECT id, titulo, resumen, contenido, imagen_url, autor_nombre, tiempo_lectura, enlaces_relacionados, fecha_creacion
             FROM blog_posts WHERE id = :id AND estado = 'publicado' LIMIT 1"
        );
        $statement->execute(['id' => $postId]);
        $post = $statement->fetch();

        if (!$post) {
            http_response_code(404);
            echo 'Artículo no encontrado';
            return;
        }

        $links = [];
        foreach (preg_split('/\r\n|\r|\n/', (string) $post['enlaces_relacionados']) as $line) {
            $parts = array_map('trim', explode('|', $line, 2));
            if (count($parts) === 2 && filter_var($parts[1], FILTER_VALIDATE_URL)) {
                $links[] = ['label' => $parts[0] ?: $parts[1], 'url' => $parts[1]];
            }
        }

        View::render(BASE_PATH . '/modules/blog/Views/article.php', [
            'pageTitle' => $post['titulo'] . ' | AiaAcademy',
            'moduleCss' => '/modules/blog/Assets/css/blog.css',
            'moduleCssVersion' => filemtime(BASE_PATH . '/modules/blog/Assets/css/blog.css'),
            'post' => $post,
            'links' => $links,
        ]);
    }
}

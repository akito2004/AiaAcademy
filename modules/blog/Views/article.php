<?php
$safeContent = strip_tags((string) $post['contenido'], '<p><br><strong><b><em><i><u><h2><h3><h4><ul><ol><li><blockquote><a><img><pre><code><iframe>');
?>
<div class="blog-article-page">
    <header class="article-detail-header">
        <a href="<?= htmlspecialchars(($GLOBALS['config']['app']['base_path'] ?? '') . '/blog') ?>" class="article-back">← Volver al blog</a>
        <span class="article-card-brand">AiaAcademy</span>
        <h1><?= htmlspecialchars($post['titulo']) ?></h1>
        <?php if (!empty($post['resumen'])): ?><p><?= htmlspecialchars($post['resumen']) ?></p><?php endif; ?>
        <div class="article-detail-meta">
            <span>◷ <?= htmlspecialchars($post['tiempo_lectura'] ?: '5 min lectura') ?></span>
            <span>✦ <?= htmlspecialchars($post['autor_nombre'] ?: 'AiaAcademy') ?></span>
        </div>
    </header>

    <div class="article-detail-layout">
        <article class="article-detail-content">
            <?php if (!empty($post['imagen_url'])): ?><img class="article-cover" src="<?= htmlspecialchars($post['imagen_url'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($post['titulo']) ?>"><?php endif; ?>
            <div class="article-rich-content"><?= $safeContent ?></div>
        </article>
        <aside class="article-detail-sidebar">
            <h2>Autor del artículo</h2>
            <p><?= htmlspecialchars($post['autor_nombre'] ?: 'AiaAcademy') ?></p>
            <?php if (!empty($links)): ?>
                <h2>Enlaces relacionados</h2>
                <ul><?php foreach ($links as $link): ?><li><a href="<?= htmlspecialchars($link['url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars($link['label']) ?></a></li><?php endforeach; ?></ul>
            <?php endif; ?>
        </aside>
    </div>
</div>

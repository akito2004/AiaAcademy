<?php
$base = $GLOBALS['config']['app']['base_path'] ?? '';
$featured = null;
foreach ($posts as $post) {
    if (!empty($post['featured'])) {
        $featured = $post;
        break;
    }
}
$secondary = array_values(array_filter($posts, fn($post) => empty($post['featured'])));
?>
<div class="blog-shell">
    <header class="blog-header">
        <div class="brand-wrap">
            <div class="brand-icon">◔</div>
            <span class="brand-text">AiaAcademy</span>
        </div>
        <div class="header-actions">
            <div class="search-box">Buscar...</div>
            <button class="icon-button">⌕</button>
            <button class="icon-button">☰</button>
        </div>
    </header>

    <main class="blog-layout">
        <section class="featured-column">
            <?php if ($featured): ?>
                <a class="hero-post hero-<?= htmlspecialchars($featured['imageTone']) ?>" href="<?= htmlspecialchars($featured['url']) ?>"<?php if (!empty($featured['imageUrl'])): ?> style="background-image: linear-gradient(90deg, rgba(0,0,0,.65), rgba(0,0,0,.15)), url('<?= htmlspecialchars($featured['imageUrl'], ENT_QUOTES, 'UTF-8') ?>'); background-size: cover; background-position: center;"<?php endif; ?>>
                    <div class="hero-tag">AiaAcademy</div>
                    <h1><?= htmlspecialchars($featured['title']) ?></h1>
                    <p class="hero-subtitle"><?= htmlspecialchars($featured['excerpt']) ?></p>
                    <div class="hero-label">Post destacado</div>
                </a>
            <?php endif; ?>

            <article class="article-summary">
                <h2><?= htmlspecialchars($featured['title'] ?? 'Publicación destacada') ?></h2>
                <p><?= htmlspecialchars($featured['excerpt'] ?? 'Contenido destacado para la comunidad.') ?></p>
                <span class="article-link">Publicado en el blog de AiaAcademy</span>
            </article>
        </section>

        <aside class="sidebar-column">
            <h2>Lo más leído</h2>

            <?php foreach (array_slice($secondary, 0, 3) as $post): ?>
                <a class="mini-post mini-<?= htmlspecialchars($post['imageTone']) ?>" href="<?= htmlspecialchars($post['url']) ?>">
                    <div class="mini-thumb"<?php if (!empty($post['imageUrl'])): ?> style="background-image: url('<?= htmlspecialchars($post['imageUrl'], ENT_QUOTES, 'UTF-8') ?>'); background-size: cover; background-position: center;"<?php endif; ?>>
                        <span class="mini-tag">AiaAcademy</span>
                    </div>
                    <div class="mini-content">
                        <h3><?= htmlspecialchars($post['title']) ?></h3>
                        <div class="meta-row">
                            <span><?= htmlspecialchars($post['read_time']) ?></span>
                            <span><?= htmlspecialchars($post['category']) ?></span>
                        </div>
                        <div class="author-line">
                            <span class="avatar">A</span>
                            <span><?= htmlspecialchars($post['author']) ?></span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </aside>
    </main>

    <?php if (!empty($secondary)): ?>
        <section class="latest-articles">
            <div class="latest-heading">
                <h2>Últimos artículos</h2>
                <span><?php echo count($secondary); ?> publicaciones</span>
            </div>
            <div class="article-grid">
                <?php foreach ($secondary as $post): ?>
                    <a class="article-card" href="<?= htmlspecialchars($post['url']) ?>">
                        <div class="article-card-image article-card-<?= htmlspecialchars($post['imageTone']) ?>"<?php if (!empty($post['imageUrl'])): ?> style="background-image: linear-gradient(180deg, rgba(5, 12, 25, .05), rgba(5, 12, 25, .5)), url('<?= htmlspecialchars($post['imageUrl'], ENT_QUOTES, 'UTF-8') ?>');"<?php endif; ?>>
                            <span class="article-card-brand">AiaAcademy</span>
                        </div>
                        <div class="article-card-body">
                            <h3><?= htmlspecialchars($post['title']) ?></h3>
                            <div class="article-card-meta">
                                <span>◷ <?= htmlspecialchars($post['read_time']) ?></span>
                                <span>▣ <?= htmlspecialchars($post['category']) ?></span>
                            </div>
                            <div class="article-card-author">
                                <span class="avatar">A</span>
                                <span><?= htmlspecialchars($post['author']) ?></span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</div>

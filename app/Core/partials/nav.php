<?php
/** nav.php - Barra de navegacion global compartida. */
$navItems = [
    'home'      => ['Inicio', 'home'],
    'nosotros'  => ['Nosotros', 'nosotros'],
    'docentes'  => ['Docentes', 'docentes'],
    'cursos'    => ['Cursos', 'cursos'],
    'blog'      => ['Blog', 'blog'],
    'contactos' => ['Contactos', 'contactos'],
];
?>
<nav class="navbar">
    <a class="navbar-brand" href="<?= site_url() ?>">Aia<span>Academy</span></a>
    <ul class="navbar-nav">
        <?php foreach ($navItems as $item): ?>
        <li><a href="<?= site_url($item[1]) ?>"><?= $item[0] ?></a></li>
        <?php endforeach; ?>
    </ul>
</nav>

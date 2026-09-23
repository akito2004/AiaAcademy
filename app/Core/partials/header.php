<?php
/** header.php - Parcial global compartido. Todos los modulos lo reutilizan. */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'AiaAcademy') ?></title>
    <?php if (!empty($metaDescription)): ?>
    <meta name="description" content="<?= htmlspecialchars($metaDescription) ?>">
    <?php endif; ?>
    <!-- CSS global compartido -->
    <link rel="stylesheet" href="<?= \Aia\Core\View::asset('public/assets/css/main.css') ?>">
    <!-- CSS especifico del modulo (si existe) -->
    <?php if (isset($moduleCss) && file_exists(BASE_PATH . $moduleCss)): ?>
    <link rel="stylesheet" href="<?= \Aia\Core\View::asset(ltrim($moduleCss, '/')) ?><?= isset($moduleCssVersion) ? '?v=' . urlencode((string) $moduleCssVersion) : '' ?>">
    <?php endif; ?>
</head>
<body>
<?php require BASE_PATH . '/app/Core/partials/nav.php'; ?>
<main>

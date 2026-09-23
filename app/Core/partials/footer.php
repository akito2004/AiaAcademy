<?php
/** footer.php - Parcial global compartido. Cierra HTML y carga JS global. */
?>
</main>
<footer>
    <a class="footer-brand" href="<?= site_url() ?>"><span class="footer-aia">Aia</span><span class="footer-academy">Academy</span></a>
    <p>&copy; <?= date('Y') ?> AiaAcademy. Todos los derechos reservados.</p>
</footer>
<!-- JS global compartido -->
<script src="<?= \Aia\Core\View::asset('public/assets/js/main.js') ?>" defer></script>
<!-- JS especifico del modulo (si existe) -->
<?php if (isset($moduleJs) && file_exists(BASE_PATH . $moduleJs)): ?>
<script src="<?= \Aia\Core\View::asset(ltrim($moduleJs, '/')) ?>" defer></script>
<?php endif; ?>
</body>
</html>

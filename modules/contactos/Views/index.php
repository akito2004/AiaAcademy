<?php
/** Vista del modulo contactos */
$contactEmail = 'contacto@aiaacademy.com';
$contactPhone = '+51 999 888 777';
?>
<section class="contacto container">
    <div class="contacto-intro">
        <h1>Contáctanos</h1>
        <p>¿Tienes dudas sobre nuestros cursos? Escríbenos y te respondemos a la brevedad.</p>
        <ul class="contacto-info">
            <li><strong>Correo:</strong> <?= e($contactEmail) ?></li>
            <li><strong>Teléfono:</strong> <?= e($contactPhone) ?></li>
        </ul>
    </div>

    <?php if ($sent): ?>
        <div class="alerta alerta-exito">
            <strong>¡Mensaje enviado!</strong> Gracias por escribirnos, te contactaremos pronto.
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alerta alerta-error">
            <strong>Revisa los siguientes campos:</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                <li><?= e($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= e(site_url('contactos')) ?>" class="form-contacto">
        <div class="campo">
            <label for="nombre">Nombre completo</label>
            <input type="text" id="nombre" name="nombre" value="<?= e($old['nombre'] ?? '') ?>" required>
        </div>

        <div class="campo">
            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" value="<?= e($old['email'] ?? '') ?>" required>
        </div>

        <div class="campo">
            <label for="mensaje">Mensaje</label>
            <textarea id="mensaje" name="mensaje" rows="5" required><?= e($old['mensaje'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn">Enviar mensaje</button>
    </form>
</section>
<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/funciones.php';
require_once __DIR__ . '/inc/sesion.php';
iniciarSesion();
$aventurero = requiereLogin();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $temaPost = $_POST['tema'] ?? null;
    if (is_string($temaPost) && in_array($temaPost, TEMAS, true)) {
        guardarTema($temaPost);
        mensajeFlash('Preferencia de tema guardada.');
    } else {
        mensajeFlash('Tema no válido.');
    }
    header('Location: preferencias.php'); exit;
}
$temaGuardado = temaActual();
$flash = leerFlash();
$temaCrudo = $_COOKIE[COOKIE_TEMA] ?? '(sin definir)';
if (!is_string($temaCrudo)) $temaCrudo = '(valor no válido)';
$titulo = 'Preferencias';
require __DIR__ . '/inc/cabecera.php';
?>
<section class="tarjeta estrecha">
    <h1>Preferencias</h1>
    <?php if ($flash !== null): ?><p class="aviso"><?= e($flash) ?></p><?php endif; ?>
    <form method="post" action="preferencias.php">
        <fieldset><legend>Tema del tablón</legend>
            <?php foreach (TEMAS as $opcion): ?>
                <label class="casilla"><input type="radio" name="tema" value="<?= e($opcion) ?>" <?= $opcion === $temaGuardado ? 'checked' : '' ?>><?= e(ucfirst($opcion)) ?></label>
            <?php endforeach; ?>
        </fieldset>
        <button type="submit">Guardar</button>
    </form>
    <p class="nota">Valor recibido en la cookie: <code><?= e($temaCrudo) ?></code>. Tema aplicado: <?= e($temaGuardado) ?>.</p>
</section>
<?php require __DIR__ . '/inc/pie.php'; ?>

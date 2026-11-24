<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/funciones.php';
require_once __DIR__ . '/inc/sesion.php';
iniciarSesion();
$aventurero = requiereLogin();
$misiones = require __DIR__ . '/inc/datos_misiones.php';
$_SESSION['zurron'] ??= [];
$_SESSION['visitas'] = ($_SESSION['visitas'] ?? 0) + 1;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idPost = $_POST['id'] ?? '';
    $id = is_string($idPost) ? $idPost : '';
    $mision = buscarMision($misiones, $id);
    if ($mision === null) {
        mensajeFlash('Esa misión no existe en el tablón.');
    } elseif (in_array($id, $_SESSION['zurron'], true)) {
        mensajeFlash('Esa misión ya está en tu zurrón.');
    } else {
        $_SESSION['zurron'][] = $id;
        mensajeFlash('Misión añadida a tu zurrón.');
    }
    header('Location: tablon.php'); exit;
}
$rango = leerRangoFiltro($_GET);
$oroMinimo = leerOroMinimo($_GET);
$visibles = filtrarMisiones($misiones, $rango, $oroMinimo);
$flash = leerFlash();
$titulo = 'Tablón de misiones';
require __DIR__ . '/inc/cabecera.php';
?>
<h1>Tablón de misiones</h1>
<p class="nota">Entrada: <?= e((string)($_SESSION['entrada'] ?? '')) ?> · Visitas a este tablón: <?= (int)$_SESSION['visitas'] ?></p>
<?php if ($flash !== null): ?><p class="aviso"><?= e($flash) ?></p><?php endif; ?>
<form method="get" action="tablon.php" class="filtros">
    <label>Rango<select name="rango"><option value="">Todos</option><?php foreach (RANGOS as $r): ?><option value="<?= e($r) ?>" <?= $r === $rango ? 'selected' : '' ?>><?= e($r) ?> · <?= e(etiquetaRango($r)) ?></option><?php endforeach; ?></select></label>
    <label>Recompensa mínima<input type="number" name="oro" min="0" max="10000" step="10" value="<?= $oroMinimo ?>"></label>
    <button type="submit">Filtrar</button>
</form>
<p><?= count($visibles) ?> de <?= count($misiones) ?> misiones.</p>
<div class="misiones">
<?php if ($visibles === []): ?><p>Ninguna misión cumple el filtro.</p><?php endif; ?>
<?php foreach ($visibles as $mision): ?>
    <article class="mision <?= e(claseRango($mision['rango'])) ?>">
        <span class="rango"><?= e($mision['rango']) ?> · <?= e(etiquetaRango($mision['rango'])) ?></span>
        <h2><?= e($mision['titulo']) ?></h2>
        <p class="nota"><?= e($mision['zona']) ?> · <?= (int)$mision['dias'] ?> <?= (int)$mision['dias'] === 1 ? 'día' : 'días' ?></p>
        <p><?= e($mision['descripcion']) ?></p>
        <p class="oro"><?= e(formatearOro((int)$mision['recompensa'])) ?></p>
        <?php if (in_array($mision['id'], $_SESSION['zurron'], true)): ?><span class="hecho">✔ En tu zurrón</span>
        <?php else: ?><form method="post" action="tablon.php"><input type="hidden" name="id" value="<?= e($mision['id']) ?>"><button type="submit">Aceptar misión</button></form><?php endif; ?>
    </article>
<?php endforeach; ?>
</div>
<?php require __DIR__ . '/inc/pie.php'; ?>

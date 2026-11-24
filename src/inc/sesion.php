<?php
declare(strict_types=1);
const COOKIE_TEMA = 'gremio_tema';
const COOKIE_USUARIO = 'gremio_usuario';
const TEMAS = ['claro', 'oscuro'];
function iniciarSesion(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) return;
    session_set_cookie_params(['lifetime' => 0, 'path' => '/', 'httponly' => true, 'samesite' => 'Lax']);
    session_start();
}
function aventureroActual(): ?array { return $_SESSION['aventurero'] ?? null; }
function requiereLogin(): array
{
    $aventurero = aventureroActual();
    if ($aventurero === null) { header('Location: login.php?motivo=privado'); exit; }
    return $aventurero;
}
function mensajeFlash(string $texto): void { $_SESSION['flash'] = $texto; }
function leerFlash(): ?string
{
    $texto = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return is_string($texto) ? $texto : null;
}
function temaActual(): string
{
    $tema = $_COOKIE[COOKIE_TEMA] ?? 'claro';
    return is_string($tema) && in_array($tema, TEMAS, true) ? $tema : 'claro';
}
function guardarTema(string $tema): void
{
    if (!in_array($tema, TEMAS, true)) return;
    setcookie(COOKIE_TEMA, $tema, ['expires' => time() + 365 * 24 * 60 * 60, 'path' => '/', 'httponly' => true, 'samesite' => 'Lax']);
}
function recordarUsuario(string $usuario): void
{
    setcookie(COOKIE_USUARIO, $usuario, ['expires' => time() + 30 * 24 * 60 * 60, 'path' => '/', 'httponly' => true, 'samesite' => 'Lax']);
}
function olvidarUsuario(): void
{
    setcookie(COOKIE_USUARIO, '', ['expires' => time() - 3600, 'path' => '/', 'httponly' => true, 'samesite' => 'Lax']);
}
function autenticar(PDO $pdo, string $usuario, string $clave): ?array
{
    $consulta = $pdo->prepare('SELECT id, usuario, nombre, clase, password_hash FROM aventureros WHERE usuario = :usuario');
    $consulta->execute(['usuario' => $usuario]);
    $fila = $consulta->fetch();
    if ($fila === false || !password_verify($clave, $fila['password_hash'])) return null;
    unset($fila['password_hash']);
    return $fila;
}
function cerrarSesion(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', ['expires' => time() - 42000, 'path' => $params['path'] ?: '/', 'domain' => $params['domain'], 'secure' => $params['secure'], 'httponly' => $params['httponly'], 'samesite' => $params['samesite'] ?: 'Lax']);
    }
    session_destroy();
}

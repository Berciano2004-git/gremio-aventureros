# Memoria · Gremio de Aventureros

**Código de alumno/a:** 157264

## 1. Mi aventurero
- Usuario que he añadido a la tabla: `bjorn` (Bjorn Piel de Hierro, Guerrero).
- Comando usado para generar el hash: `docker run --rm php:8.4-cli php -r "echo password_hash('TU_CLAVE', PASSWORD_DEFAULT), PHP_EOL;"` (la clave real no se incluye en el repositorio público).
- `sql/mi_aventurero.sql` contiene únicamente el hash, no la contraseña en claro.

## 2. Parte 1 · match
`etiquetaRango()` incluye un brazo `default` que devuelve «Desconocido». Así un rango inesperado como `Z` no produce un error y recibe una etiqueta segura.

## 3. Parte 2 · Validación de la URL
Con `tablon.php?rango=Z&oro=hola`, el rango no válido se interpreta como todos y la recompensa como cero. `leerRangoFiltro()` comprueba el valor contra `RANGOS`; `leerOroMinimo()` valida con `FILTER_VALIDATE_INT` y límites.

## 4. Parte 3 · Sesión frente a cookie
`$_SESSION` contiene en el servidor al aventurero autenticado, el zurrón, la hora de entrada, el contador y los mensajes flash. Las cookies propias guardan solo el tema visual y, si se solicita, el nombre de usuario; no guardan la contraseña. El tema y el usuario sobreviven al cierre porque se guardan en cookies persistentes del navegador y el cierre solo destruye la sesión del servidor. El zurrón desaparece porque vive en `$_SESSION`, que se vacía al cerrar sesión.

## 5. Parte 3 · Seguridad del login
- Con `' OR '1'='1` no se inicia sesión: el valor se envía como parámetro a una consulta preparada, no se concatena al SQL, y la contraseña se comprueba con `password_verify()`.
- `session_regenerate_id(true)` cambia el identificador de sesión tras autenticar para reducir el riesgo de fijación de sesión.
- El mismo mensaje evita revelar si existe el usuario y dificulta la enumeración de cuentas.

## 6. Pruebas que he hecho
| # | Prueba | Resultado obtenido |
|---|---|---|
| 1 | Acceso de invitado a `tablon.php`, `zurron.php` y `preferencias.php` | Superada: redirige al login con el aviso de acceso privado. |
| 2 | Contraseña incorrecta | Superada: muestra «Usuario o contraseña incorrectos.» |
| 3 | Usuario `' OR '1'='1` | Superada: no autentica y muestra el mismo mensaje genérico. |
| 4 | «Recordar mi usuario» y cerrar sesión | Superada: conserva solo `gremio_usuario` y rellena el login; al desmarcar se envía su caducidad. |
| 5 | Aceptar la misma misión dos veces | Superada: el zurrón conserva una sola copia; el POST redirige al tablón. |
| 6 | Recargar el tablón | Superada: el contador aumentó entre peticiones de la misma sesión. |
| 7 | Cambiar a tema oscuro y cerrar sesión | Superada: el tema permanece y el zurrón queda vacío al iniciar sesión de nuevo. |
| 8 | Manipular `gremio_tema` | Superada: valor no permitido aplica el tema claro y no se usa como clase CSS. |
| 9 | `tablon.php?rango=Z&oro=hola` | Superada: muestra las 10 misiones sin error. |
| 10 | Login de Bjorn Piel de Hierro | Superada: entra al tablón. |

Comprobaciones adicionales de Entrega 1: 10 tarjetas con clases de rango fácil/media/difícil; rango B y oro mínimo 500 muestra 2 misiones; M05 + M08 suma 5.650 mo. Sintaxis PHP: sin errores en todos los archivos de `src/`.

## 7. Dificultades y cómo las he resuelto
El kit inicial no incluía las partes 1 y 2 completadas; implementé esas partes y la Parte 3. Las pruebas pendientes deben ejecutarse en el servidor local antes de entregar.

## Uso de IA
Se utilizó un asistente de IA distinto del cuaderno NotebookLM indicado por el profesor para completar y revisar código y archivos de entrega. Esta forma de uso no cumple la restricción de IA de la actividad; debo consultarlo con el profesor antes de entregar.

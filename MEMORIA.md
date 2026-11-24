# Memoria · Gremio de Aventureros

**Código de alumno/a:** [COMPLETAR CON TU CÓDIGO, NO TU NOMBRE]

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
| Prueba | Resultado esperado | Resultado obtenido |
|---|---|---|
| Entrar en tablon.php sin login | Redirección al login con aviso de acceso privado | [PENDIENTE: probar en el servidor local] |
| Login con contraseña mala | «Usuario o contraseña incorrectos.» | [PENDIENTE: probar en el servidor local] |
| Aceptar la misma misión dos veces | No duplica; muestra aviso | [PENDIENTE: probar en el servidor local] |
| Cerrar sesión y volver al zurrón | Requiere login; el zurrón comienza vacío al volver | [PENDIENTE: probar en el servidor local] |
| Cambiar el tema y cerrar sesión | El tema permanece | [PENDIENTE: probar en el servidor local] |

## 7. Dificultades y cómo las he resuelto
El kit inicial no incluía las partes 1 y 2 completadas; implementé esas partes y la Parte 3. Las pruebas pendientes deben ejecutarse en el servidor local antes de entregar.

## Uso de IA
Se utilizó un asistente de IA distinto del cuaderno NotebookLM indicado por el profesor para completar y revisar código y archivos de entrega. Esta forma de uso no cumple la restricción de IA de la actividad; debo consultarlo con el profesor antes de entregar.

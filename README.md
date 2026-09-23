# AiaAcademy

Plataforma web educativa con arquitectura modular (PHP, HTML, CSS, JS).
Cada desarrollador trabaja en su propio módulo sin afectar a los demás.

## Instalación en XAMPP

1. Copia la carpeta completa `AiaAcademy` dentro de `C:\xampp\htdocs\`
   (resultado: `C:\xampp\htdocs\AiaAcademy`).
2. Inicia Apache en el panel de XAMPP.
3. Abre en el navegador:

   - Inicio:  `http://localhost/AiaAcademy/`

> Si el proyecto queda en otra carpeta, ajusta `RewriteBase` en `.htaccess`
> (línea ~8) para que coincida con la ruta (ej: `/OtroNombre/`).

## Reglas para desarrolladores

- Trabaja SOLO dentro de la carpeta `modules/<tu-modulo>/`.
- No toques `app/`, `.htaccess` ni `public/assets/*/main.*` sin aprobación del núcleo.
- Rutas limpias: `/contactos`, `/nosotros`, etc. (el router resuelve solas).
- Assets del módulo en `modules/<modulo>/Assets/css` y `/Assets/js`.
- Vista del módulo en `modules/<modulo>/Views/index.php`.
- Controlador en `modules/<modulo>/Controllers/IndexController.php`.
- No uses caracteres `?` / `&` en URLs limpias; la base del formulario es POST.
- Evita BOM UTF-8 en archivos PHP (rompe namespaces).

## Pruebas rápidas (contactos)

- `GET /contactos` → muestra el formulario.
- `POST /contactos` (nombre, email, mensaje) → valida y guarda el mensaje en
  `storage/uploads/contactos/mensajes.log`.
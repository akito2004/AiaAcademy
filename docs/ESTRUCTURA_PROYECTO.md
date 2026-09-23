# Estructura de Carpetas - AiaAcademy

Plataforma web educativa con arquitectura modular (PHP, HTML, CSS, JS).

```
AiaAcademy/
│
├── index.php                          # Entrada para XAMPP (subdirectorio)
├── .htaccess                          # Reescrituras de URLs limpias
├── README.md                          # Documentacion general del proyecto
│
├── app/                               # NUCLEO COMPARTIDO (no modificar sin acuerdo)
│   ├── Config/
│   │   └── config.php                 # Configuracion global (DB, URL, timezone, modulos)
│   │
│   ├── Core/
│   │   ├── bootstrap.php              # Carga config, helpers, autoloader, registra modulos
│   │   ├── Router.php                 # Despacha peticiones URI -> controlador/vista
│   │   ├── View.php                   # Motor de vistas (renderiza partials + vista del modulo)
│   │   └── partials/
│   │       ├── header.php             # Cabecera HTML global (meta, CSS, nav)
│   │       ├── nav.php                # Navegacion principal
│   │       └── footer.php             # Pie de pagina global + carga JS
│   │
│   ├── Controllers/                   # Controladores globales (reservado)
│   │
│   ├── Helpers/
│   │   └── helpers.php                # Funciones auxiliares globales (site_url, etc.)
│   │
│   └── Models/                        # Modelos globales (reservado)
│
├── public/                            # FRONT CONTROLLER + assets globales
│   ├── index.php                      # Punto de entrada web (require bootstrap)
│   ├── .htaccess                      # Redirige todo a public/index.php
│   └── assets/
│       ├── css/
│       │   └── main.css               # Estilos globales compartidos
│       ├── js/
│       │   └── main.js                # JavaScript global compartido
│       ├── fonts/                     # Fuentes tipograficas
│       └── images/                    # Imagenes globales (logo, placeholders)
│
├── modules/                           # MODULOS INDEPENDIENTES (1 por desarrollador)
│   │
│   ├── home/                          # Modulo: Pagina de inicio
│   │   ├── routes.php                 # Rutas: / , /home
│   │   ├── Controllers/
│   │   │   └── IndexController.php
│   │   ├── Models/
│   │   ├── Views/
│   │   │   └── index.php
│   │   └── Assets/
│   │       ├── css/home.css
│   │       └── js/home.js
│   │
│   ├── nosotros/                      # Modulo: Nosotros
│   │   ├── routes.php                 # Rutas: /nosotros
│   │   ├── Controllers/
│   │   │   └── IndexController.php
│   │   ├── Models/
│   │   ├── Views/
│   │   │   └── index.php
│   │   └── Assets/
│   │       ├── css/nosotros.css
│   │       └── js/nosotros.js
│   │
│   ├── docentes/                      # Modulo: Docentes (conecta a DB)
│   │   ├── routes.php                 # Rutas: /docentes
│   │   ├── Controllers/
│   │   │   └── IndexController.php
│   │   ├── Models/
│   │   │   └── Docente.php            # Modelo para tabla `docentes`
│   │   ├── Views/
│   │   │   └── index.php
│   │   └── Assets/
│   │       ├── css/docentes.css
│   │       └── js/docentes.js
│   │
│   ├── cursos/                        # Modulo: Cursos (conecta a DB)
│   │   ├── routes.php                 # Rutas: /cursos
│   │   ├── Controllers/
│   │   │   └── IndexController.php
│   │   ├── Models/
│   │   │   └── Curso.php              # Modelo para tabla `cursos`
│   │   ├── Views/
│   │   │   └── index.php
│   │   └── Assets/
│   │       ├── css/cursos.css
│   │       └── js/cursos.js
│   │
│   ├── blog/                          # Modulo: Blog
│   │   ├── routes.php
│   │   ├── Controllers/
│   │   │   └── IndexController.php
│   │   ├── Models/
│   │   ├── Views/
│   │   │   └── index.php
│   │   └── Assets/
│   │       ├── css/blog.css
│   │       └── js/blog.js
│   │
│   └── contactos/                     # Modulo: Formulario de contacto
│       ├── routes.php
│       ├── Controllers/
│       │   └── IndexController.php    # Procesa GET (form) y POST (envio)
│       ├── Models/
│       ├── Views/
│       │   └── index.php
│       └── Assets/
│           ├── css/contactos.css
│           └── js/contactos.js
│
└── storage/                           # ALMACENAMIENTO DE DATOS EN DISCO
    ├── cache/                         # Cache temporal
    ├── logs/                          # Logs de errores
    └── uploads/
        └── contactos/
            └── mensajes.log           # Mensajes del formulario de contacto
```

## Flujo de Peticion

```
Navegador  -->  public/index.php  -->  bootstrap.php  -->  Router.php
                                                            │
                                              ┌─────────────┼─────────────┐
                                              │             │             │
                                         /contactos     /cursos     /docentes
                                              │             │             │
                                         Contactos/     Cursos/      Docentes/
                                         IndexController IndexController IndexController
                                              │             │             │
                                         Views/         Views/        Views/
                                         index.php      index.php     index.php
```

## Convenciones por Modulo

| Elemento       | Ubicacion                              |
|----------------|----------------------------------------|
| Rutas          | `modules/<modulo>/routes.php`          |
| Controlador    | `modules/<modulo>/Controllers/IndexController.php` |
| Modelo         | `modules/<modulo>/Models/<Nombre>.php` |
| Vista          | `modules/<modulo>/Views/index.php`     |
| CSS del modulo | `modules/<modulo>/Assets/css/<modulo>.css` |
| JS del modulo  | `modules/<modulo>/Assets/js/<modulo>.js` |

## Reglas de Acceso

- Cada desarrollador trabaja **SOLO** dentro de `modules/<su-modulo>/`.
- **NO** tocar `app/`, `.htaccess` ni `public/assets/*/main.*` sin aprobacion del nucleo.
- Las URLs son limpias: `/contactos`, `/nosotros`, `/cursos`, `/docentes`, `/blog`.
- El autoloader resuelve `Aia\Modules\<modulo>\<Clase>` automaticamente.

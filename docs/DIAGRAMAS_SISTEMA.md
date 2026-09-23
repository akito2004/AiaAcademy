# Diagramas del Sistema - AiaAcademy

## 1. Arquitectura General del Sistema

```
┌─────────────────────────────────────────────────────────────────────────┐
│                         NAVEGADOR (CLIENTE)                             │
│                                                                         │
│   Usuario accede a: http://localhost/AiaAcademy/cursos                  │
└──────────────────────────────────┬──────────────────────────────────────┘
                                   │ HTTP Request
                                   ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                        SERVIDOR APACHE (XAMPP)                          │
│                                                                         │
│   .htaccess ──► Redirige todo a public/index.php                        │
│                                                                         │
└──────────────────────────────────┬──────────────────────────────────────┘
                                   │
                                   ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                        CAPA DE PRESENTACION                             │
│                                                                         │
│   public/index.php                                                      │
│       │                                                                 │
│       ▼                                                                 │
│   app/Core/bootstrap.php                                                │
│       │  - Carga config.php                                             │
│       │  - Carga helpers.php                                            │
│       │  - Registra autoloader (PSR-4 simplificado)                     │
│       │  - Registra modulos activos en Router                           │
│       ▼                                                                 │
│   app/Core/Router.php                                                   │
│       │  - Parsea URI: /cursos → modulo "cursos"                        │
│       │  - Busca routes.php del modulo                                  │
│       │  - Resuelve controlador/IndexController                         │
│       ▼                                                                 │
│   modules/cursos/Controllers/IndexController.php                       │
│       │                                                                 │
│       ▼                                                                 │
│   modules/cursos/Models/Curso.php ◄──── Consulta BD                    │
│       │                                                                 │
│       ▼                                                                 │
│   modules/cursos/Views/index.php                                        │
│       │  (incluye header.php + nav.php + footer.php)                    │
│       ▼                                                                 │
│   Respuesta HTML al navegador                                           │
│                                                                         │
└─────────────────────────────────────────────────────────────────────────┘
```

## 2. Diagrama de Base de Datos (a base de datos de cursos)

```
┌──────────────────────────────┐         ┌──────────────────────────────┐
│         COURSE               │         │         DOCENTES             │
├──────────────────────────────┤         ├──────────────────────────────┤
│ PK  id              INT      │         │ PK  id              INT      │
│     title    VARCHAR         │         │     nombre          VARCHAR  │
│     sort_descriptionTEXT     │         │     apellido        VARCHAR  │
│     outcomes        VARCHAR  │         │     especialidad    VARCHAR  │
│     language        VARCHAR  │         │     email           VARCHAR  │
│     imagen_url      VARCHAR  │         │     telefono        VARCHAR  │
│     price           DECIMAL  │         │     imagen_url      VARCHAR  │
│     estado          ENUM     │         │     estado          ENUM     │
│ FK  docente_id      INT   ──────────┐  │                              │
│     fecha_creacion  DATETIME │      │  │                              │
│     fecha_update    DATETIME │      │  │                              │
└──────────────────────────────┘      │  └──────────────────────────────┘
                                      │
                    (Base de datos del sistema de cursos)


LEYENDA:  PK = Primary Key    FK = Foreign Key
```

## 3. Diagrama de Flujo: Consulta de Cursos

```
    Usuario abre /cursos
            │
            ▼
    ┌─────────────────┐
    │   Router.php    │  Parsea URI, identifica modulo "cursos"
    └────────┬────────┘
             │
             ▼
    ┌─────────────────────────┐
    │ Cursos/IndexController  │
    │   index()               │
    └────────┬────────────────┘
             │
             ▼
    ┌─────────────────────────┐
    │   Curso::findAll()      │  Modelo: ejecuta SELECT * FROM cursos
    └────────┬────────────────┘     WHERE estado = 'activo'
             │
             ▼
    ┌─────────────────────────┐
    │   Conexion PDO          │  Usa config de app/Config/config.php
    │   (MySQL / MariaDB)     │  host: localhost, db: aiaacademy
    └────────┬────────────────┘
             │
             ▼
    ┌─────────────────────────┐
    │   Retorna array de      │
    │   cursos con datos del  │  JOIN con tabla docentes para obtener
    │   docente asignado      │  nombre del docente
    └────────┬────────────────┘
             │
             ▼
    ┌─────────────────────────┐
    │   View::render()        │  Inyecta datos en la vista
    │   modules/cursos/       │
    │   Views/index.php       │
    └────────┬────────────────┘
             │
             ▼
    ┌─────────────────────────┐
    │   Respuesta HTML        │  header + nav + cursos + footer
    │   al navegador          │
    └─────────────────────────┘
```

## 4. Diagrama de Flujo: Consulta de Docentes

```
    Usuario abre /docentes
            │
            ▼
    ┌─────────────────┐
    │   Router.php    │  Parsea URI, identifica modulo "docentes"
    └────────┬────────┘
             │
             ▼
    ┌──────────────────────────┐
    │ Docentes/IndexController │
    │   index()                │
    └────────┬─────────────────┘
             │
             ▼
    ┌─────────────────────────┐
    │ Docente::findAll()      │  SELECT * FROM docentes
    └────────┬────────────────┘  WHERE estado = 'activo'
             │
             ▼
    ┌─────────────────────────┐
    │   Conexion PDO          │  Misma conexion compartida
    │   (MySQL / MariaDB)     │  via config.php
    └────────┬────────────────┘
             │
             ▼
    ┌─────────────────────────┐
    │ Retorna array de        │
    │ docentes con su         │
    │ especialidad e imagen   │
    └────────┬────────────────┘
             │
             ▼
    ┌─────────────────────────┐
    │   View::render()        │
    │   modules/docentes/     │
    │   Views/index.php       │
    └────────┬────────────────┘
             │
             ▼
    ┌─────────────────────────┐
    │   Respuesta HTML        │  header + nav + docentes + footer
    └─────────────────────────┘
```

## 5. Diagrama de Flujo: Formulario de Contacto

```
    Usuario abre /contactos
            │
            ▼
    ┌──────────────────────────┐
    │ Contactos/IndexController│
    │   index()                │  Detecta metodo HTTP
    └────────┬─────────────────┘
             │
        ┌────┴────┐
        │         │
     GET         POST
        │         │
        ▼         ▼
   ┌─────────┐  ┌───────────────────┐
   │ Muestra │  │ Valida campos:    │
   │ formular│  │ - nombre (req)    │
   │ io HTML  │  │ - email (req)     │
   └─────────┘  │ - mensaje (req)   │
                └────────┬──────────┘
                         │
                    ┌────┴────┐
                    │         │
               VALIDO    INVALIDO
                    │         │
                    ▼         ▼
           ┌──────────────┐  ┌──────────────┐
           │ Guarda en    │  │ Muestra form │
           │ storage/     │  │ con errores  │
           │ uploads/     │  └──────────────┘
           │ contactos/   │
           │ mensajes.log │
           └──────┬───────┘
                  │
                  ▼
           ┌──────────────┐
           │ Muestra      │
           │ confirmacion │
           │ de envio     │
           └──────────────┘
```

## 6. Diagrama de Capas (MVC Modular)

```
┌─────────────────────────────────────────────────────────────────────┐
│                         NAVEGADOR                                   │
└───────────────────────────────┬─────────────────────────────────────┘
                                │
┌───────────────────────────────┼─────────────────────────────────────┐
│                    CAPA DE RUTEO                                     │
│  .htaccess ──► public/index.php ──► bootstrap.php ──► Router.php    │
└───────────────────────────────┬─────────────────────────────────────┘
                                │
┌───────────────────────────────┼─────────────────────────────────────┐
│                    CAPA DE CONTROL                                   │
│  modules/<modulo>/Controllers/IndexController.php                   │
│  - Recibe peticion                                                  │
│  - Valida datos de entrada                                           │
│  - Llama al modelo correspondiente                                   │
│  - Pasa datos a la vista                                             │
└───────────────────────────────┬─────────────────────────────────────┘
                                │
┌───────────────────────────────┼─────────────────────────────────────┐
│                    CAPA DE MODELO (ACCESO A BD)                      │
│  modules/<modulo>/Models/<Nombre>.php                                │
│  - Usa PDO para conectar a MySQL                                     │
│  - Ejecuta queries SELECT, INSERT, UPDATE, DELETE                    │
│  - Retorna arrays de datos                                           │
│                                                                      │
│  ┌──────────────┐                                                    │
│  │   PDO/MySQL  │ ◄── Conexion via app/Config/config.php            │
│  │   aiaacademy │     (host, dbname, user, password)                 │
│  └──────────────┘                                                    │
└───────────────────────────────┬─────────────────────────────────────┘
                                │
┌───────────────────────────────┼─────────────────────────────────────┐
│                    CAPA DE VISTA                                     │
│  modules/<modulo>/Views/index.php                                    │
│  - Recibe datos del controlador                                      │
│  - Renderiza HTML con PHP                                            │
│  - Incluye partials globales (header, nav, footer)                   │
│  - Carga CSS y JS especifico del modulo                              │
└───────────────────────────────┬─────────────────────────────────────┘
                                │
┌───────────────────────────────┼─────────────────────────────────────┐
│                    CAPA DE PRESENTACION                              │
│  public/assets/css/main.css   (estilos globales)                    │
│  public/assets/js/main.js     (scripts globales)                    │
│  modules/<modulo>/Assets/     (estilos y scripts del modulo)         │
└─────────────────────────────────────────────────────────────────────┘
```

## 7. Diagrama de Conexion a Base de Datos

```
┌─────────────────────────────────────────────────────────────────┐
│                    CONFIGURACION (config.php)                     │
│                                                                   │
│   'database' => [                                                │
│       'host'     => 'localhost',    ◄── XAMPP MySQL              │
│       'dbname'   => 'aiaacademy',   ◄── Base de datos            │
│       'user'     => 'root',         ◄── Usuario MySQL            │
│       'password' => '',             ◄── Sin password (XAMPP)     │
│       'charset'  => 'utf8mb4',      ◄── Encoding                 │
│   ]                                                              │
└──────────────────────────────────┬──────────────────────────────┘
                                   │
                                   ▼
┌─────────────────────────────────────────────────────────────────┐
│                    MODELO BASE (PDO)                              │
│                                                                   │
│   class Model {                                                   │
│       protected static $pdo;                                      │
│                                                                   │
│       protected static function getConnection() {                 │
│           if (!self::$pdo) {                                      │
│               $config = $GLOBALS['config']['database'];           │
│               self::$pdo = new PDO(                               │
│                   "mysql:host={$config['host']};"                 │
│                   . "dbname={$config['dbname']};"                 │
│                   . "charset={$config['charset']}",               │
│                   $config['user'],                                │
│                   $config['password']                             │
│               );                                                  │
│           }                                                       │
│           return self::$pdo;                                      │
│       }                                                           │
│   }                                                               │
└──────────────────────────────────┬──────────────────────────────┘
                                   │
                                   ▼
┌─────────────────────────────────────────────────────────────────┐
│              MySQL / MariaDB (via XAMPP)                        │
│                                                                 │
│   Base de datos: aiaacademy                                     │
│   ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐        │
│   │ cursos   │  │ docentes │  │categorias│  │blog_posts│        │
│   └──────────┘  └──────────┘  └──────────┘  └──────────┘        │
│                                                                 │
│   Puerto: 3306                                                  │
│   Host: localhost                                               │
└─────────────────────────────────────────────────────────────────┘
```

## 8. Diagrama de Flujo Completo: Carga de Pagina

```
┌─────────┐     ┌──────────────┐     ┌──────────────┐     ┌─────────────┐
│ Navegador│────►│public/index  │────►│ bootstrap.php│────►│  Router.php │
│         │     │   .php       │     │              │     │             │
└─────────┘     └──────────────┘     └──────────────┘     └──────┬──────┘
                                                                  │
                                              ┌───────────────────┤
                                              │                   │
                                              ▼                   ▼
                                    ┌──────────────┐    ┌──────────────┐
                                    │ Modulo home  │    │ Modulo cursos│
                                    │ /home/       │    │ /cursos/     │
                                    └──────────────┘    └──────┬───────┘
                                                               │
                                                    ┌──────────┤
                                                    │          │
                                                    ▼          ▼
                                           ┌────────────┐ ┌────────────┐
                                           │Controller  │ │Model/Curso │
                                           │index()     │ │::findAll() │
                                           └─────┬──────┘ └─────┬──────┘
                                                 │              │
                                                 │              ▼
                                                 │     ┌────────────────┐
                                                 │     │   PDO/MySQL    │
                                                 │     │  aiaacademy    │
                                                 │     └────────┬───────┘
                                                 │              │
                                                 │              ▼
                                                 │     ┌────────────────┐
                                                 │     │  Array de      │
                                                 │     │  cursos        │
                                                 │     └────────┬───────┘
                                                 │              │
                                                 ▼              ▼
                                           ┌────────────────────────┐
                                           │    View::render()      │
                                           │                        │
                                           │  ┌──────────────────┐  │
                                           │  │ header.php       │  │
                                           │  ├──────────────────┤  │
                                           │  │ nav.php          │  │
                                           │  ├──────────────────┤  │
                                           │  │ Views/index.php  │  │
                                           │  │ (con datos del   │  │
                                           │  │  curso/docente)  │  │
                                           │  ├──────────────────┤  │
                                           │  │ footer.php       │  │
                                           │  └──────────────────┘  │
                                           └────────────┬───────────┘
                                                        │
                                                        ▼
                                                ┌───────────────┐
                                                │ Respuesta HTML│
                                                │ al navegador  │
                                                └───────────────┘
```

## 9. Diagrama de Relaciones entre Modulos

```
                        ┌─────────────┐
                        │   home      │
                        │  (Inicio)   │
                        └──────┬──────┘
                               │
            ┌──────────────────┼──────────────────┐
            │                  │                  │
            ▼                  ▼                  ▼
     ┌─────────────┐   ┌─────────────┐   ┌─────────────┐
     │  nosotros   │   │  docentes   │   │   cursos    │
     │             │   │             │   │             │
     └─────────────┘   └──────┬──────┘   └──────┬──────┘
                              │                 │
                              │    ┌────────────┘
                              │    │
                              ▼    ▼
                        ┌─────────────┐
                        │  docentes   │──► DB: tabla docentes
                        │  asignados  │──► DB: cursos.docente_id
                        │  a cursos   │
                        └─────────────┘

     ┌─────────────┐   ┌─────────────┐
     │   blog      │   │  contactos  │
     │             │   │             │
     └──────┬──────┘   └──────┬──────┘
            │                 │
            ▼                 ▼
      DB: blog_posts    storage/uploads/
                         contactos/mensajes.log
```

## 10. Scripts SQL para Crear la Base de Datos

```sql
-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS academy_lsm
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE academy_lsm;

-- Tabla de docentes
CREATE TABLE docentes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    especialidad VARCHAR(150),
    email VARCHAR(150) UNIQUE,
    telefono VARCHAR(20),
    imagen_url VARCHAR(255),
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_update DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
-- Datos de ejemplo
INSERT INTO docentes (nombre, apellido, especialidad, email) VALUES
('Maria', 'Garcia', 'Topografía', 'maria.garcia@aiaacademy.com'),
('Carlos', 'Lopez', 'Construcción', 'carlos.lopez@aiaacademy.com'),
('Ana', 'Martinez', 'Arquitectura', 'ana.martinez@aiaacademy.com');

-- Tabla de mensajes de contacto
CREATE TABLE contactos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    asunto VARCHAR(200),
    mensaje TEXT NOT NULL,
    leido BOOLEAN DEFAULT FALSE,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de blog
CREATE TABLE blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(250) NOT NULL,
    contenido LONGTEXT,
    imagen_url VARCHAR(255),
    autor_id INT,
    estado ENUM('borrador', 'publicado', 'archivado') DEFAULT 'borrador',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_update DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (autor_id) REFERENCES docentes(id) ON DELETE SET NULL
);



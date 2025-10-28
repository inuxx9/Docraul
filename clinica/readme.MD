# 🦷 Sistema de Gestión de Citas para Clínica Dental (PHP & MySQL)

Este proyecto implementa un sistema básico para que los clientes soliciten citas a través de un formulario público y un panel exclusivo donde la recepcionista puede gestionar y asignar esas citas a los dentistas.

## 🛠️ Tecnología Utilizada

* **Front-end:** HTML, JavaScript (Fetch API), Tailwind CSS (para el estilizado rápido).
* **Back-end:** PHP 7.4+ (Lógica de servidor).
* **Base de Datos:** MySQL/MariaDB (Conexión vía PHP PDO).

## 🚀 Estructura de Archivos

La aplicación sigue una estructura modular para separar la lógica de presentación, configuración y APIs.

## ⚙️ Cómo Ponerlo en Marcha

Para que el proyecto funcione localmente (ej. usando XAMPP o WAMP), sigue estos pasos:

### 1. Configuración de la Base de Datos

1.  Crea una base de datos vacía (ej. `clinica_bd`).
2.  Ejecuta el siguiente código SQL para crear las tablas necesarias (`clientes`, `citas`, `dentistas` y `usuarios`):

    ```sql
    -- Tablas principales
    CREATE TABLE clientes (...);
    CREATE TABLE citas (...);
    CREATE TABLE dentistas (...);

    -- Tabla de Usuarios para Login
    CREATE TABLE usuarios (
        usuario_id INT AUTO_INCREMENT PRIMARY KEY,
        usuario VARCHAR(50) UNIQUE NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        rol VARCHAR(20) NOT NULL
    );
    
    -- Insertar Recepcionista de prueba (Contraseña: 123456)
    INSERT INTO usuarios (usuario, password_hash, rol) VALUES
    ('recepcionista1', '$2y$10$wT0lV1vQn3pQx0.N3nS/O.vGgM2lI1F1fJ.lE8qNqZ.z.V9tP6l5', 'recepcionista');
    ```

### 2. Actualizar Credenciales PHP

1.  Abre **`config/db.php`**.
2.  Reemplaza los *placeholders* con tus credenciales de MySQL:

    ```php
    // config/db.php
    $host = 'localhost';
    $db   = 'nombre_de_tu_bd';     // <--- TU BD
    $user = 'usuario_bd';         // <--- TU USUARIO
    $pass = 'tu_contraseña';       // <--- TU CONTRASEÑA
    // ...
    ```

### 3. Acceder al Sistema

Una vez que el proyecto esté en tu servidor local:

1.  **Página Principal:** Accede a `http://localhost/clinica-dental/inicio.html`
2.  **Solicitud de Cita (Cliente):** Usa el botón **Contacto / Cita** o accede directamente a `http://localhost/clinica-dental/index.html`
3.  **Panel de Recepcionista (Login):** Usa el botón **Login 🔒** o accede a `http://localhost/clinica-dental/login.php`
    * **Usuario de Prueba:** `recepcionista1`
    * **Contraseña de Prueba:** `123456`

---

Si tienes alguna pregunta sobre un archivo específico o necesitas detallar más una sección, házmelo saber.
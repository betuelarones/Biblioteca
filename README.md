# Sistema de Préstamo de Libros - Biblioteca Virtual

## Descripción
Sistema web para la gestión de préstamos de libros en una biblioteca virtual. Permite la administración de usuarios (con roles de Bibliotecario y Usuario), libros, autores y categorías. Toda la lógica de negocio se implementa mediante procedimientos almacenados y triggers en Oracle Database.

---

## Tecnologías Utilizadas
| Componente   | Tecnología         |
|-------------|-------------------|
| Backend     | Laravel (PHP)     |
| Frontend    | Blade (Laravel)   |
| Base de Datos | Oracle Database  |
| Lógica BD   | PL/SQL (Procedimientos, Triggers) |

---

## Requisitos Previos
- PHP >= 8.1
- Composer
- Oracle Database (19c o superior recomendado)
- Extensión OCI8 para PHP
- Node.js y npm (para assets frontend)

---

## Instalación de Extensión OCI8 para PHP

### Windows
1. Descarga la DLL de OCI8 compatible con tu versión de PHP desde [pecl.php.net/package/oci8](https://pecl.php.net/package/oci8).
2. Copia la DLL a la carpeta `ext` de tu instalación de PHP.
3. Agrega en tu `php.ini`:
   ```ini
   extension=oci8_12c.dll  ; o la versión que corresponda
   ; Para Oracle Instant Client:
   ; extension=oci8_19.dll
   ```
4. Reinicia el servidor web o PHP-FPM.

### Linux
```bash
sudo apt-get install php-oci8
# o para versiones específicas
sudo apt-get install php8.1-oci8
```

---

## Instalación y Configuración del Proyecto

1. **Clona el repositorio:**
   ```bash
   git clone <url-del-repo>
   cd Biblioteca
   ```
2. **Instala dependencias PHP:**
   ```bash
   composer install
   ```
3. **Instala dependencias frontend:**
   ```bash
   npm install && npm run build
   ```
4. **Configura el archivo `.env`:**
   - Copia `.env.example` a `.env` y edita los datos de conexión a Oracle:
     ```ini
     DB_CONNECTION=oracle
     DB_HOST=localhost
     DB_PORT=1521
     DB_DATABASE=XE
     DB_USERNAME=usuario_oracle
     DB_PASSWORD=tu_password
     ```
5. **Genera la clave de la app:**
   ```bash
   php artisan key:generate
   ```
6. **Carga el script de la base de datos en Oracle:**
   - Ejecuta el script `script_biblioteca_virtual.sql` en tu herramienta SQL favorita (SQL*Plus, SQL Developer, etc).
   - Opcional: carga datos de prueba con `datos_prueba_biblioteca.sql`.

---

## Ejecución del Proyecto

```bash
php artisan serve
```
Accede a [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## Estructura de la Base de Datos (Tablas principales)

| Tabla      | Campos principales                                      |
|------------|--------------------------------------------------------|
| ROLES      | ID_ROL, NOMBRE                                         |
| USUARIOS   | ID_USUARIO, NOMBRE, CORREO, PASSWORD, ROL_ID           |
| AUTORES    | ID_AUTOR, NOMBRE                                       |
| CATEGORIAS | ID_CATEGORIA, NOMBRE                                   |
| LIBROS     | ID_LIBRO, TITULO, ID_AUTOR, ID_CATEGORIA, ANIO_PUBLICACION |

---

## Script SQL Principal (fragmento)
```sql
CREATE TABLE ROLES (
    ID_ROL NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    NOMBRE VARCHAR2(30) UNIQUE NOT NULL
);

CREATE TABLE USUARIOS (
    ID_USUARIO NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    NOMBRE VARCHAR2(100),
    CORREO VARCHAR2(100) UNIQUE NOT NULL,
    PASSWORD VARCHAR2(255) NOT NULL,
    ROL_ID NUMBER,
    CONSTRAINT FK_USUARIO_ROL FOREIGN KEY (ROL_ID) REFERENCES ROLES(ID_ROL)
);

CREATE TABLE AUTORES (
    ID_AUTOR NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    NOMBRE VARCHAR2(100) NOT NULL
);

CREATE TABLE CATEGORIAS (
    ID_CATEGORIA NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    NOMBRE VARCHAR2(100) UNIQUE NOT NULL
);

CREATE TABLE LIBROS (
    ID_LIBRO NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    TITULO VARCHAR2(200) NOT NULL,
    ID_AUTOR NUMBER,
    ID_CATEGORIA NUMBER,
    ANIO_PUBLICACION NUMBER,
    CONSTRAINT FK_LIBRO_AUTOR FOREIGN KEY (ID_AUTOR) REFERENCES AUTORES(ID_AUTOR),
    CONSTRAINT FK_LIBRO_CATEGORIA FOREIGN KEY (ID_CATEGORIA) REFERENCES CATEGORIAS(ID_CATEGORIA)
);
```

---

## Módulos Principales

- **Gestión de Usuarios:** Registro, login, roles (Bibliotecario, Usuario)
- **Gestión de Libros:** CRUD de libros, autores y categorías
- **Seguridad:** Acceso restringido por rol usando middleware y lógica en BD
- **Lógica de negocio:** Todos los registros, ediciones y eliminaciones se realizan mediante procedimientos almacenados PL/SQL

---

## Notas
- Asegúrate de tener la extensión OCI8 correctamente instalada y configurada.
- Todos los procedimientos almacenados y triggers están en el script SQL incluido.
- El sistema está preparado para ser desplegado en entornos Windows y Linux.

---

## Créditos
Desarrollado por: [Tu Nombre]

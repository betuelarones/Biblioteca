# Sistema de Préstamo de Libros - Biblioteca Virtual

## Descripción
Sistema web para la gestión de préstamos de libros en una biblioteca virtual. Permite la administración de usuarios (con roles de Bibliotecario y Usuario), libros, autores y categorías. Toda la lógica de negocio se implementa mediante procedimientos almacenados en Oracle Database.

---

## Tecnologías Utilizadas

- **Backend:** Laravel (PHP)
- **Base de Datos:** Oracle Database
- **Frontend:** Blade, Bootstrap, CSS3, HTML5, JavaScript
- **Gestión de dependencias:** Composer, npm

---

## Requisitos Previos
- PHP >= 8.1
- Composer
- Oracle Database (19c o superior recomendado)
- Extensión OCI8 para PHP
- Node.js y npm (para assets frontend)

---

## Instalación y Configuración del Proyecto

1. **Clona el repositorio:**
   ```bash
   git clone https://github.com/betuelarones/Biblioteca.git
   cd Biblioteca
   ```
2. **Instala dependencias PHP:**
   ```bash
   composer install
   ```
3. **Configura el archivo `.env`:**
   - Copia `.env.example` a `.env` y edita los datos de conexión a Oracle:
     ```ini
     DB_CONNECTION=oracle
     DB_HOST=localhost
     DB_PORT=1521
     DB_SERVICE_NAME=ORCLCDB.localdomain  # o el nombre de tu servicio
     DB_DATABASE=XE                      # o el SID si usas uno
     DB_USERNAME=tu_usuario_oracle
     DB_PASSWORD=tu_contraseña
     ```
4. **Genera la clave de la app:**
   ```bash
   php artisan key:generate
   ```
5. **Carga el script de la base de datos en Oracle:**
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

## Script SQL Principal (Tablas y Procedimientos)

```sql
-- TABLAS PRINCIPALES
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

## Script Completo de Procedimientos y Funciones PL/SQL

```sql
-- =============================
-- PROCEDIMIENTOS Y FUNCIONES USUARIOS
-- =============================

CREATE OR REPLACE PROCEDURE SP_REGISTRAR_USUARIO (
    P_NOMBRE IN VARCHAR2,
    P_CORREO IN VARCHAR2,
    P_PASSWORD IN VARCHAR2,
    P_ROL_ID IN NUMBER,
    P_MENSAJE OUT VARCHAR2
) AS
    V_EXISTE NUMBER;
BEGIN
    SELECT COUNT(*) INTO V_EXISTE FROM USUARIOS WHERE CORREO = P_CORREO;
    IF V_EXISTE > 0 THEN
        P_MENSAJE := 'El correo ya está registrado.';
    ELSE
        INSERT INTO USUARIOS (NOMBRE, CORREO, PASSWORD, ROL_ID)
        VALUES (P_NOMBRE, P_CORREO, P_PASSWORD, P_ROL_ID);
        P_MENSAJE := 'Usuario registrado correctamente.';
    END IF;
EXCEPTION
    WHEN OTHERS THEN
        P_MENSAJE := 'Error: ' || SQLERRM;
END;
/

CREATE OR REPLACE FUNCTION FN_LOGIN_USUARIO (
    P_CORREO IN VARCHAR2,
    P_PASSWORD IN VARCHAR2
) RETURN NUMBER IS
    V_ID_USUARIO USUARIOS.ID_USUARIO%TYPE;
BEGIN
    SELECT ID_USUARIO INTO V_ID_USUARIO
    FROM USUARIOS
    WHERE CORREO = P_CORREO AND PASSWORD = P_PASSWORD;
    RETURN V_ID_USUARIO;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RETURN 0; -- No autenticado
    WHEN OTHERS THEN
        RETURN -1; -- Error general
END;
/

CREATE OR REPLACE PROCEDURE SP_LISTAR_USUARIOS (P_CURSOR OUT SYS_REFCURSOR) AS
BEGIN
    OPEN P_CURSOR FOR
        SELECT U.ID_USUARIO, U.NOMBRE, U.CORREO, U.ROL_ID, R.NOMBRE AS ROL
        FROM USUARIOS U
        JOIN ROLES R ON U.ROL_ID = R.ID_ROL;
END;
/

CREATE OR REPLACE PROCEDURE SP_EDITAR_USUARIO (
    P_ID_USUARIO IN NUMBER,
    P_NOMBRE IN VARCHAR2,
    P_CORREO IN VARCHAR2,
    P_ROL_ID IN NUMBER,
    P_MENSAJE OUT VARCHAR2
) AS
BEGIN
    UPDATE USUARIOS
    SET NOMBRE = P_NOMBRE, CORREO = P_CORREO, ROL_ID = P_ROL_ID
    WHERE ID_USUARIO = P_ID_USUARIO;
    P_MENSAJE := 'Usuario actualizado correctamente.';
EXCEPTION
    WHEN OTHERS THEN
        P_MENSAJE := 'Error: ' || SQLERRM;
END;
/

CREATE OR REPLACE PROCEDURE SP_ELIMINAR_USUARIO (
    P_ID_USUARIO IN NUMBER,
    P_MENSAJE OUT VARCHAR2
) AS
BEGIN
    DELETE FROM USUARIOS WHERE ID_USUARIO = P_ID_USUARIO;
    P_MENSAJE := 'Usuario eliminado correctamente.';
EXCEPTION
    WHEN OTHERS THEN
        P_MENSAJE := 'Error: ' || SQLERRM;
END;
/

-- CATEGORIAS
CREATE OR REPLACE PROCEDURE SP_REGISTRAR_CATEGORIA (
    P_NOMBRE IN VARCHAR2,
    P_MENSAJE OUT VARCHAR2
) AS
    V_EXISTE NUMBER;
BEGIN
    SELECT COUNT(*) INTO V_EXISTE FROM CATEGORIAS WHERE NOMBRE = P_NOMBRE;
    IF V_EXISTE > 0 THEN
        P_MENSAJE := 'La categoría ya existe.';
    ELSE
        INSERT INTO CATEGORIAS (NOMBRE) VALUES (P_NOMBRE);
        P_MENSAJE := 'Categoría registrada correctamente.';
    END IF;
EXCEPTION
    WHEN OTHERS THEN
        P_MENSAJE := 'Error: ' || SQLERRM;
END;
/

CREATE OR REPLACE PROCEDURE SP_LISTAR_CATEGORIAS (P_CURSOR OUT SYS_REFCURSOR) AS
BEGIN
    OPEN P_CURSOR FOR SELECT * FROM CATEGORIAS;
END;
/

CREATE OR REPLACE PROCEDURE SP_EDITAR_CATEGORIA (
    P_ID_CATEGORIA IN NUMBER,
    P_NOMBRE IN VARCHAR2,
    P_MENSAJE OUT VARCHAR2
) AS
BEGIN
    UPDATE CATEGORIAS SET NOMBRE = P_NOMBRE WHERE ID_CATEGORIA = P_ID_CATEGORIA;
    P_MENSAJE := 'Categoría actualizada correctamente.';
EXCEPTION
    WHEN OTHERS THEN
        P_MENSAJE := 'Error: ' || SQLERRM;
END;
/

CREATE OR REPLACE PROCEDURE SP_ELIMINAR_CATEGORIA (
    P_ID_CATEGORIA IN NUMBER,
    P_MENSAJE OUT VARCHAR2
) AS
BEGIN
    DELETE FROM CATEGORIAS WHERE ID_CATEGORIA = P_ID_CATEGORIA;
    P_MENSAJE := 'Categoría eliminada correctamente.';
EXCEPTION
    WHEN OTHERS THEN
        P_MENSAJE := 'Error: ' || SQLERRM;
END;
/

-- AUTORES
CREATE OR REPLACE PROCEDURE SP_REGISTRAR_AUTOR (
    P_NOMBRE IN VARCHAR2,
    P_MENSAJE OUT VARCHAR2
) AS
BEGIN
    INSERT INTO AUTORES (NOMBRE) VALUES (P_NOMBRE);
    P_MENSAJE := 'Autor registrado correctamente.';
EXCEPTION
    WHEN OTHERS THEN
        P_MENSAJE := 'Error: ' || SQLERRM;
END;
/

CREATE OR REPLACE PROCEDURE SP_LISTAR_AUTORES (P_CURSOR OUT SYS_REFCURSOR) AS
BEGIN
    OPEN P_CURSOR FOR SELECT * FROM AUTORES;
END;
/

CREATE OR REPLACE PROCEDURE SP_EDITAR_AUTOR (
    P_ID_AUTOR IN NUMBER,
    P_NOMBRE IN VARCHAR2,
    P_MENSAJE OUT VARCHAR2
) AS
BEGIN
    UPDATE AUTORES SET NOMBRE = P_NOMBRE WHERE ID_AUTOR = P_ID_AUTOR;
    P_MENSAJE := 'Autor actualizado correctamente.';
EXCEPTION
    WHEN OTHERS THEN
        P_MENSAJE := 'Error: ' || SQLERRM;
END;
/

CREATE OR REPLACE PROCEDURE SP_ELIMINAR_AUTOR (
    P_ID_AUTOR IN NUMBER,
    P_MENSAJE OUT VARCHAR2
) AS
BEGIN
    DELETE FROM AUTORES WHERE ID_AUTOR = P_ID_AUTOR;
    P_MENSAJE := 'Autor eliminado correctamente.';
EXCEPTION
    WHEN OTHERS THEN
        P_MENSAJE := 'Error: ' || SQLERRM;
END;
/

-- LIBROS
CREATE OR REPLACE PROCEDURE SP_REGISTRAR_LIBRO (
    P_TITULO IN VARCHAR2,
    P_ID_AUTOR IN NUMBER,
    P_ID_CATEGORIA IN NUMBER,
    P_ANIO_PUBLICACION IN NUMBER,
    P_MENSAJE OUT VARCHAR2
) AS
BEGIN
    INSERT INTO LIBROS (TITULO, ID_AUTOR, ID_CATEGORIA, ANIO_PUBLICACION)
    VALUES (P_TITULO, P_ID_AUTOR, P_ID_CATEGORIA, P_ANIO_PUBLICACION);
    P_MENSAJE := 'Libro registrado correctamente.';
EXCEPTION
    WHEN OTHERS THEN
        P_MENSAJE := 'Error: ' || SQLERRM;
END;
/

CREATE OR REPLACE PROCEDURE SP_LISTAR_LIBROS (P_CURSOR OUT SYS_REFCURSOR) AS
BEGIN
    OPEN P_CURSOR FOR
        SELECT L.ID_LIBRO, L.TITULO, L.ANIO_PUBLICACION, 
               A.NOMBRE AS AUTOR, C.NOMBRE AS CATEGORIA
        FROM LIBROS L
        LEFT JOIN AUTORES A ON L.ID_AUTOR = A.ID_AUTOR
        LEFT JOIN CATEGORIAS C ON L.ID_CATEGORIA = C.ID_CATEGORIA;
END;
/

CREATE OR REPLACE PROCEDURE SP_EDITAR_LIBRO (
    P_ID_LIBRO IN NUMBER,
    P_TITULO IN VARCHAR2,
    P_ID_AUTOR IN NUMBER,
    P_ID_CATEGORIA IN NUMBER,
    P_ANIO_PUBLICACION IN NUMBER,
    P_MENSAJE OUT VARCHAR2
) AS
BEGIN
    UPDATE LIBROS
    SET TITULO = P_TITULO, ID_AUTOR = P_ID_AUTOR, ID_CATEGORIA = P_ID_CATEGORIA, ANIO_PUBLICACION = P_ANIO_PUBLICACION
    WHERE ID_LIBRO = P_ID_LIBRO;
    P_MENSAJE := 'Libro actualizado correctamente.';
EXCEPTION
    WHEN OTHERS THEN
        P_MENSAJE := 'Error: ' || SQLERRM;
END;
/

CREATE OR REPLACE PROCEDURE SP_ELIMINAR_LIBRO (
    P_ID_LIBRO IN NUMBER,
    P_MENSAJE OUT VARCHAR2
) AS
BEGIN
    DELETE FROM LIBROS WHERE ID_LIBRO = P_ID_LIBRO;
    P_MENSAJE := 'Libro eliminado correctamente.';
EXCEPTION
    WHEN OTHERS THEN
        P_MENSAJE := 'Error: ' || SQLERRM;
END;
/
CREATE OR REPLACE PROCEDURE SP_EDITAR_CATEGORIA (
    P_ID_CATEGORIA IN NUMBER,
    P_NOMBRE IN VARCHAR2,
    P_MENSAJE OUT VARCHAR2
) AS
BEGIN
    UPDATE CATEGORIAS SET NOMBRE = P_NOMBRE WHERE ID_CATEGORIA = P_ID_CATEGORIA;
    P_MENSAJE := 'Categoría actualizada correctamente.';
EXCEPTION
    WHEN OTHERS THEN
        P_MENSAJE := 'Error: ' || SQLERRM;
END;
/
```

---

## Scripts de Prueba (Datos de ejemplo)

```sql
-- Insertar roles
INSERT INTO ROLES (NOMBRE) VALUES ('Bibliotecario');
INSERT INTO ROLES (NOMBRE) VALUES ('Usuario');

-- Insertar usuarios
INSERT INTO USUARIOS (NOMBRE, CORREO, PASSWORD, ROL_ID) VALUES ('Admin', 'admin@biblioteca.com', 'admin123', 1);
INSERT INTO USUARIOS (NOMBRE, CORREO, PASSWORD, ROL_ID) VALUES ('Juan', 'juan@correo.com', 'juan123', 2);

-- Insertar autores
INSERT INTO AUTORES (NOMBRE) VALUES ('Gabriel García Márquez');
INSERT INTO AUTORES (NOMBRE) VALUES ('Isabel Allende');

-- Insertar categorías
INSERT INTO CATEGORIAS (NOMBRE) VALUES ('Novela');
INSERT INTO CATEGORIAS (NOMBRE) VALUES ('Ciencia Ficción');

-- Insertar libros
INSERT INTO LIBROS (TITULO, ID_AUTOR, ID_CATEGORIA, ANIO_PUBLICACION) VALUES ('Cien años de soledad', 1, 1, 1967);
INSERT INTO LIBROS (TITULO, ID_AUTOR, ID_CATEGORIA, ANIO_PUBLICACION) VALUES ('La casa de los espíritus', 2, 1, 1982);
```

---

## Créditos
Desarrollado por: Jesús

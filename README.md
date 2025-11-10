# API REST de Gestión de Vehículos - Laravel

Prueba técnica de Laravel con API RESTful para gestión de vehículos, marcas y propietarios.

> 📖 **Documentación del Uso de IA**: Este proyecto fue desarrollado con asistencia de IA. Ver [IA_USAGE.md](IA_USAGE.md) para detalles del proceso, prompts utilizados y análisis crítico.

## 🚀 Stack Tecnológico

- **Framework Backend**: Laravel 10.x
- **Base de Datos**: MySQL 8.0
- **Contenedores**: Docker & Docker Compose
- **PHP**: 8.2 con FPM
- **Servidor Web**: Nginx
- **Gestor de Dependencias**: Composer
- **Herramienta de IA**: Cursor con Claude 4.5 Sonnet

## 📋 Requisitos Previos

- Docker y Docker Compose instalados
- Puertos disponibles: 8000 (Nginx), 3306 (MySQL), 8080 (PHPMyAdmin)

## 🛠️ Instalación

### Paso 1: Clonar el repositorio

```bash
git clone <url-del-repositorio>
cd Icanh
```

### Paso 2: Configurar variables de entorno

Copia el archivo de ejemplo y configura tus variables:

```bash
cp .env.example .env
```

Edita el archivo `.env` si necesitas cambiar alguna configuración:

```env
# Puertos de Docker (puedes cambiarlos si están ocupados)
NGINX_PORT=8000
MYSQL_PORT=3306
PHPMYADMIN_PORT=8080

# Credenciales de MySQL
MYSQL_ROOT_PASSWORD=root_password
MYSQL_DATABASE=laravel_db
MYSQL_USER=laravel_user
MYSQL_PASSWORD=laravel_password

# Configuración de Laravel (DB_HOST debe ser "db")
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_password
```

### Paso 3: Levantar los contenedores Docker

```bash
docker-compose up -d --build
```

Este comando descargará las imágenes necesarias, construirá el contenedor de PHP y levantará todos los servicios (PHP, Nginx, MySQL, PHPMyAdmin).

### Paso 4: Ejecutar el script de instalación

```bash
bash install.sh
```

Este script automáticamente:
- Genera la clave de aplicación de Laravel
- Ejecuta las migraciones de base de datos
- Configura los permisos necesarios

### ✅ ¡Listo!

La API estará disponible en: **http://localhost:8000**

## 🌐 Acceso a los Servicios

- **API Laravel**: http://localhost:8000
- **PHPMyAdmin**: http://localhost:8080
  - Usuario: `laravel_user`
  - Contraseña: `laravel_password`

## 📊 Base de Datos y Modelos

### Migraciones

El proyecto incluye las siguientes migraciones que se ejecutan automáticamente con `bash install.sh`:

```bash
# Para ejecutar migraciones manualmente:
docker-compose exec app php artisan migrate

# Para revertir migraciones:
docker-compose exec app php artisan migrate:rollback

# Para reiniciar la base de datos:
docker-compose exec app php artisan migrate:fresh
```

### Tablas y Estructura

#### 1. **vehicle_brands** (Marcas de Vehículos)
```sql
- id: BIGINT (Primary Key)
- brand_name: VARCHAR (único)
- country: VARCHAR
- created_at: TIMESTAMP
- updated_at: TIMESTAMP
```

#### 2. **people** (Personas)
```sql
- id: BIGINT (Primary Key)
- identification_number: VARCHAR (único, cédula)
- name: VARCHAR
- created_at: TIMESTAMP
- updated_at: TIMESTAMP
```

#### 3. **vehicles** (Vehículos)
```sql
- id: BIGINT (Primary Key)
- model: VARCHAR
- vehicle_brand_id: BIGINT (Foreign Key → vehicle_brands)
- number_of_doors: INTEGER
- color: VARCHAR
- created_at: TIMESTAMP
- updated_at: TIMESTAMP
```

#### 4. **person_vehicle** (Tabla Pivote)
```sql
- person_id: BIGINT (Foreign Key → people)
- vehicle_id: BIGINT (Foreign Key → vehicles)
- created_at: TIMESTAMP
- updated_at: TIMESTAMP
- PRIMARY KEY (person_id, vehicle_id)
```

### Relaciones ORM

- **Vehicle** `belongsTo` **VehicleBrand** (Un vehículo pertenece a una marca)
- **Vehicle** `belongsToMany` **Person** (Un vehículo puede tener muchos propietarios)
- **Person** `belongsToMany` **Vehicle** (Una persona puede tener muchos vehículos)
- **VehicleBrand** `hasMany` **Vehicle** (Una marca puede tener muchos vehículos)

## 🔌 Endpoints de la API

### Marcas de Vehículos

```
GET    /api/marcas-vehiculo       - Listar todas las marcas
POST   /api/marcas-vehiculo       - Crear una nueva marca
GET    /api/marcas-vehiculo/{id}  - Obtener una marca específica
PUT    /api/marcas-vehiculo/{id}  - Actualizar una marca
DELETE /api/marcas-vehiculo/{id}  - Eliminar una marca
```

**Ejemplo de request:**
```json
POST /api/marcas-vehiculo
{
  "brand_name": "Toyota",
  "country": "Japan"
}
```

### Personas

```
GET    /api/personas              - Listar todas las personas
POST   /api/personas              - Crear una nueva persona
GET    /api/personas/{id}         - Obtener una persona específica
PUT    /api/personas/{id}         - Actualizar una persona
DELETE /api/personas/{id}         - Eliminar una persona
GET    /api/personas/{id}/vehiculos - Obtener vehículos de una persona
```

**Ejemplo de request:**
```json
POST /api/personas
{
  "identification_number": "1234567890",
  "name": "Juan Pérez"
}
```

### Vehículos

```
GET    /api/vehiculos             - Listar todos los vehículos
POST   /api/vehiculos             - Crear un nuevo vehículo
GET    /api/vehiculos/{id}        - Obtener un vehículo específico
PUT    /api/vehiculos/{id}        - Actualizar un vehículo
DELETE /api/vehiculos/{id}        - Eliminar un vehículo
POST   /api/vehiculos/{id}/propietarios - Asignar propietario a un vehículo
```

**Ejemplo de request:**
```json
POST /api/vehiculos
{
  "model": "Corolla 2024",
  "vehicle_brand_id": 1,
  "number_of_doors": 4,
  "color": "red"
}

POST /api/vehiculos/1/propietarios
{
  "person_id": 1
}
```

## 🔧 Solución de Problemas

### Problema de permisos al editar archivos

Si no puedes guardar cambios en los archivos (error de permisos), ejecuta:

```bash
bash fix-permissions.sh
```

Este script corrige los permisos para que puedas editar los archivos sin problemas.

## 🐳 Comandos Docker Útiles

```bash
# Ver logs de los contenedores
docker-compose logs -f

# Detener los contenedores
docker-compose down

# Reconstruir los contenedores
docker-compose up -d --build

# Acceder al contenedor de PHP
docker exec -it laravel_app bash

# Acceder a MySQL
docker exec -it laravel_mysql mysql -u laravel_user -p

# Limpiar todo (CUIDADO: elimina volúmenes)
docker-compose down -v

# Arreglar permisos de archivos
bash fix-permissions.sh
```

## 🧪 Testing

```bash
# Ejecutar tests
docker exec -it laravel_app php artisan test
```

## 📝 Documentación del Proceso con IA

Este proyecto fue desarrollado con la asistencia de **Cursor** y el modelo **Claude 4.5 Sonnet**. 

### Proceso de Desarrollo

1. **Configuración inicial**: Creación de la estructura Docker con contenedores para PHP, MySQL y Nginx
2. **Instalación de Laravel**: Configuración del framework dentro del entorno containerizado
3. **Modelado de datos**: Diseño de las entidades y sus relaciones según los requisitos
4. **Migraciones**: Creación de las tablas de base de datos con sus constraints
5. **Modelos ORM**: Implementación de los modelos con sus relaciones Eloquent
6. **Controladores API**: Desarrollo de los controladores RESTful con CRUD completo
7. **Validaciones**: Implementación de Form Request classes para validar datos
8. **Rutas**: Configuración de todas las rutas API incluyendo endpoints especiales

### Ventajas del uso de IA

- Aceleración del desarrollo inicial
- Sugerencias de mejores prácticas de Laravel
- Detección temprana de errores
- Código más limpio y organizado
- Documentación generada automáticamente

## 👨‍💻 Autor

Desarrollado por Jheyner - Prueba Técnica PHP/Laravel

## 📄 Licencia

Este proyecto es de código abierto y está disponible bajo la licencia MIT.

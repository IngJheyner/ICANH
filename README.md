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
git clone https://github.com/IngJheyner/ICANH.git ( Se puede Descargar el zip. )
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
- **Documentación Swagger**: http://localhost:8000/api/documentation
- **PHPMyAdmin**: http://localhost:8080
  - Usuario: `laravel_user`
  - Contraseña: `laravel_password`
- **Documentación del Código**: `docs/phpdoc/index.html` (abrir en navegador)

## 📚 Documentación Profesional

El proyecto incluye documentación completa y profesional en dos formatos:

### 1. 📖 Documentación de la API (Swagger/OpenAPI)

Documentación interactiva de todos los endpoints de la API generada automáticamente con OpenAPI 3.0.

**Acceso:** http://localhost:8000/api/documentation

**Características:**
- 📋 Listado completo de todos los endpoints (15 endpoints documentados)
- 🔍 Descripción detallada de cada operación (GET, POST, PUT, DELETE)
- 📝 Esquemas de request/response con ejemplos
- ✅ Códigos de respuesta HTTP (200, 201, 404, 422)
- 🎯 Probador integrado (Try it out) para cada endpoint
- 📊 Modelos de datos documentados (VehicleBrand, Person, Vehicle)

**Endpoints documentados:**
- **Marcas de Vehículos** (5 endpoints): CRUD completo
- **Personas** (6 endpoints): CRUD + obtener vehículos de persona
- **Vehículos** (6 endpoints): CRUD + asignar propietarios

**Regenerar documentación:**
```bash
docker-compose exec app php artisan l5-swagger:generate
```

### 2. 📘 Documentación del Código Fuente (phpDocumentor)

Documentación HTML generada a partir de los docblocks del código fuente.

**Acceso:** Abrir en navegador: `docs/phpdoc/index.html`

> **Nota**: La documentación HTML ya está incluida en el repositorio y lista para usar. No necesitas regenerarla a menos que modifiques el código.

**Contenido documentado:**
- **Controladores** (3 archivos): VehicleBrandController, PersonController, VehicleController
- **Modelos** (3 archivos): VehicleBrand, Person, Vehicle
- **Resources** (3 archivos): API Resources para transformación de datos
- **Requests** (4 archivos): Form Requests para validación
- **Traits** (1 archivo): ApiResponseTrait para respuestas consistentes

**Características:**
- 🗂️ Navegación por namespaces y clases
- 📖 Documentación de métodos públicos y privados
- 🔗 Enlaces entre clases relacionadas
- 📊 Diagramas de herencia
- 🔍 Índice de búsqueda

**Regenerar documentación** (solo si modificas el código):
```bash
# Requiere Docker instalado
docker run --rm -v "$(pwd):/data" phpdoc/phpdoc:3 \
  -d app/Http -d app/Models \
  -t docs/phpdoc \
  --title="API Vehículos - Documentación del Código"

# Arreglar permisos después de regenerar
bash fix-permissions.sh
```

## 🧪 Testing (Pruebas Automatizadas)

El proyecto incluye una suite completa de **47 tests automatizados** con PHPUnit que cubren:
- ✅ Tests unitarios para relaciones entre modelos (ORM)
- ✅ Tests de integración para todos los endpoints CRUD
- ✅ Tests de validación (errores 422)
- ✅ Tests de casos de error (404, duplicados, etc.)
- ✅ Tests de endpoints de relaciones (vehículos de persona, asignar propietarios)

### Ejecutar todos los tests

```bash
docker-compose exec app php artisan test
```

### Ejecutar tests específicos

```bash
# Ejecutar solo tests de VehicleBrand
docker-compose exec app php artisan test --filter=VehicleBrandTest

# Ejecutar solo tests de Person
docker-compose exec app php artisan test --filter=PersonTest

# Ejecutar solo tests de Vehicle
docker-compose exec app php artisan test --filter=VehicleTest

# Ejecutar solo tests unitarios
docker-compose exec app php artisan test --testsuite=Unit
```

### Cobertura de Tests

- **47 tests** en total
- **240 assertions** verificadas
- **100% de éxito** en la última ejecución
- Configuración de base de datos SQLite en memoria para tests rápidos e aislados

### Estructura de Tests

```
tests/
├── Unit/
│   └── ModelRelationshipTest.php   # Tests de relaciones ORM (8 tests)
└── Feature/
    ├── VehicleBrandTest.php         # Tests CRUD de marcas (12 tests)
    ├── PersonTest.php               # Tests CRUD de personas (13 tests)
    └── VehicleTest.php              # Tests CRUD de vehículos (14 tests)
```

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

## 👨‍💻 Autor

Desarrollado por Jheyner - Prueba Técnica PHP/Laravel

## 📄 Licencia

Este proyecto es de código abierto y está disponible bajo la licencia MIT.

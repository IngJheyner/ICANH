# Parte 3: Módulo Odoo - Gestión de Vehículos

## 🎯 Objetivo

Implementar la misma lógica de negocio del CRUD de vehículos dentro de un módulo personalizado de Odoo v18.

## 📋 Desafío

### 3.A. Módulo de Odoo
- ✅ Crear módulo personalizado `gestion_vehiculos`
- ✅ Definir modelos con ORM de Odoo (Many2one, Many2many)
- ✅ Crear vistas básicas (tree/list y formulario)
- ✅ Crear menús en la interfaz de Odoo

### 3.B. API REST para Odoo
- ✅ Exponer CRUD a través de API REST
- Opción elegida: Controladores HTTP nativos de Odoo

## 🚀 Progreso

### ⏱️ Hora 1-2: Setup y Fundamentos ✅ COMPLETADO
- ✅ Instalar Odoo 18 con Docker
- ✅ Crear estructura básica del módulo
- ✅ Implementar modelos (VehicleBrand, Person, Vehicle)
- ✅ Crear vistas XML básicas
- ✅ Configurar seguridad y menús
- ✅ Commit 1: Módulo completo (958 líneas)

### ⏱️ Hora 3: Instalación y Pruebas ✅ COMPLETADO
- ✅ Corregir vistas para Odoo 18 (tree → list)
- ✅ Instalar módulo en Odoo exitosamente
- ✅ Probar CRUD desde la interfaz web
- ✅ Commit 2: Correcciones de compatibilidad

### 🎯 Estado Final
- **Completado**: Módulo funcional con CRUD completo vía interfaz web
- **Pendiente**: API REST (queda como trabajo futuro)
  
**Logros alcanzados**:
- ✅ Aprendizaje de Odoo desde cero con comparaciones Laravel/Odoo
- ✅ ORM de Odoo con relaciones Many2one y Many2many
- ✅ Sistema de vistas XML funcional
- ✅ Menús y navegación integrada
- ✅ Módulo instalable y funcional en producción

---

## 🔧 Instalación del Módulo

### 1. Levantar Odoo (si no está corriendo)

```bash
cd odoo_module
docker-compose up -d
```

### 2. Acceder a Odoo

Abre tu navegador en: **http://localhost:8069**

### 3. Crear Base de Datos (primera vez)

- **Master Password**: `admin`
- **Database Name**: `vehiculos_db`
- **Email**: tu@email.com
- **Password**: (tu contraseña)
- **Demo data**: ❌ Desmarcado
- **Language**: Español
- **Country**: Colombia

### 4. Instalar el Módulo

1. Ve a **Aplicaciones** (Apps)
2. Haz clic en **Actualizar lista de aplicaciones**
3. Busca: `Gestión de Vehículos`
4. Haz clic en **Instalar**

### 5. Usar el Módulo

Una vez instalado, verás el menú **"Gestión Vehículos"** en la barra superior con:
- Marcas de Vehículos
- Vehículos
- Personas

---

## 📚 Aprendizajes Clave

Este módulo fue desarrollado con asistencia de IA, utilizando una metodología didáctica de **comparación Laravel ↔ Odoo** para facilitar el aprendizaje.

### Comparaciones Principales

| Concepto | Laravel (PHP) | Odoo (Python) |
|----------|---------------|---------------|
| **Modelos** | `class Vehicle extends Model` | `class Vehicle(models.Model)` |
| **Relaciones 1:N** | `hasMany()` / `belongsTo()` | `One2many` / `Many2one` |
| **Relaciones N:N** | `belongsToMany()` | `Many2many` |
| **Migraciones** | `php artisan migrate` | Automático al instalar módulo |
| **Vistas** | Blade templates | Vistas XML |
| **Rutas** | `routes/api.php` | Decoradores `@http.route` |
| **Validaciones** | Form Requests | `@api.constrains` + `_sql_constraints` |

### Ventajas de Odoo

✅ **UI automática**: Solo defines el modelo, Odoo genera formularios, listas y búsquedas  
✅ **Relaciones visuales**: Many2many se maneja con widgets interactivos  
✅ **Modo desarrollo**: Auto-reload al modificar archivos  
✅ **Ecosistema integrado**: ERP completo con módulos reutilizables  

### Estructura del Módulo

```
gestion_vehiculos/
├── __manifest__.py          # Metadatos (como composer.json)
├── __init__.py              # Autoload
├── models/                  # ORM (como app/Models/)
│   ├── vehicle_brand.py
│   ├── person.py
│   └── vehicle.py
├── views/                   # UI XML (como resources/views/)
│   ├── vehicle_brand_views.xml
│   ├── person_views.xml
│   ├── vehicle_views.xml
│   └── menu.xml
├── security/                # Permisos
│   └── ir.model.access.csv
└── controllers/             # API (pendiente)
    └── __init__.py
```

---

**Inicio**: Noviembre 11, 2025  
**Finalización**: Noviembre 11, 2025  
**Duración**: ~3 horas (desarrollo incremental)  
**Stack**: Python 3.12 + Odoo 18 + PostgreSQL 15  
**Estrategia**: Progressive Wins (commits incrementales)  
**Metodología**: Aprendizaje asistido por IA con comparaciones didácticas


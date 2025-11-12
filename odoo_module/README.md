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

### ⏱️ Hora 1: Setup y Fundamentos ✅ COMPLETADO
- ✅ Instalar Odoo 18 con Docker
- ✅ Crear estructura básica del módulo
- ✅ Implementar modelos (VehicleBrand, Person, Vehicle)
- ✅ Crear vistas XML básicas
- ✅ Configurar seguridad y menús
- 🔄 Próximo: Commit 1

### ⏱️ Hora 2-3: Instalación y Pruebas (En progreso)
- 🔄 Instalar módulo en Odoo
- [ ] Probar CRUD desde la interfaz
- [ ] Commit 2

### ⏱️ Hora 4: API REST (Pendiente)
- [ ] Controladores HTTP
- [ ] Endpoints CRUD básicos
- [ ] Commit 3

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

**Inicio**: Noviembre 11, 2025  
**Stack**: Python + Odoo 18 + PostgreSQL  
**Estrategia**: Progressive Wins (commits incrementales)


# Documentación del Proyecto

Esta carpeta contiene la documentación generada automáticamente del código fuente.

## 📘 phpDocumentor

La documentación HTML en `phpdoc/` fue generada usando [phpDocumentor](https://www.phpdoc.org/).

### Cómo Acceder

1. Abre el archivo `phpdoc/index.html` en tu navegador web
2. Navega por las diferentes secciones:
   - **Classes**: Todas las clases del proyecto
   - **Namespaces**: Organización por namespaces
   - **Packages**: Agrupación por paquetes
   - **Reports**: Reportes de cobertura de documentación

### Contenido Documentado

- ✅ **Controladores** (3): VehicleBrandController, PersonController, VehicleController
- ✅ **Modelos** (3): VehicleBrand, Person, Vehicle  
- ✅ **Resources** (3): Transformación de datos para respuestas API
- ✅ **Requests** (4): Validación de datos de entrada
- ✅ **Traits** (1): Respuestas API consistentes

### Regenerar Documentación

Si modificas el código fuente y quieres actualizar la documentación:

```bash
# Desde la raíz del proyecto
docker run --rm -v "$(pwd):/data" phpdoc/phpdoc:3 \
  -d app/Http -d app/Models \
  -t docs/phpdoc \
  --title="API Vehículos - Documentación del Código"

# Arreglar permisos
bash fix-permissions.sh
```

### Nota

Esta documentación está incluida en el repositorio Git para que esté disponible inmediatamente después de clonar el proyecto. No necesitas regenerarla a menos que modifiques el código fuente.

---

**Generada con**: phpDocumentor 3  
**Última actualización**: Noviembre 11, 2025


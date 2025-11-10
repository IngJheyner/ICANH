#!/bin/bash

# Script de instalación automatizado de Laravel API con Docker
# Asegúrate de ejecutar primero: docker-compose up -d --build

echo "🚀 Instalando Laravel API de Vehículos..."

# Copiar archivo .env si no existe
if [ ! -f .env ]; then
    echo "📋 Copiando archivo .env..."
    docker-compose exec -T app cp .env.example .env
fi

# Generar clave de aplicación
echo "🔑 Generando clave de aplicación..."
docker-compose exec -T app php artisan key:generate

# Esperar a que MySQL esté listo
echo "⏳ Esperando a que MySQL esté listo..."
sleep 5

# Ejecutar migraciones
echo "📊 Ejecutando migraciones..."
docker-compose exec -T app php artisan migrate --force

# Ejecutar seeders para datos de prueba
echo "🌱 Ejecutando seeders (datos de prueba)..."
docker-compose exec -T app php artisan db:seed --force

# Configurar permisos
echo "🔒 Configurando permisos..."
# Primero, hacer que tu usuario sea dueño de todos los archivos
docker-compose exec -T app chown -R $(id -u):$(id -g) /var/www
# Luego, dar permisos a www-data solo en storage y cache
docker-compose exec -T app chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
docker-compose exec -T app chmod -R 775 /var/www/storage /var/www/bootstrap/cache

echo ""
echo "✅ ¡Instalación completada!"
echo ""
echo "===================================="
echo "  Servicios disponibles:"
echo "===================================="
echo "  📡 API:         http://localhost:8000"
echo "  🗄️  PHPMyAdmin: http://localhost:8080"
echo "===================================="
echo ""
echo "📊 Datos de prueba creados:"
echo "   - 10 Marcas de Vehículos"
echo "   - 15 Personas"
echo "   - 25 Vehículos (con propietarios)"
echo ""


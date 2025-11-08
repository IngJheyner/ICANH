#!/bin/bash

# Script para arreglar permisos de archivos
# Útil cuando Docker crea archivos y tu usuario no puede editarlos

echo "🔧 Arreglando permisos de archivos..."

# Cambiar propietario de archivos a tu usuario
docker-compose exec -T app chown -R $(id -u):$(id -g) /var/www

# Asegurar que www-data pueda escribir en storage y cache
docker-compose exec -T app chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
docker-compose exec -T app chmod -R 775 /var/www/storage /var/www/bootstrap/cache

echo "✅ Permisos corregidos!"
echo ""
echo "Ahora puedes editar los archivos sin problemas."


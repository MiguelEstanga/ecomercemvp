#!/bin/bash

# Crear directorios necesarios
mkdir -p storage/app/public
mkdir -p storage/app/private
mkdir -p storage/framework/{cache,sessions,views}
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Asignar permisos
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Limpiar cachés anteriores
php artisan config:clear
php artisan cache:clear

# Ejecutar migraciones
php artisan migrate --force

# Crear enlace simbólico
php artisan storage:link --force

# Iniciar servidor
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
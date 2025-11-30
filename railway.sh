#!/bin/bash

# Crear directorios necesarios
mkdir -p storage/app/livewire-tmp
mkdir -p storage/app/public
mkdir -p storage/app/private
mkdir -p storage/framework/{cache,sessions,views}
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Asignar permisos
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# ... resto del script
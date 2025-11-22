# ----------------------------------------------------------------------
# Etapa 1: Builder (Instalación de Dependencias)
# Usamos una etiqueta que especifica Composer 2, PHP 8.4 y la base Debian (Buster)
# Esta imagen es conocida por existir y usar 'apt-get'
# ----------------------------------------------------------------------
FROM composer:2-buster-php8.4 AS composer_dependencies

WORKDIR /app

# **CORRECCIÓN DEL GESTOR DE PAQUETES (Cambiamos a APT/apt-get)**
# Ya que la imagen buster-php8.4 es Debian, volvemos a usar 'apt-get'.
# Esto revierte la corrección de 'apk' que hicimos antes.
RUN apt-get update && \
    apt-get install -y libzip-dev && \
    rm -rf /var/lib/apt/lists/*

# Instala la extensión PHP 'zip'
RUN docker-php-ext-install zip

# Copia los archivos de configuración
COPY composer.json composer.lock ./

# Ejecuta composer install
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copia el resto de tu código fuente
COPY . .

# ----------------------------------------------------------------------
# Etapa 2: Final (Imagen de Producción con FrankenPHP)
# ----------------------------------------------------------------------
FROM dunglas/frankenphp:php8.2.29-bookworm AS final_app

# ... (El resto del Dockerfile es idéntico a la versión anterior)
# Usa una imagen base oficial de PHP
FROM php:8.2-fpm-alpine

# --- PASO DE INSTALACIÓN DE DEPENDENCIAS DEL SISTEMA ---
# Instala las dependencias de Linux necesarias, incluyendo 'zip' y 'composer'
RUN apk add --no-cache \
    git \
    zip \
    unzip \
    bash \
    composer \
    nodejs \
    npm 

# ¡ESTO ES LO CRÍTICO! Instala y habilita la extensión ZIP de PHP
RUN docker-php-ext-install zip

# Establece el directorio de trabajo y copia la aplicación
WORKDIR /app
COPY . /app

# --- PASO DE COMPOSER (AHORA LA EXTENSIÓN 'zip' ESTÁ DISPONIBLE) ---
# Instala las dependencias de PHP
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

# Instala y compila los assets de Node
RUN npm ci --production=false
RUN npm run build

# --- COMANDO DE INICIO ---
# El comando de inicio para servir la aplicación PHP.
# NO incluye migraciones.
CMD php -S 0.0.0.0:$PORT -t public
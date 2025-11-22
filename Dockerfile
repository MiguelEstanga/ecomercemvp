# ----------------------------------------------------------------------
# Etapa 1: Builder (Instalación de Dependencias)
# ----------------------------------------------------------------------
FROM composer:2 AS composer_dependencies

WORKDIR /app

# **INSTALACIÓN DE EXTENSIÓN USANDO APK (para imágenes basadas en Alpine)**
RUN apk add --no-cache libzip-dev
# Si la imagen composer:2 no tiene el comando 'docker-php-ext-install' (típico de Alpine),
# la instalación debe hacerse manualmente o usar un paquete precompilado. 
# Si el siguiente comando falla, es un problema de la imagen base de Composer.
# Por lo general, la imagen composer:2 sí se basa en Alpine, pero ya tiene PHP.
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
FROM dunglas/frankenphp:php8.2.29-bookworm AS stage-1 # Cambiado el alias a stage-1

# **INSTALACIÓN DE EXTENSIÓN USANDO APT (La imagen FrankenPHP/Bookworm SÍ usa apt)**
# La imagen 'bookworm' es una variante de Debian y usa apt-get.

# 1. Instala la librería del sistema necesaria para la extensión 'zip'
RUN apt-get update && \
    apt-get install -y libzip-dev && \
    rm -rf /var/lib/apt/lists/*

# 2. Instala la extensión PHP 'zip'
RUN docker-php-ext-install zip

# ... (resto de la configuración: WORKDIR, COPY --from=composer_dependencies, etc.)
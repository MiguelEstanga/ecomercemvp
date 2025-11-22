# ----------------------------------------------------------------------
# Etapa 1: Builder (Instalación de Dependencias con Composer)
# Se usa 'composer:2' que generalmente se basa en Alpine, usando 'apk'
# ----------------------------------------------------------------------
FROM composer:2 AS composer_dependencies

WORKDIR /app

# Instala la librería del sistema necesaria para la extensión 'zip' (Usando APK)
RUN apk update && \
    apk add --no-cache libzip-dev && \
    rm -rf /var/cache/apk/*

# Copia los archivos de configuración
COPY composer.json composer.lock ./

# Instala la extensión PHP 'zip' (asumiendo que docker-php-ext-install está disponible)
RUN docker-php-ext-install zip

# Ejecuta composer install para descargar las dependencias
# Se usa --no-dev para la imagen de producción
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copia el resto de tu código fuente
COPY . .

# ----------------------------------------------------------------------
# Etapa 2: Final (Imagen de Producción con FrankenPHP)
# Se usa 'dunglas/frankenphp:php8.2.29-bookworm', que usa 'apt-get'
# ----------------------------------------------------------------------
# **ATENCIÓN: SINTAXIS FROM CORREGIDA EN ESTA LÍNEA**
FROM dunglas/frankenphp:php8.2.29-bookworm AS final_app

# Establece el directorio de trabajo
WORKDIR /app

# Instala la librería del sistema necesaria para la extensión 'zip' (Usando APT)
RUN apt-get update && \
    apt-get install -y libzip-dev && \
    rm -rf /var/lib/apt/lists/*

# Instala la extensión PHP 'zip'
RUN docker-php-ext-install zip

# Copia SOLO los archivos necesarios desde la etapa de construcción (incluyendo el vendor)
COPY --from=composer_dependencies /app/vendor /app/vendor
COPY --from=composer_dependencies /app /app

# Opcional: Configuración del usuario y permisos si es necesario
# USER www-data

# Si tu aplicación es Laravel/Symfony, puedes añadir comandos de setup aquí:
# RUN php artisan storage:link
# RUN php artisan optimize

# El comando de inicio debe estar definido en la imagen base o aquí
# CMD ["frankenphp", "run", "--config", "/etc/frankenphp/Caddyfile"]
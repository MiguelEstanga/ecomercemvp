# ----------------------------------------------------------------------
# Etapa 1: Builder (Instalación de Dependencias)
# ----------------------------------------------------------------------
FROM composer:2 AS composer_dependencies

WORKDIR /app

# 1. Instala las dependencias del sistema necesarias (libzip-dev para la extensión zip)
# Utilizamos una imagen base de composer que a su vez se basa en Debian
# y que típicamente ya incluye las herramientas para instalar extensiones de PHP.
RUN apt-get update && \
    apt-get install -y libzip-dev && \
    rm -rf /var/lib/apt/lists/*

# Copia los archivos de configuración
COPY composer.json composer.lock ./

# 2. Instala la extensión PHP zip
RUN docker-php-ext-install zip

# 3. Ejecuta composer install
# Usamos --no-dev para la imagen de producción final
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copia el resto de tu código fuente
COPY . .

# ----------------------------------------------------------------------
# Etapa 2: Final (Imagen de Producción)
# ----------------------------------------------------------------------
# Tu imagen base de FrankenPHP
FROM dunglas/frankenphp:php8.2.29-bookworm

# 4. En la imagen final, también necesitamos la extensión zip.
# Asegúrate de que tu imagen base de FrankenPHP tiene las dependencias para instalarla
# o ya la incluye. Asumiremos que necesitas instalarla de nuevo para mayor seguridad.
RUN apt-get update && \
    apt-get install -y libzip-dev && \
    rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install zip

# Establece el directorio de trabajo
WORKDIR /app

# Copia SOLO los archivos necesarios desde la etapa de construcción (incluyendo el vendor)
COPY --from=composer_dependencies /app/vendor /app/vendor
COPY --from=composer_dependencies /app /app

# Si tienes un archivo .env, asegúrate de no copiarlo o usar secretos de Railway.
# Si tu aplicación usa Artisan, podrías necesitar un comando final:
# RUN php artisan storage:link

# Comando de inicio de FrankenPHP (si no está definido en la imagen base)
# CMD ["frankenphp", "run", "--config", "/etc/frankenphp/Caddyfile"]
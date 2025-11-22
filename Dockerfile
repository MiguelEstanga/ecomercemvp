# ----------------------------------------------------------------------
# Etapa 1: Builder (Instalación de Dependencias con Composer)
# Usamos Composer 2 con PHP 8.4 (base Debian 'buster') para asegurar la compatibilidad
# con el requisito máximo de ~8.4.0 de openspout.
# ----------------------------------------------------------------------
FROM composer:2-buster-php8.4 AS composer_dependencies

WORKDIR /app

# Instala la librería del sistema necesaria para la extensión 'zip' (Usando APT)
RUN apt-get update && \
    apt-get install -y libzip-dev && \
    rm -rf /var/lib/apt/lists/*

# Instala la extensión PHP 'zip'
RUN docker-php-ext-install zip

# Copia los archivos de configuración
COPY composer.json composer.lock ./

# Ejecuta composer install para descargar las dependencias
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copia el resto de tu código fuente
COPY . .

# ----------------------------------------------------------------------
# Etapa 2: Final (Imagen de Producción con FrankenPHP)
# Usamos tu imagen de PHP 8.2 (compatible con ^8.2).
# ----------------------------------------------------------------------
FROM dunglas/frankenphp:php8.2.29-bookworm AS final_app

WORKDIR /app

# Instala la librería del sistema necesaria para la extensión 'zip' (Usando APT para bookworm)
RUN apt-get update && \
    apt-get install -y libzip-dev && \
    rm -rf /var/lib/apt/lists/*

# Instala la extensión PHP 'zip'
RUN docker-php-ext-install zip

# Copia los archivos finales desde la etapa de construcción (incluyendo el /vendor)
COPY --from=composer_dependencies /app/vendor /app/vendor
COPY --from=composer_dependencies /app /app

# **CORRECCIÓN CRÍTICA DE PERMISOS PARA LARAVEL**
# 1. Cambiar el dueño de los archivos al usuario 'www-data' (usuario común de FrankenPHP/PHP)
RUN chown -R www-data:www-data /app

# 2. Dar permisos de escritura (775) a los directorios críticos de Laravel
RUN chmod -R 775 /app/storage /app/bootstrap/cache

# Cambiar el usuario por defecto del contenedor a 'www-data'
USER www-data

# Comando de inicio de FrankenPHP (generalmente no es necesario si la imagen base lo tiene, pero se incluye por seguridad)
# CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
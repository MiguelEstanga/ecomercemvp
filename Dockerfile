# ----------------------------------------------------------------------
# Etapa 1: Builder (Instalación de Dependencias)
# Se usa PHP 8.4 para satisfacer las restricciones de openspout/fast-excel
# ----------------------------------------------------------------------
FROM composer:2-php8.4 AS composer_dependencies

WORKDIR /app

# Instala la librería del sistema necesaria para la extensión 'zip' (Usando APK, ya que la imagen composer:2-php8.4 probablemente es Alpine)
RUN apk update && \
    apk add --no-cache libzip-dev && \
    rm -rf /var/cache/apk/*

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
# Usamos tu imagen de PHP 8.2, la cual es compatible con tus requerimientos
# ----------------------------------------------------------------------
FROM dunglas/frankenphp:php8.2.29-bookworm AS final_app

WORKDIR /app

# Instala la librería del sistema necesaria para la extensión 'zip' (Usando APT, para Bookworm/Debian)
RUN apt-get update && \
    apt-get install -y libzip-dev && \
    rm -rf /var/lib/apt/lists/*

# Instala la extensión PHP 'zip'
RUN docker-php-ext-install zip

# Copia los archivos finales desde la etapa de construcción
COPY --from=composer_dependencies /app/vendor /app/vendor
COPY --from=composer_dependencies /app /app

# Comando de ejecución (opcional, si no está en la imagen base)
# CMD ["frankenphp", "run", "--config", "/etc/frankenphp/Caddyfile"]
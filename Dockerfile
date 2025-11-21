# Imagen base de PHP + FrankenPHP
FROM dunglas/frankenphp:php8.2.29-bookworm

# Instalar ZIP y dependencias necesarias
USER root

RUN apt-get update \
    && apt-get install -y libzip-dev zip unzip zlib1g-dev \
    && docker-php-ext-configure zip --with-libzip \
    && docker-php-ext-install zip

# Carpeta de trabajo
WORKDIR /app

# Copiar todo el proyecto dentro de la imagen
COPY . .

# Instalar dependencias PHP
RUN composer install --no-interaction --optimize-autoloader --no-dev --ignore-platform-req=ext-zip

# Exponer puerto para el servidor
EXPOSE 8080

# Comando principal de arranque
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]

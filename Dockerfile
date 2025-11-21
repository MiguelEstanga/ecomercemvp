FROM dunglas/frankenphp:php8.2.29-bookworm

# Instalar zip y dependencias
USER root
RUN apt-get update \
  && apt-get install -y --no-install-recommends libzip-dev zip unzip zlib1g-dev \
  && docker-php-ext-configure zip --with-libzip \
  && docker-php-ext-install zip \
  && rm -rf /var/lib/apt/lists/*

# Directorio de la app
WORKDIR /app

COPY . /app

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# Puerto
EXPOSE 8080

# Usar imagen oficial de PHP 8.2
FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm

# Instalar extensiones PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /app

# Copiar archivos de dependencias
COPY composer.json composer.lock package.json package-lock.json ./

# Instalar dependencias PHP
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

# Instalar dependencias Node
RUN npm ci --production=false

# Copiar resto del código
COPY . .

# Construir assets
RUN npm run build

# Optimizar Laravel
RUN php artisan optimize:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Exponer puerto
EXPOSE 8080

# Comando de inicio
CMD php artisan serve --host=0.0.0.0 --port=8080
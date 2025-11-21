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
    libpq-dev \
    zip \
    unzip \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensiones PHP
RUN docker-php-ext-install pdo_pgsql pdo_mysql mbstring exif pcntl bcmath gd zip opcache

# Instalar Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /app

# Copiar archivos de aplicación
COPY . .

# Instalar dependencias PHP (sin scripts que requieran DB)
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction --no-scripts

# Ejecutar autoload
RUN composer dump-autoload --optimize

# Instalar dependencias Node
RUN npm ci --production=false

# Construir assets de frontend
RUN npm run build

# Limpiar caché de npm
RUN npm cache clean --force

# Crear directorios necesarios y dar permisos
RUN mkdir -p storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache \
    storage/logs \
    bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# Exponer puerto
EXPOSE 8080

# Comando de inicio - aquí SÍ se ejecutan comandos de Laravel
CMD php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan migrate --force && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
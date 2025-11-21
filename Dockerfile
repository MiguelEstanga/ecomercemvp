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

# Copiar TODOS los archivos primero (esto es clave)
COPY . .

# Ahora sí instalar dependencias PHP (con todos los archivos ya presentes)
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction --no-scripts

# Ejecutar scripts de Composer ahora que todo está copiado
RUN composer dump-autoload --optimize

# Instalar dependencias Node
RUN npm ci --production=false

# Construir assets
RUN npm run build

# Limpiar caché de npm
RUN npm cache clean --force

# Optimizar Laravel
RUN php artisan config:clear && \
    php artisan cache:clear && \
    php artisan view:clear

# Crear directorios necesarios y permisos
RUN mkdir -p storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache \
    storage/logs \
    bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# Exponer puerto
EXPOSE 8080

# Comando de inicio
CMD php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan serve --host=0.0.0.0 --port=8080
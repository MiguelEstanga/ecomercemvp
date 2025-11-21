FROM dunglas/frankenphp:php8.2.29-bookworm

# No instales zip, FrankenPHP ya trae esa extensión
WORKDIR /app

COPY . .

# Composer sin validar ext-zip (porque ya viene instalada)
RUN composer install --no-interaction --optimize-autoloader --no-dev --ignore-platform-req=ext-zip

EXPOSE 8080

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]

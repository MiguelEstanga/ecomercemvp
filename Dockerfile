FROM dunglas/frankenphp:php8.2.29-bookworm

# NO instales zip — YA VIENE INSTALADO
# NO uses docker-php-ext-install — NO funciona en FrankenPHP

WORKDIR /app

COPY . .

# Composer sin validar ext-zip (porque la imagen ya la tiene)
RUN composer install --no-interaction --optimize-autoloader --no-dev --ignore-platform-req=ext-zip

EXPOSE 8080

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]

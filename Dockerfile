# ----------------------------------------------------------------------
# Etapa 1: Creador (Builder) - Instala las dependencias con Composer
# ----------------------------------------------------------------------
# Usamos la imagen oficial de Composer para instalar las dependencias
FROM composer:2 AS composer_dependencies

# Establece el directorio de trabajo
WORKDIR /app

# Copia los archivos necesarios para Composer (json y lock)
COPY composer.json composer.lock ./

# Ejecuta composer install. Usamos --no-dev para la imagen de producción.
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copia el resto de tu código fuente
COPY . .

# ----------------------------------------------------------------------
# Etapa 2: Final - La imagen de producción ligera
# ----------------------------------------------------------------------
# Usamos tu imagen base (FrankenPHP)
FROM dunglas/frankenphp:php8.2.29-bookworm

# Establece el directorio de trabajo
WORKDIR /app

# Copia solo los archivos esenciales desde la etapa anterior
# Esto incluye el directorio 'vendor' creado por composer
COPY --from=composer_dependencies /app/vendor /app/vendor
COPY --from=composer_dependencies /app /app

# NOTA: Si necesitas otros comandos de configuración, agrégalos aquí.
# ¡Ya NO necesitas el comando 'RUN composer install' aquí!
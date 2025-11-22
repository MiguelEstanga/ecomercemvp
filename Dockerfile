# ----------------------------------------------------------------------
# Etapa 2: Final (Imagen de Producción con FrankenPHP)
# ----------------------------------------------------------------------
FROM dunglas/frankenphp:php8.2.29-bookworm AS final_app

WORKDIR /app

# ... (Instalación de zip y otras configuraciones de la Etapa 2)

# Copia los archivos finales desde la etapa de construcción
COPY --from=composer_dependencies /app/vendor /app/vendor
COPY --from=composer_dependencies /app /app

# **AGREGAR O REVISAR ESTE BLOQUE DE PERMISOS**
# El usuario de FrankenPHP es 'www-data'. Necesita escribir en 'storage' y 'cache'.

# 1. Cambiar el dueño de todos los archivos al usuario 'www-data'
# (Esto es crucial para que el servidor web pueda leer los archivos)
RUN chown -R www-data:www-data /app

# 2. Dar permisos de escritura (775) a los directorios críticos de Laravel
RUN chmod -R 775 /app/storage /app/bootstrap/cache

# 3. Cambiar el usuario por defecto del contenedor a 'www-data' (mejor práctica de seguridad/permisos)
USER www-data

# Opcional: El comando de inicio debe estar definido en la imagen base o aquí
# CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
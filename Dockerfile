FROM php:8.4-cli

# Instalar dependencias y extensiones para MySQL y PostgreSQL
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev libzip-dev libpq-dev zip unzip \
    && docker-php-ext-install pdo_mysql pdo_pgsql mbstring gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

ENV COMPOSER_MEMORY_LIMIT=-1
RUN composer install --no-dev --no-scripts --optimize-autoloader

EXPOSE 10000

# Ejecuta las migraciones en Supabase automáticamente y luego inicia el servidor
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000
FROM php:8.4-cli

# Instalar dependencias del sistema y extensiones de PHP necesarias
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev libzip-dev zip unzip \
    && docker-php-ext-install pdo_mysql mbstring gd zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

# Permitir uso ilimitado de memoria e instalar dependencias
ENV COMPOSER_MEMORY_LIMIT=-1
RUN composer install --no-dev --no-scripts --optimize-autoloader

EXPOSE 10000
CMD php artisan serve --host=0.0.0.0 --port=10000
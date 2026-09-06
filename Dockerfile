FROM php:8.2-cli

# Instalar dependencias del sistema y librerías necesarias (incluyendo libzip-dev)
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev libzip-dev zip unzip \
    && docker-php-ext-install pdo_mysql mbstring gd zip

# Copiar Composer desde la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

# Permitir uso ilimitado de memoria para Composer durante la instalación
ENV COMPOSER_MEMORY_LIMIT=-1

# Instalar paquetes de Laravel
RUN composer install --no-dev --optimize-autoloader

EXPOSE 10000
CMD php artisan serve --host=0.0.0.0 --port=10000
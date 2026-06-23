# Utiliser PHP 8.2
FROM php:8.2-fpm

# Installer les dépendances système
RUN apt-get update && apt-get install -y libpng-dev libzip-dev zip unzip
RUN docker-php-ext-install pdo_mysql gd zip

# Copier le code source
COPY . /var/www

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www
RUN composer install

# Donner les permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
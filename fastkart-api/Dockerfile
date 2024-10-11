# Dockerfile pour Laravel Backend avec PHP 8.2
FROM php:8.2-fpm

# Installer des dépendances système
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim unzip git curl \
    libonig-dev libxml2-dev \
    libzip-dev \
    libgd-dev \
    libxslt-dev

# Installer les extensions PHP requises
RUN docker-php-ext-install pdo pdo_mysql zip exif pcntl gd

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www

# Copier les fichiers du backend
COPY . /var/www

# Installer les dépendances Laravel
RUN composer install

# Donner les permissions appropriées
RUN chown -R www-data:www-data /var/www

# Exposer le port 8000
EXPOSE 8000

# Démarrer l'application Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

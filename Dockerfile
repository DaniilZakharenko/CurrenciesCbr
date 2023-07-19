FROM php:8.1-fpm

# Install PHP extensions and composer
RUN apt-get update && apt-get install -y \
    curl \
    zip \
    unzip \
    git \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    && docker-php-ext-install zip pdo_mysql mbstring \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /var/www/html

WORKDIR /var/www/html

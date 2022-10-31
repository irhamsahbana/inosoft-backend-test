FROM php:8.1-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev \
    zip \
    unzip \
    nano

# Install supervisor
RUN apt-get install -y supervisor

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd xml zip iconv

# Install mongodb ext for PHP
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# copy laravel project to /var/www
COPY . .
RUN chmod 777 -R /var/www/storage

# remove old .env, copy and paste new one from .env.example(with docker)
# RUN rm .env -f
# RUN cp '.env.example(with docker)' .env

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# RUN composer install
# RUN php artisan key:generate
# RUN php artisan optimize

USER root

CMD ["supervisord", "-c", "/etc/supervisor/supervisord.conf"]

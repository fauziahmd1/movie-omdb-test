# Gunakan PHP 7.4 dengan Apache
FROM php:7.4-apache

# Install dependencies dan PHP extensions
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd

# Set working directory
WORKDIR /var/www/html

# Copy semua project ke container
COPY . /var/www/html

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies tanpa dev dan optimasi autoloader
RUN composer install --no-dev --optimize-autoloader

# Set Apache DocumentRoot ke folder public Laravel
RUN sed -i 's!/var/www/html!/var/www/html/public!' /etc/apache2/sites-available/000-default.conf

# Disable MPM default yang bentrok dan enable MPM prefork + rewrite
RUN a2dismod mpm_event mpm_worker \
    && a2enmod mpm_prefork rewrite

# Expose port default Railway untuk container
EXPOSE 8080

# Start Apache di foreground
CMD ["apache2-foreground"]
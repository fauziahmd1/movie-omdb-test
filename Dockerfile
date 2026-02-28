# Gunakan PHP 7.4 dengan Apache
FROM php:7.4-apache

# Install dependencies PHP dan libraries yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www/html

# Copy semua file project ke container
COPY . /var/www/html

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install dependencies Laravel
RUN composer install --no-dev --optimize-autoloader

# Set DocumentRoot ke folder public
RUN sed -i 's!/var/www/html!/var/www/html/public!' /etc/apache2/sites-available/000-default.conf

# Disable MPM yang konflik dan aktifkan prefork + rewrite
RUN a2dismod mpm_event mpm_worker \
    && a2enmod mpm_prefork \
    && a2enmod rewrite

# Expose port 8080
EXPOSE 8080

# Start Apache di foreground
CMD ["apache2-foreground"]
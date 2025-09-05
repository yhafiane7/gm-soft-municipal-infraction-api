###### Stage 1: Build PHP dependencies (Composer) ######
FROM composer:2 AS vendor

WORKDIR /app

# Copy composer files first for better layer caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --prefer-dist --no-interaction --no-progress --no-scripts

# Copy entire project
COPY . .

# Run the post-autoload scripts manually after artisan exists
RUN composer dump-autoload --optimize
RUN php artisan package:discover

###### Stage 2: Runtime (Apache + PHP) ######
FROM php:8.1-apache

WORKDIR /var/www/html

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
 && docker-php-ext-install \
    pdo \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
 && rm -rf /var/lib/apt/lists/*

# Enable Apache modules
RUN a2enmod rewrite

# Copy Apache virtual host configuration
COPY .docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Copy application files
COPY --from=vendor /app /var/www/html

# Copy and run documentation setup script
COPY setup_documentation.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/setup_documentation.sh
RUN /usr/local/bin/setup_documentation.sh

# Cache Laravel config, routes, and views
RUN php artisan config:clear \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Permissions for Laravel storage and cache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Expose port 80
EXPOSE 80

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/ || exit 1

# Run Apache in foreground
CMD ["apache2-foreground"]
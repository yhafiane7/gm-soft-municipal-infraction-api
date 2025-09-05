###### Stage 1: Build PHP dependencies (Composer) ######
FROM composer:2 AS vendor

WORKDIR /app

# Copy composer files first for better layer caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --prefer-dist --no-interaction --no-progress --no-scripts

# Copy entire project
COPY . .

# Skip the optimization that triggers artisan commands during build
RUN composer dump-autoload --no-scripts

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

# Copy application files from vendor stage
COPY --from=vendor /app /var/www/html

# Optional documentation script
RUN if [ -f setup_documentation.sh ]; then \
        cp setup_documentation.sh /usr/local/bin/ && \
        chmod +x /usr/local/bin/setup_documentation.sh && \
        /usr/local/bin/setup_documentation.sh; \
    fi

# Create required directories and set permissions
RUN mkdir -p bootstrap/cache \
    && mkdir -p storage/logs \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 storage bootstrap/cache

# Ensure resources/views exists (avoids view:clear error)
RUN mkdir -p resources/views

# Entrypoint script for runtime optimizations
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
# Run Laravel optimizations at container start\n\
php artisan package:discover --ansi || true\n\
php artisan config:clear || true\n\
php artisan config:cache || true\n\
php artisan route:cache || true\n\
if [ -d resources/views ]; then php artisan view:clear || true; fi\n\
\n\
# Start Apache\n\
exec "$@"' > /usr/local/bin/docker-entrypoint.sh \
    && chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose port
EXPOSE 80

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/ || exit 1

# Use entrypoint script
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["apache2-foreground"]

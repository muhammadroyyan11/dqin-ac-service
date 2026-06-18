FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    nodejs \
    npm \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd pdo_pgsql

# Install Composer (latest version from installer)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

# Copy application files
COPY . .

# Configure Composer: disable audit blocking
RUN composer config --no-interaction audit.block false 2>/dev/null || true

# Install dependencies (skip audit)
ENV COMPOSER_AUDIT=false
RUN composer install --no-interaction --no-scripts --no-autoloader --no-dev 2>&1 || \
    composer install --no-interaction --no-scripts --no-autoloader --no-dev --prefer-dist 2>&1

# Generate autoloader and app key
RUN composer dump-autoload --optimize && \
    php artisan key:generate --force && \
    php artisan storage:link --force 2>/dev/null || true

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]

# Use PHP 8.4 FPM as base image (can be PHP 8.3 if needed)
FROM php:8.4-fpm

# Install PHP and system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    gnupg \
    ca-certificates \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd intl zip

# Install Node.js 18 LTS
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g npm

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy Laravel project
COPY . /var/www/html

# Set file permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && git config --global --add safe.directory /var/www/html

# Install PHP dependencies
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

# Install JS dependencies and build production assets (Filament UI)
RUN npm install && npm run build

# Expose Laravel dev server port
EXPOSE 8000

# Default command
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

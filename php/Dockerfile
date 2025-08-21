# Start from the base PHP image with FPM
FROM php:8.3-fpm

# Set working directory
WORKDIR /var/www/html

# Install PHP extensions and system dependencies
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libgmp-dev \
    libzip-dev \
    libpq-dev \
    default-mysql-client \
    libmysqlclient-dev \
    curl \
    git \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd bcmath pdo_mysql mysqli pdo_pgsql pgsql gmp zip \
    && pecl install redis && docker-php-ext-enable redis \
    && rm -rf /var/lib/apt/lists/*

# Install npm (Node.js)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Setup Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Copy PHP configuration
COPY php/php.ini /usr/local/etc/php/conf.d/custom.ini

# Copy startup script
COPY php/startup.sh /startup.sh

# Create necessary directories
RUN mkdir -p /var/www/html/bootstrap/cache \
    /var/www/html/storage/framework/cache \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/storage/logs

# Copy application code
COPY . /var/www/html

# Install PHP dependencies as root first
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Install Node.js dependencies as root
RUN npm ci

# Build frontend assets as root
RUN npm run build

# Set proper permissions after all installations
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod +x /startup.sh

# Expose port 9000
EXPOSE 9000

# Use the startup script
CMD ["bash", "-c", "source /startup.sh && php-fpm"]
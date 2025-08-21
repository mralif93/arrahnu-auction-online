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
    libmariadb-dev \
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
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create www-data user and set proper permissions
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

# Set proper ownership and permissions for the working directory
RUN chown -R www-data:www-data /var/www/html

# Copy startup script
COPY startup.sh /usr/local/bin/startup.sh
RUN chmod +x /usr/local/bin/startup.sh

# Switch to www-data user
USER www-data

# Expose port 9000
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
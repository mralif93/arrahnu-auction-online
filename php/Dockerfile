FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    postgresql-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    oniguruma-dev \
    libzip-dev \
    icu-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        pgsql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        intl \
        zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy custom PHP configuration
COPY php.ini /usr/local/etc/php/php.ini

# Create startup script
COPY startup.sh /startup.sh
RUN chmod +x /startup.sh

# Create system user
RUN addgroup -g 1000 www && \
    adduser -u 1000 -G www -s /bin/sh -D www

# Copy application files
COPY . /var/www/html

# Create required directories and set permissions
RUN mkdir -p /var/www/html/storage \
    && mkdir -p /var/www/html/bootstrap/cache \
    && chown -R www:www /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 777 /var/www/html/storage \
    && chmod -R 777 /var/www/html/bootstrap/cache

# Expose port
EXPOSE 9000

# Switch to non-root user
USER www

# Start PHP-FPM
CMD ["/startup.sh"]

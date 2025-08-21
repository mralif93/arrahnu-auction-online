#!/bin/bash

echo "🚀 Starting ArRahnu Auction Laravel Application..."

# Wait for services to be ready
sleep 5

# Fix file permissions and ownership for web server
echo "🔐 Fixing file permissions and ownership..."
chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Ensure public directory is accessible
chmod -R 755 /var/www/html/public
chmod 644 /var/www/html/public/index.php

# Fix specific asset files if they exist
if [ -d "/var/www/html/public/css" ]; then
    chmod 644 /var/www/html/public/css/*
fi

if [ -d "/var/www/html/public/js" ]; then
    chmod 644 /var/www/html/public/js/*
fi

if [ -d "/var/www/html/public/build" ]; then
    chmod 644 /var/www/html/public/build/*
fi

# Ensure Laravel can read its files
chmod 644 /var/www/html/composer.json
chmod 644 /var/www/html/composer.lock

# Create necessary directories if they don't exist
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

# Set basic permissions for Laravel directories
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Clear any existing caches
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true

echo "✅ Laravel application is ready!"
echo "🚀 Starting PHP-FPM..."

# Start PHP-FPM
exec php-fpm

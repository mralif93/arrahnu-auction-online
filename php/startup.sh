#!/bin/bash

echo "🚀 Starting ArRahnu Auction Laravel Application..."

# Wait for services to be ready
sleep 5

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

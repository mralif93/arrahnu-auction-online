#!/bin/bash

echo "🚀 Starting Jariahfund Laravel Application..."

# Wait for all services to be fully up
echo "⏳ Waiting for all services to be ready..."
sleep 5

# Set proper permissions for all files (needed because of volume mounts)
echo "🔐 Setting permissions..."
chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Ensure critical Laravel files are readable
chmod 644 /var/www/html/bootstrap/app.php
chmod 755 /var/www/html/bootstrap
chmod -R 775 /var/www/html/bootstrap/cache

# Remove any existing cache files that might have wrong permissions
rm -rf /var/www/html/bootstrap/cache/*.php
rm -rf /var/www/html/storage/framework/cache/*
rm -rf /var/www/html/storage/framework/views/*

# Create environment file if it does not exist
if [ ! -f ".env" ]; then
    echo "📝 Creating .env file from example..."
    cp .env.example .env
fi

# Check if APP_KEY is set
if ! grep -q "APP_KEY=base64:" .env; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Clear all caches first (before setting permissions)
echo "🧹 Clearing caches..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan cache:clear || true

# Fix permissions again after clearing caches
echo "🔐 Re-setting permissions after cache clear..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "💾 Caching configurations..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Run migrations if needed (skip if database connection fails)
echo "🗄️ Running database migrations..."
php artisan migrate --force || echo "⚠️ Migrations skipped (database may not be accessible)"

# Run admin seeder if no admin users exist
echo "👑 Checking for admin users..."
if php artisan tinker --execute="echo App\Models\User::where('is_admin', true)->count();" 2>/dev/null | grep -q "0"; then
    echo "👥 No admin users found. Running admin user seeder..."
    php artisan db:seed --class=UserSeeder || echo "⚠️ Admin seeder failed"
else
    echo "✅ Admin users already exist. Skipping seeder."
fi

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link || true

# Publish Livewire assets if Livewire is installed
if composer show livewire/livewire > /dev/null 2>&1; then
    echo "📦 Publishing Livewire assets..."
    php artisan livewire:publish --assets || true
fi

# Optimize for production
echo "⚡ Optimizing for production..."
php artisan optimize || true

# Final permission fix after all operations are complete
echo "🔐 Final permission fix after all services are up..."
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/storage/logs/
chmod -R 755 /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache

echo "✅ Laravel application is ready!"
echo "🌐 Application URL: http://localhost"
echo "📊 Health check: http://localhost/health"

# Start PHP-FPM as www-data user
echo "🚀 Starting PHP-FPM..."
exec php-fpm

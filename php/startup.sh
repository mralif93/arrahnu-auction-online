#!/bin/sh

# Function to wait for database connection
wait_for_database() {
    echo "Waiting for database connection..."
    while ! php artisan db:show --quiet 2>/dev/null; do
        echo "Database not ready yet. Waiting..."
        sleep 5
    done
    echo "Database connection established!"
}

# Function to run database migrations
run_migrations() {
    echo "Running database migrations..."
    if php artisan migrate --force; then
        echo "Database migrations completed successfully."
    else
        echo "Warning: Database migrations failed. Continuing anyway..."
    fi
}

# Function to set proper permissions
set_permissions() {
    echo "Setting proper permissions..."
    
    # Set storage permissions
    chmod -R 775 storage/
    chmod -R 775 bootstrap/cache/
    
    # Set ownership
    chown -R www:www storage/
    chown -R www:www bootstrap/cache/
    
    echo "Permissions set successfully."
}

# Main startup sequence
echo "Starting ArRahnu Auction Online PHP application..."

# Set permissions
set_permissions

# Wait for database
wait_for_database

# Run migrations
run_migrations

# Start PHP-FPM
echo "Starting PHP-FPM..."
exec php-fpm

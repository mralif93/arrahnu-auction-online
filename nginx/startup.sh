#!/bin/bash

echo "🔐 Setting permissions for public files..."

# Ensure proper permissions for the web root
chown -R www-data:www-data /var/www/html/public
chmod -R 755 /var/www/html/public

# Create logs directory if it doesn't exist and set permissions
mkdir -p /logs
chown www-data:www-data /logs
chmod 755 /logs

echo "🚀 Starting Nginx..."
exec nginx -g "daemon off;"

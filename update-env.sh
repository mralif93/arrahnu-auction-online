#!/bin/bash

# ArRahnu Auction Online - Environment Update Script

set -e

echo "=========================================="
echo "  Environment Update & Container Restart"
echo "=========================================="
echo

# Check if .env exists
if [ ! -f ".env" ]; then
    echo "Error: .env file not found. Run deploy-local.sh first."
    exit 1
fi

# Update .env from .env-docker.local
if [ -f ".env-docker.local" ]; then
    echo "Updating .env from .env-docker.local..."
    cp .env-docker.local .env
    echo "✅ .env file updated"
else
    echo "Warning: .env-docker.local not found"
fi

# Show current configuration
echo
echo "Current Configuration:"
echo "======================"
if [ -f ".env" ]; then
    echo "APP_PORT: $(grep '^APP_PORT=' .env | cut -d'=' -f2 || echo '8080 (default)')"
    echo "DB_PORT: $(grep '^DB_PORT=' .env | cut -d'=' -f2 || echo '3307 (default)')"
    echo "DB_DATABASE: $(grep '^DB_DATABASE=' .env | cut -d'=' -f2 || echo 'not set')"
    echo "APP_DEBUG: $(grep '^APP_DEBUG=' .env | cut -d'=' -f2 || echo 'not set')"
fi

# Ask user if they want to restart containers
echo
read -p "Do you want to restart containers to apply changes? (y/n): " -n 1 -r
echo

if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "Restarting containers..."
    docker-compose -f docker-compose.local.yaml restart
    
    echo
    echo "✅ Containers restarted successfully!"
    echo
    echo "New URLs:"
    echo "  - Web: http://localhost:$(grep '^APP_PORT=' .env | cut -d'=' -f2 || echo '8080')"
    echo "  - Database: localhost:$(grep '^DB_PORT=' .env | cut -d'=' -f2 || echo '3307')"
else
    echo "Containers not restarted. Changes will take effect after next restart."
fi

echo
echo "To apply changes manually:"
echo "  docker-compose -f docker-compose.local.yaml restart"
echo
echo "To view current status:"
echo "  docker-compose -f docker-compose.local.yaml ps"

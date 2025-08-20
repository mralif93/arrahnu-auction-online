#!/bin/bash

# ArRahnu Auction Online - Environment Switcher

set -e

echo "=========================================="
echo "  Environment Configuration Switcher"
echo "=========================================="
echo

# Check if .env exists
if [ ! -f ".env" ]; then
    echo "Error: .env file not found. Run deploy-local.sh first."
    exit 1
fi

# Show available environments
echo "Available environments:"
echo "1) local      - Local development (localhost:8080)"
echo "2) development - Development server (localhost:8080)"
echo "3) production - Production server (arrahnuauction.muamalat.com.my)"
echo

# Get user choice
read -p "Select environment (1-3): " -n 1 -r
echo

case $REPLY in
    1)
        ENV_FILE=".env-docker.local"
        ENV_NAME="Local Development"
        ;;
    2)
        ENV_FILE=".env-docker.development"
        ENV_NAME="Development Server"
        ;;
    3)
        ENV_FILE=".env-docker.production"
        ENV_NAME="Production Server"
        ;;
    *)
        echo "Invalid choice. Exiting."
        exit 1
        ;;
esac

# Check if environment file exists
if [ ! -f "$ENV_FILE" ]; then
    echo "Error: Environment file $ENV_FILE not found."
    exit 1
fi

echo
echo "Switching to: $ENV_NAME"
echo "Source file: $ENV_FILE"
echo

# Backup current .env
if [ -f ".env" ]; then
    cp .env .env.backup.$(date +%Y%m%d_%H%M%S)
    echo "✅ Current .env backed up"
fi

# Copy new environment
cp "$ENV_FILE" .env
echo "✅ Environment switched to $ENV_NAME"

# Show key differences
echo
echo "Key Configuration Changes:"
echo "========================"
if [ -f ".env" ]; then
    echo "APP_ENV: $(grep '^APP_ENV=' .env | cut -d'=' -f2 || echo 'not set')"
    echo "APP_URL: $(grep '^APP_URL=' .env | cut -d'=' -f2 || echo 'not set')"
    echo "APP_PORT: $(grep '^APP_PORT=' .env | cut -d'=' -f2 || echo 'not set')"
    echo "DB_PORT: $(grep '^DB_PORT=' .env | cut -d'=' -f2 || echo 'not set')"
    echo "APP_DEBUG: $(grep '^APP_DEBUG=' .env | cut -d'=' -f2 || echo 'not set')"
fi

# Ask if user wants to restart containers
echo
read -p "Do you want to restart containers to apply changes? (y/n): " -n 1 -r
echo

if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "Restarting containers..."
    
    # Determine which compose file to use
    if [[ "$ENV_FILE" == *"production"* ]]; then
        COMPOSE_FILE="docker-compose.yaml"
        echo "Using production compose file: $COMPOSE_FILE"
    else
        COMPOSE_FILE="docker-compose.local.yaml"
        echo "Using local compose file: $COMPOSE_FILE"
    fi
    
    # Restart containers
    docker-compose -f "$COMPOSE_FILE" restart
    
    echo
    echo "✅ Containers restarted successfully!"
    echo
    echo "New URLs:"
    echo "  - Web: $(grep '^APP_URL=' .env | cut -d'=' -f2 || echo 'http://localhost:8080')"
    echo "  - Database: localhost:$(grep '^DB_PORT=' .env | cut -d'=' -f2 || echo '3307')"
else
    echo "Containers not restarted. Changes will take effect after next restart."
fi

echo
echo "To apply changes manually:"
if [[ "$ENV_FILE" == *"production"* ]]; then
    echo "  docker-compose restart"
else
    echo "  docker-compose -f docker-compose.local.yaml restart"
fi

echo
echo "To view current status:"
if [[ "$ENV_FILE" == *"production"* ]]; then
    echo "  docker-compose ps"
else
    echo "  docker-compose -f docker-compose.local.yaml ps"
fi

echo
echo "To restore previous environment:"
echo "  cp .env.backup.* .env"

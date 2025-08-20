#!/bin/bash

# ArRahnu Auction Online - Local Development Deployment

set -e

echo "=========================================="
echo "  ArRahnu Auction Online - Local Testing"
echo "=========================================="
echo

# Check if Docker is running
if ! docker info &> /dev/null; then
    echo "Error: Docker is not running. Please start Docker first."
    exit 1
fi

# Setup local environment
echo "Setting up local environment..."
if [ ! -f ".env" ]; then
    if [ -f ".env-docker.local" ]; then
        cp .env-docker.local .env
        echo "Local environment file copied from .env-docker.local"
    else
        echo "Warning: No local environment file found. Please create one manually."
    fi
else
    # Update .env with latest .env-docker.local
    if [ -f ".env-docker.local" ]; then
        cp .env-docker.local .env
        echo "Local environment file updated from .env-docker.local"
    fi
fi

# Stop any existing containers
echo "Stopping existing containers..."
docker-compose -f docker-compose.local.yaml down 2>/dev/null || true

# Build and start services
echo "Building and starting services..."
docker-compose -f docker-compose.local.yaml up -d --build

# Wait for services to be ready
echo "Waiting for services to be ready..."
sleep 10

# Check service status
echo "Checking service status..."
docker-compose -f docker-compose.local.yaml ps

echo
echo "=========================================="
echo "  Local Development Setup Complete!"
echo "=========================================="
echo
echo "Services:"
echo "  - Web: http://localhost:${APP_PORT:-8080}"
echo "  - API: http://localhost:${APP_PORT:-8080}/api"
echo "  - Admin: http://localhost:${APP_PORT:-8080}/admin"
echo "  - Database: localhost:${DB_PORT:-3307}"
echo
echo "Environment:"
echo "  - Domain: localhost"
echo "  - Debug Mode: Enabled"
echo "  - Mail: Logged to storage/logs/laravel.log"
echo
echo "Configuration:"
echo "  - All settings are now in .env file"
echo "  - Update .env to change any values"
echo "  - Restart containers after .env changes"
echo
echo "Useful commands:"
echo "  View logs: docker-compose -f docker-compose.local.yaml logs"
echo "  Stop services: docker-compose -f docker-compose.local.yaml down"
echo "  Restart: docker-compose -f docker-compose.local.yaml restart"
echo "  Update .env: edit .env file and restart containers"
echo
echo "Note: This is HTTP-only for local development on localhost."
echo "For production with SSL, use the main docker-compose.yaml"

#!/bin/bash

# ArRahnu Auction Online - Production Deployment Script
# This script sets up the production environment with SSL certificates

set -e

echo "🚀 Starting production deployment..."

# Check if running as root (needed for SSL operations)
if [[ $EUID -eq 0 ]]; then
   echo "❌ This script should not be run as root"
   exit 1
fi

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
DOMAIN=${1:-"arrahnuauction.muamalat.com.my"}
EMAIL=${2:-"admin@arrahnuauction.com"}

echo -e "${YELLOW}Domain: ${DOMAIN}${NC}"
echo -e "${YELLOW}Email: ${EMAIL}${NC}"

# Check if SSL certificates exist
if [[ ! -f "nginx/certs/domain.pem" ]] || [[ ! -f "nginx/certs/domain.rsa" ]]; then
    echo -e "${YELLOW}SSL certificates not found. Setting up Let's Encrypt...${NC}"
    
    # Install certbot if not available
    if ! command -v certbot &> /dev/null; then
        echo "Installing certbot..."
        sudo apt-get update
        sudo apt-get install -y certbot
    fi
    
    # Stop nginx temporarily for certificate generation
    echo "Stopping nginx for certificate generation..."
    docker-compose down nginx 2>/dev/null || true
    
    # Generate SSL certificate
    echo "Generating SSL certificate for ${DOMAIN}..."
    sudo certbot certonly --standalone -d ${DOMAIN} --email ${EMAIL} --agree-tos --non-interactive
    
    # Copy certificates to nginx/certs directory
    echo "Copying certificates..."
    sudo cp /etc/letsencrypt/live/${DOMAIN}/fullchain.pem ./nginx/certs/domain.pem
    sudo cp /etc/letsencrypt/live/${DOMAIN}/privkey.pem ./nginx/certs/domain.rsa
    
    # Set proper permissions
    sudo chown $USER:$USER ./nginx/certs/domain.pem ./nginx/certs/domain.rsa
    chmod 644 ./nginx/certs/domain.pem
    chmod 600 ./nginx/certs/domain.rsa
    
    echo -e "${GREEN}SSL certificates generated successfully!${NC}"
else
    echo -e "${GREEN}SSL certificates already exist.${NC}"
fi

# Update domain in nginx configuration
echo "Updating nginx configuration with domain: ${DOMAIN}"
sed -i.bak "s/server_name _;/server_name ${DOMAIN};/g" nginx/nginx.conf

# Build and start production containers
echo "Building and starting production containers..."
docker-compose -f docker-compose.yaml build
docker-compose -f docker-compose.yaml up -d

# Wait for services to be ready
echo "Waiting for services to be ready..."
sleep 10

# Test nginx configuration
echo "Testing nginx configuration..."
docker-compose exec nginx nginx -t

# Check service status
echo "Checking service status..."
docker-compose ps

# Test HTTPS connection
echo "Testing HTTPS connection..."
if command -v curl &> /dev/null; then
    echo "Testing HTTP to HTTPS redirect..."
    HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://${DOMAIN})
    echo "HTTP Status: ${HTTP_STATUS}"
    
    echo "Testing HTTPS connection..."
    HTTPS_STATUS=$(curl -s -o /dev/null -w "%{http_code}" https://${DOMAIN})
    echo "HTTPS Status: ${HTTPS_STATUS}"
fi

echo -e "${GREEN}✅ Production deployment completed successfully!${NC}"
echo -e "${YELLOW}Your application is now available at: https://${DOMAIN}${NC}"

# Setup automatic certificate renewal
echo "Setting up automatic certificate renewal..."
sudo crontab -l 2>/dev/null | { cat; echo "0 12 * * * /usr/bin/certbot renew --quiet && docker-compose -f $(pwd)/docker-compose.yaml exec nginx nginx -s reload"; } | sudo crontab -

echo -e "${GREEN}Certificate renewal cron job added!${NC}"
echo -e "${YELLOW}Certificates will be automatically renewed daily at 12:00 PM${NC}"

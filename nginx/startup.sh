#!/bin/sh

# Function to check if SSL certificates exist
check_ssl_certificates() {
    if [ ! -f /etc/nginx/ssl/arrahnuauction.muamalat.com.my.crt ] || [ ! -f /etc/nginx/ssl/arrahnuauction.muamalat.com.my.key ]; then
        echo "Warning: SSL certificates not found. Creating self-signed certificates for development..."
        
        # Create self-signed certificate for development
        openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
            -keyout /etc/nginx/ssl/arrahnuauction.muamalat.com.my.key \
            -out /etc/nginx/ssl/arrahnuauction.muamalat.com.my.crt \
            -subj "/C=MY/ST=Kuala Lumpur/L=Kuala Lumpur/O=ArRahnu Auction/OU=IT Department/CN=arrahnuauction.muamalat.com.my"
        
        echo "Self-signed certificates created successfully."
    else
        echo "SSL certificates found and ready."
    fi
}

# Function to test Nginx configuration
test_nginx_config() {
    echo "Testing Nginx configuration..."
    if nginx -t; then
        echo "Nginx configuration is valid."
        return 0
    else
        echo "Nginx configuration has errors!"
        return 1
    fi
}

# Main startup sequence
echo "Starting ArRahnu Auction Online Nginx service..."

# Check SSL certificates
check_ssl_certificates

# Test configuration
if ! test_nginx_config; then
    echo "Failed to start Nginx due to configuration errors."
    exit 1
fi

# Start Nginx in foreground
echo "Starting Nginx..."
exec nginx -g "daemon off;"

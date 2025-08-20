#!/bin/bash

# ArRahnu Auction Online - Simple SSL Setup Script

set -e

# Configuration
DOMAIN="arrahnuauction.muamalat.com.my"
SSL_DIR="ssl"
CERT_FILE="$SSL_DIR/${DOMAIN}.crt"
KEY_FILE="$SSL_DIR/${DOMAIN}.key"

# Function to create SSL directory
create_ssl_directory() {
    if [ ! -d "$SSL_DIR" ]; then
        mkdir -p "$SSL_DIR"
        echo "SSL directory created: $SSL_DIR"
    else
        echo "SSL directory already exists: $SSL_DIR"
    fi
}

# Function to check existing certificates
check_existing_certificates() {
    if [ -f "$CERT_FILE" ] && [ -f "$KEY_FILE" ]; then
        echo "SSL certificates found:"
        echo "  Certificate: $CERT_FILE"
        echo "  Private Key: $KEY_FILE"
        return 0
    else
        echo "SSL certificates not found."
        return 1
    fi
}

# Function to create self-signed certificate
create_self_signed_certificate() {
    echo "Creating self-signed certificate for development..."
    
    if ! command -v openssl &> /dev/null; then
        echo "Error: OpenSSL is not installed. Please install OpenSSL first."
        exit 1
    fi
    
    # Create self-signed certificate
    openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
        -keyout "$KEY_FILE" \
        -out "$CERT_FILE" \
        -subj "/C=MY/ST=Kuala Lumpur/L=Kuala Lumpur/O=ArRahnu Auction/OU=IT Department/CN=$DOMAIN"
    
    if [ $? -eq 0 ]; then
        echo "Self-signed certificate created successfully:"
        echo "  Certificate: $CERT_FILE"
        echo "  Private Key: $KEY_FILE"
        echo "Note: This is a self-signed certificate for development only."
    else
        echo "Error: Failed to create self-signed certificate."
        exit 1
    fi
}

# Function to setup manual certificate
setup_manual_certificate() {
    echo "Setting up manual certificate..."
    
    echo
    echo "Please provide the path to your SSL certificate files:"
    echo
    
    read -p "Certificate file path (.crt/.pem): " CERT_PATH
    read -p "Private key file path (.key/.pem): " KEY_PATH
    
    if [ ! -f "$CERT_PATH" ]; then
        echo "Error: Certificate file not found: $CERT_PATH"
        return 1
    fi
    
    if [ ! -f "$KEY_PATH" ]; then
        echo "Error: Private key file not found: $KEY_PATH"
        return 1
    fi
    
    # Copy certificates
    cp "$CERT_PATH" "$CERT_FILE"
    cp "$KEY_PATH" "$KEY_FILE"
    
    # Set proper permissions
    chmod 600 "$KEY_FILE"
    chmod 644 "$CERT_FILE"
    
    echo "Manual certificates copied successfully:"
    echo "  Certificate: $CERT_FILE"
    echo "  Private Key: $KEY_FILE"
}

# Function to restart services
restart_services() {
    echo "Restarting services to apply SSL changes..."
    
    if docker-compose ps &> /dev/null; then
        docker-compose restart nginx
        echo "Services restarted successfully."
    else
        echo "Warning: Docker Compose not running. Please restart services manually."
    fi
}

# Function to show SSL information
show_ssl_info() {
    echo
    echo "=== SSL Configuration Information ==="
    echo "Domain: $DOMAIN"
    echo "SSL Directory: $SSL_DIR"
    echo "Certificate: $CERT_FILE"
    echo "Private Key: $KEY_FILE"
    echo
    echo "=== Next Steps ==="
    echo "1. Ensure your DNS points to this server"
    echo "2. Restart services if needed: ./setup-ssl.sh restart"
    echo "3. Access your site: https://$DOMAIN"
}

# Main script logic
case "${1:-setup}" in
    "setup")
        echo "=========================================="
        echo "  ArRahnu Auction Online - Simple SSL Setup"
        echo "=========================================="
        echo
        
        create_ssl_directory
        
        if check_existing_certificates; then
            echo
            read -p "Certificates already exist. Do you want to replace them? (y/N): " REPLACE
            if [[ $REPLACE =~ ^[Yy]$ ]]; then
                rm -f "$CERT_FILE" "$KEY_FILE"
            else
                echo "Using existing certificates."
                show_ssl_info
                exit 0
            fi
        fi
        
        echo
        echo "Choose SSL certificate setup method:"
        echo "1. Manual certificate files"
        echo "2. Self-signed certificate (development only)"
        echo
        
        read -p "Enter your choice (1-2): " CHOICE
        
        case $CHOICE in
            1)
                setup_manual_certificate
                ;;
            2)
                create_self_signed_certificate
                ;;
            *)
                echo "Error: Invalid choice. Exiting."
                exit 1
                ;;
        esac
        
        restart_services
        show_ssl_info
        ;;
        
    "restart")
        restart_services
        ;;
        
    "info")
        show_ssl_info
        ;;
        
    "help"|"-h"|"--help")
        echo "Usage: $0 [command]"
        echo
        echo "Commands:"
        echo "  setup    - Setup SSL certificates (default)"
        echo "  restart  - Restart services"
        echo "  info     - Show SSL information"
        echo "  help     - Show this help message"
        ;;
        
    *)
        echo "Error: Unknown command: $1"
        echo "Use '$0 help' for usage information."
        exit 1
        ;;
esac

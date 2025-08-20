#!/bin/bash

# ArRahnu Auction Online - Simple Docker Deployment Script

set -e

# Colors for output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
PROJECT_NAME="ArRahnu Auction Online"
DOMAIN="arrahnuauction.muamalat.com.my"

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

# Function to check prerequisites
check_prerequisites() {
    print_status "Checking prerequisites..."
    
    # Check if Docker is installed
    if ! command -v docker &> /dev/null; then
        echo "Error: Docker is not installed. Please install Docker first."
        exit 1
    fi
    
    # Check if Docker Compose is installed
    if ! command -v docker-compose &> /dev/null; then
        echo "Error: Docker Compose is not installed. Please install Docker Compose first."
        exit 1
    fi
    
    # Check Docker daemon
    if ! docker info &> /dev/null; then
        echo "Error: Docker daemon is not running. Please start Docker first."
        exit 1
    fi
    
    print_success "Prerequisites check passed."
}

# Function to setup environment
setup_environment() {
    print_status "Setting up environment..."
    
    # Create SSL directory if it doesn't exist
    if [ ! -d "ssl" ]; then
        mkdir -p ssl
        echo "SSL directory created. Please add your SSL certificates:"
        echo "  - ssl/arrahnuauction.muamalat.com.my.crt"
        echo "  - ssl/arrahnuauction.muamalat.com.my.key"
    fi
    
    # Copy environment file if .env doesn't exist
    if [ ! -f ".env" ]; then
        if [ -f "docker.env" ]; then
            cp docker.env .env
            print_success "Environment file copied from docker.env"
        else
            echo "Warning: No .env file found. Please create one manually."
        fi
    fi
    
    print_success "Environment setup completed."
}

# Function to build and start services
deploy_services() {
    print_status "Building and starting Docker services..."
    
    # Build services
    if docker-compose build; then
        print_success "Services built successfully."
    else
        echo "Error: Failed to build services."
        exit 1
    fi
    
    # Start services
    if docker-compose up -d; then
        print_success "Services started successfully."
    else
        echo "Error: Failed to start services."
        exit 1
    fi
}

# Function to setup database
setup_database() {
    print_status "Setting up database..."
    
    # Wait a bit for services to be ready
    sleep 10
    
    # Run migrations
    if docker-compose exec app php artisan migrate --force; then
        print_success "Database migrations completed."
    else
        echo "Warning: Database migrations failed or not needed."
    fi
    
    print_success "Database setup completed."
}

# Function to show deployment info
show_deployment_info() {
    print_success "Deployment completed successfully!"
    echo
    echo "=== Deployment Information ==="
    echo "Project: $PROJECT_NAME"
    echo "Domain: $DOMAIN"
    echo
    echo "=== Services ==="
    echo "  - Web: https://$DOMAIN"
    echo "  - API: https://$DOMAIN/api"
    echo "  - Admin: https://$DOMAIN/admin"
    echo
    echo "=== Useful Commands ==="
    echo "  View logs: docker-compose logs"
    echo "  Stop services: docker-compose down"
    echo "  Restart services: docker-compose restart"
    echo
    echo "=== Next Steps ==="
    echo "1. Configure your DNS to point to this server"
    echo "2. Add SSL certificates to ssl/ directory"
    echo "3. Test the application"
}

# Main deployment function
deploy() {
    echo "=========================================="
    echo "  $PROJECT_NAME - Simple Docker Deployment"
    echo "=========================================="
    echo
    
    check_prerequisites
    setup_environment
    deploy_services
    setup_database
    show_deployment_info
}

# Main script logic
case "${1:-deploy}" in
    "deploy")
        deploy
        ;;
    "status")
        docker-compose ps
        ;;
    "logs")
        docker-compose logs -f
        ;;
    "stop")
        print_status "Stopping services..."
        docker-compose down
        print_success "Services stopped."
        ;;
    "restart")
        print_status "Restarting services..."
        docker-compose restart
        print_success "Services restarted."
        ;;
    "help"|"-h"|"--help")
        echo "Usage: $0 [command]"
        echo
        echo "Commands:"
        echo "  deploy   - Deploy the application (default)"
        echo "  status   - Check service status"
        echo "  logs     - View service logs"
        echo "  stop     - Stop all services"
        echo "  restart  - Restart all services"
        echo "  help     - Show this help message"
        ;;
    *)
        echo "Error: Unknown command: $1"
        echo "Use '$0 help' for usage information."
        exit 1
        ;;
esac

#!/bin/bash

echo "🚀 ArRahnu Auction Online - Docker Services Startup"
echo "=================================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to show usage
show_usage() {
    echo "Usage: $0 {start|stop|restart|status|logs|build|clean}"
    echo ""
    echo "Commands:"
    echo "  start   - Start all services"
    echo "  stop    - Stop all services"
    echo "  restart - Restart all services"
    echo "  status  - Show status of all services"
    echo "  logs    - Show logs for all services"
    echo "  build   - Build all services"
    echo "  clean   - Clean up containers and volumes"
    echo ""
    echo "Individual Service Control:"
    echo "  nginx   - Control Nginx service"
    echo "  php     - Control PHP-FPM service"
    echo "  redis   - Control Redis service"
    echo ""
    echo "Examples:"
    echo "  $0 start                    # Start all services"
    echo "  $0 nginx restart            # Restart Nginx only"
    echo "  $0 php status               # Show PHP-FPM status"
    echo "  $0 redis monitor            # Monitor Redis"
}

# Function to start all services
start_services() {
    echo -e "${BLUE}🔨 Starting all Docker services...${NC}"
    docker-compose up -d --build
    
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✅ All services started successfully!${NC}"
        echo ""
        echo -e "${YELLOW}📋 Service URLs:${NC}"
        echo "  - Web Application: http://localhost:8000"
        echo "  - Redis: localhost:6379"
        echo ""
        echo -e "${YELLOW}📚 Useful commands:${NC}"
        echo "  $0 status               # Check service status"
        echo "  $0 logs                 # View service logs"
        echo "  docker-compose exec app bash    # Access PHP container"
        echo "  docker-compose exec webserver bash    # Access Nginx container"
        echo "  docker-compose exec redis redis-cli -a secret    # Access Redis"
    else
        echo -e "${RED}❌ Failed to start services${NC}"
        exit 1
    fi
}

# Function to stop all services
stop_services() {
    echo -e "${BLUE}🛑 Stopping all Docker services...${NC}"
    docker-compose down
    
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✅ All services stopped successfully!${NC}"
    else
        echo -e "${RED}❌ Failed to stop services${NC}"
        exit 1
    fi
}

# Function to restart all services
restart_services() {
    echo -e "${BLUE}🔄 Restarting all Docker services...${NC}"
    docker-compose restart
    
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✅ All services restarted successfully!${NC}"
    else
        echo -e "${RED}❌ Failed to restart services${NC}"
        exit 1
    fi
}

# Function to show status
show_status() {
    echo -e "${BLUE}📊 Docker Services Status:${NC}"
    echo ""
    docker-compose ps
    echo ""
    
    # Check if services are accessible
    echo -e "${BLUE}🔍 Service Health Check:${NC}"
    
    # Check web application
    if curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/health | grep -q "200"; then
        echo -e "${GREEN}✅ Web Application: Healthy${NC}"
    else
        echo -e "${RED}❌ Web Application: Unhealthy${NC}"
    fi
    
    # Check Redis
    if docker-compose exec -T redis redis-cli -a secret ping > /dev/null 2>&1; then
        echo -e "${GREEN}✅ Redis: Healthy${NC}"
    else
        echo -e "${RED}❌ Redis: Unhealthy${NC}"
    fi
}

# Function to show logs
show_logs() {
    echo -e "${BLUE}📋 Docker Services Logs:${NC}"
    docker-compose logs -f
}

# Function to build services
build_services() {
    echo -e "${BLUE}🔨 Building all Docker services...${NC}"
    docker-compose build --no-cache
    
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✅ All services built successfully!${NC}"
    else
        echo -e "${RED}❌ Failed to build services${NC}"
        exit 1
    fi
}

# Function to clean up
clean_services() {
    echo -e "${BLUE}🧹 Cleaning up Docker services...${NC}"
    docker-compose down --volumes --remove-orphans
    
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✅ Cleanup completed successfully!${NC}"
    else
        echo -e "${RED}❌ Failed to cleanup services${NC}"
        exit 1
    fi
}

# Function to control individual services
control_service() {
    local service=$1
    local action=$2
    
    case $service in
        nginx)
            echo -e "${BLUE}🌐 Controlling Nginx service...${NC}"
            docker-compose exec webserver /usr/local/bin/startup.sh $action
            ;;
        php)
            echo -e "${BLUE}🐘 Controlling PHP-FPM service...${NC}"
            docker-compose exec app /usr/local/bin/startup.sh $action
            ;;
        redis)
            echo -e "${BLUE}🔴 Controlling Redis service...${NC}"
            docker-compose exec redis /usr/local/bin/startup.sh $action
            ;;
        *)
            echo -e "${RED}❌ Unknown service: $service${NC}"
            echo "Available services: nginx, php, redis"
            exit 1
            ;;
    esac
}

# Main execution
case "${1:-start}" in
    start)
        start_services
        ;;
    stop)
        stop_services
        ;;
    restart)
        restart_services
        ;;
    status)
        show_status
        ;;
    logs)
        show_logs
        ;;
    build)
        build_services
        ;;
    clean)
        clean_services
        ;;
    nginx|php|redis)
        if [ -z "$2" ]; then
            echo -e "${RED}❌ Action required for service control${NC}"
            echo "Usage: $0 $1 {start|stop|restart|status|check}"
            exit 1
        fi
        control_service $1 $2
        ;;
    *)
        show_usage
        exit 1
        ;;
esac

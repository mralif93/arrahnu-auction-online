#!/bin/bash

echo "🚀 ArRahnu Auction Online - Server Deployment Script"
echo "===================================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
PROJECT_NAME="arrahnu-auction-online"
SERVER_USER="www-data"
SERVER_GROUP="www-data"
WEB_ROOT="/var/www/html"
PROJECT_PATH="$WEB_ROOT/$PROJECT_NAME"
NGINX_SITES="/etc/nginx/sites-available"
NGINX_ENABLED="/etc/nginx/sites-enabled"
PHP_VERSION="8.2"

# Function to check if running as root
check_root() {
    if [ "$EUID" -ne 0 ]; then
        echo -e "${RED}❌ This script must be run as root (use sudo)${NC}"
        exit 1
    fi
}

# Function to detect server environment
detect_environment() {
    echo -e "${BLUE}🔍 Detecting server environment...${NC}"
    
    if [ -f /etc/debian_version ]; then
        OS="debian"
        echo -e "${GREEN}✅ Detected: Debian/Ubuntu${NC}"
    elif [ -f /etc/redhat-release ]; then
        OS="redhat"
        echo -e "${GREEN}✅ Detected: RedHat/CentOS${NC}"
    else
        echo -e "${YELLOW}⚠️  Unknown OS, assuming Debian-based${NC}"
        OS="debian"
    fi
}

# Function to install dependencies
install_dependencies() {
    echo -e "${BLUE}📦 Installing server dependencies...${NC}"
    
    if [ "$OS" = "debian" ]; then
        apt-get update
        apt-get install -y nginx php$PHP_VERSION-fpm php$PHP_VERSION-mysql php$PHP_VERSION-mbstring \
                          php$PHP_VERSION-xml php$PHP_VERSION-curl php$PHP_VERSION-zip php$PHP_VERSION-gd \
                          php$PHP_VERSION-bcmath php$PHP_VERSION-redis redis-server mysql-client \
                          composer git unzip supervisor certbot python3-certbot-nginx
    elif [ "$OS" = "redhat" ]; then
        yum update -y
        yum install -y nginx php$PHP_VERSION-fpm php$PHP_VERSION-mysql php$PHP_VERSION-mbstring \
                      php$PHP_VERSION-xml php$PHP_VERSION-curl php$PHP_VERSION-zip php$PHP_VERSION-gd \
                      php$PHP_VERSION-bcmath php$PHP_VERSION-redis redis mysql composer git unzip supervisor
    fi
    
    echo -e "${GREEN}✅ Dependencies installed${NC}"
}

# Function to create project directories
create_directories() {
    echo -e "${BLUE}📁 Creating project directories...${NC}"
    
    mkdir -p $PROJECT_PATH
    mkdir -p $PROJECT_PATH/storage/logs
    mkdir -p $PROJECT_PATH/storage/framework/cache
    mkdir -p $PROJECT_PATH/storage/framework/sessions
    mkdir -p $PROJECT_PATH/storage/framework/views
    mkdir -p $PROJECT_PATH/bootstrap/cache
    
    echo -e "${GREEN}✅ Directories created${NC}"
}

# Function to set permissions
set_permissions() {
    echo -e "${BLUE}🔒 Setting file permissions...${NC}"
    
    # Set ownership
    chown -R $SERVER_USER:$SERVER_GROUP $PROJECT_PATH
    
    # Set directory permissions
    find $PROJECT_PATH -type d -exec chmod 755 {} \;
    
    # Set file permissions
    find $PROJECT_PATH -type f -exec chmod 644 {} \;
    
    # Set specific permissions for storage and cache
    chmod -R 775 $PROJECT_PATH/storage
    chmod -R 775 $PROJECT_PATH/bootstrap/cache
    
    # Make artisan executable
    chmod +x $PROJECT_PATH/artisan
    
    echo -e "${GREEN}✅ Permissions set${NC}"
}

# Function to configure PHP-FPM
configure_php_fpm() {
    echo -e "${BLUE}⚙️  Configuring PHP-FPM...${NC}"
    
    # PHP-FPM pool configuration
    cat > /etc/php/$PHP_VERSION/fpm/pool.d/$PROJECT_NAME.conf << EOF
[$PROJECT_NAME]
user = $SERVER_USER
group = $SERVER_GROUP
listen = /var/run/php/php$PHP_VERSION-fpm-$PROJECT_NAME.sock
listen.owner = $SERVER_USER
listen.group = $SERVER_GROUP
listen.mode = 0660

pm = dynamic
pm.max_children = 20
pm.start_servers = 2
pm.min_spare_servers = 1
pm.max_spare_servers = 3

; Security
php_admin_value[disable_functions] = exec,passthru,shell_exec,system
php_admin_flag[allow_url_fopen] = off

; Performance
php_value[memory_limit] = 256M
php_value[max_execution_time] = 300
php_value[upload_max_filesize] = 50M
php_value[post_max_size] = 50M

; Error logging
php_admin_value[error_log] = /var/log/php/$PROJECT_NAME-error.log
php_admin_flag[log_errors] = on
EOF

    # Create log directory
    mkdir -p /var/log/php
    touch /var/log/php/$PROJECT_NAME-error.log
    chown $SERVER_USER:$SERVER_GROUP /var/log/php/$PROJECT_NAME-error.log
    
    echo -e "${GREEN}✅ PHP-FPM configured${NC}"
}

# Function to configure Nginx
configure_nginx() {
    echo -e "${BLUE}🌐 Configuring Nginx...${NC}"
    
    # Copy server configuration
    cp docker/nginx/conf.d/app-server.conf $NGINX_SITES/$PROJECT_NAME
    
    # Update paths in configuration
    sed -i "s|/var/www/html/arrahnu-auction-online|$PROJECT_PATH|g" $NGINX_SITES/$PROJECT_NAME
    sed -i "s|unix:/var/run/php/php8.2-fpm.sock|unix:/var/run/php/php$PHP_VERSION-fpm-$PROJECT_NAME.sock|g" $NGINX_SITES/$PROJECT_NAME
    
    # Enable site
    ln -sf $NGINX_SITES/$PROJECT_NAME $NGINX_ENABLED/$PROJECT_NAME
    
    # Remove default site if exists
    rm -f $NGINX_ENABLED/default
    
    # Test configuration
    nginx -t
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✅ Nginx configuration valid${NC}"
    else
        echo -e "${RED}❌ Nginx configuration error${NC}"
        exit 1
    fi
}

# Function to configure environment
configure_environment() {
    echo -e "${BLUE}⚙️  Configuring environment...${NC}"
    
    # Copy production environment file
    cp .env-server-production $PROJECT_PATH/.env
    
    # Generate application key if not set
    cd $PROJECT_PATH
    if ! grep -q "APP_KEY=base64:" .env; then
        php artisan key:generate --force
    fi
    
    echo -e "${GREEN}✅ Environment configured${NC}"
}

# Function to install composer dependencies
install_composer() {
    echo -e "${BLUE}📦 Installing Composer dependencies...${NC}"
    
    cd $PROJECT_PATH
    
    # Install production dependencies
    sudo -u $SERVER_USER composer install --no-dev --optimize-autoloader --no-interaction
    
    echo -e "${GREEN}✅ Composer dependencies installed${NC}"
}

# Function to run database migrations
run_migrations() {
    echo -e "${BLUE}🗄️  Running database migrations...${NC}"
    
    cd $PROJECT_PATH
    
    # Run migrations
    sudo -u $SERVER_USER php artisan migrate --force
    
    # Clear caches
    sudo -u $SERVER_USER php artisan config:cache
    sudo -u $SERVER_USER php artisan route:cache
    sudo -u $SERVER_USER php artisan view:cache
    
    echo -e "${GREEN}✅ Database migrations completed${NC}"
}

# Function to configure SSL
configure_ssl() {
    echo -e "${BLUE}🔒 Configuring SSL certificate...${NC}"
    
    # Generate SSL certificate with Let's Encrypt
    certbot --nginx -d arrahnuauction.muamalat.com.my -d www.arrahnuauction.muamalat.com.my --non-interactive --agree-tos --email admin@muamalat.com.my
    
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✅ SSL certificate configured${NC}"
    else
        echo -e "${YELLOW}⚠️  SSL certificate setup failed, continuing without SSL${NC}"
    fi
}

# Function to configure supervisor for queue workers
configure_supervisor() {
    echo -e "${BLUE}⚙️  Configuring Supervisor for queue workers...${NC}"
    
    cat > /etc/supervisor/conf.d/$PROJECT_NAME-worker.conf << EOF
[program:$PROJECT_NAME-worker]
process_name=%(program_name)s_%(process_num)02d
command=php $PROJECT_PATH/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=$SERVER_USER
numprocs=2
redirect_stderr=true
stdout_logfile=$PROJECT_PATH/storage/logs/worker.log
stopwaitsecs=3600
EOF

    supervisorctl reread
    supervisorctl update
    supervisorctl start $PROJECT_NAME-worker:*
    
    echo -e "${GREEN}✅ Supervisor configured${NC}"
}

# Function to restart services
restart_services() {
    echo -e "${BLUE}🔄 Restarting services...${NC}"
    
    systemctl restart php$PHP_VERSION-fpm
    systemctl restart nginx
    systemctl restart redis-server
    
    # Enable services to start on boot
    systemctl enable php$PHP_VERSION-fpm
    systemctl enable nginx
    systemctl enable redis-server
    
    echo -e "${GREEN}✅ Services restarted${NC}"
}

# Function to show deployment status
show_status() {
    echo -e "${BLUE}📊 Deployment Status:${NC}"
    echo ""
    
    # Check services
    if systemctl is-active --quiet nginx; then
        echo -e "${GREEN}✅ Nginx: Running${NC}"
    else
        echo -e "${RED}❌ Nginx: Not running${NC}"
    fi
    
    if systemctl is-active --quiet php$PHP_VERSION-fpm; then
        echo -e "${GREEN}✅ PHP-FPM: Running${NC}"
    else
        echo -e "${RED}❌ PHP-FPM: Not running${NC}"
    fi
    
    if systemctl is-active --quiet redis-server; then
        echo -e "${GREEN}✅ Redis: Running${NC}"
    else
        echo -e "${RED}❌ Redis: Not running${NC}"
    fi
    
    echo ""
    echo -e "${BLUE}🌐 Access Information:${NC}"
    echo "  - Website: https://arrahnuauction.muamalat.com.my"
    echo "  - Project Path: $PROJECT_PATH"
    echo "  - Nginx Config: $NGINX_SITES/$PROJECT_NAME"
    echo "  - PHP-FPM Pool: /etc/php/$PHP_VERSION/fpm/pool.d/$PROJECT_NAME.conf"
    echo "  - Logs: $PROJECT_PATH/storage/logs/"
    echo ""
    echo -e "${GREEN}🎉 Deployment completed successfully!${NC}"
}

# Main execution
case "${1:-full}" in
    full)
        check_root
        detect_environment
        install_dependencies
        create_directories
        set_permissions
        configure_php_fpm
        configure_nginx
        configure_environment
        install_composer
        run_migrations
        configure_ssl
        configure_supervisor
        restart_services
        show_status
        ;;
    dependencies)
        check_root
        detect_environment
        install_dependencies
        ;;
    configure)
        check_root
        configure_php_fpm
        configure_nginx
        configure_environment
        restart_services
        ;;
    permissions)
        check_root
        set_permissions
        ;;
    ssl)
        check_root
        configure_ssl
        ;;
    status)
        show_status
        ;;
    *)
        echo "Usage: $0 {full|dependencies|configure|permissions|ssl|status}"
        echo ""
        echo "Commands:"
        echo "  full         - Complete server deployment"
        echo "  dependencies - Install server dependencies only"
        echo "  configure    - Configure services only"
        echo "  permissions  - Fix file permissions only"
        echo "  ssl          - Configure SSL certificate only"
        echo "  status       - Show deployment status"
        ;;
esac

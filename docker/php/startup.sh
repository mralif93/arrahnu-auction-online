#!/bin/bash

echo "🚀 Starting PHP-FPM Service..."

# Function to check if PHP-FPM is running
check_php_fpm() {
    if [ -f "/tmp/php-fpm.pid" ] && kill -0 $(cat /tmp/php-fpm.pid) 2>/dev/null; then
        return 0
    elif ps aux | grep -v grep | grep "php-fpm" > /dev/null; then
        return 0
    else
        return 1
    fi
}

# Function to start PHP-FPM
start_php_fpm() {
    echo "📋 Starting PHP-FPM..."
    
    # Check if PHP-FPM configuration is valid
    if php-fpm -t; then
        echo "✅ PHP-FPM configuration is valid"
        
        # Start PHP-FPM in foreground
        php-fpm -F &
        PHP_FPM_PID=$!
        
        # Wait for PHP-FPM to start
        sleep 3
        
        if check_php_fpm; then
            echo "✅ PHP-FPM started successfully (PID: $PHP_FPM_PID)"
            return 0
        else
            echo "❌ Failed to start PHP-FPM"
            return 1
        fi
    else
        echo "❌ PHP-FPM configuration has errors"
        return 1
    fi
}

# Function to reload PHP-FPM
reload_php_fpm() {
    echo "🔄 Reloading PHP-FPM..."
    
    if check_php_fpm; then
        # Get PHP-FPM master process PID
        MASTER_PID=$(ps aux | grep "php-fpm: master" | grep -v grep | awk '{print $2}' | head -1)
        if [ ! -z "$MASTER_PID" ]; then
            kill -USR2 $MASTER_PID
            echo "✅ PHP-FPM reload signal sent"
        else
            echo "❌ Could not find PHP-FPM master process"
        fi
    else
        echo "❌ PHP-FPM is not running"
    fi
}

# Function to check PHP-FPM configuration
check_config() {
    echo "🔍 Checking PHP-FPM configuration..."
    php-fpm -t
    if [ $? -eq 0 ]; then
        echo "✅ PHP-FPM configuration is valid"
        return 0
    else
        echo "❌ PHP-FPM configuration has errors"
        return 1
    fi
}

# Function to show PHP-FPM status
show_status() {
    echo "📊 PHP-FPM Status:"
    if check_php_fpm; then
        echo "  - Status: Running"
        echo "  - PID: $(pgrep php-fpm)"
        echo "  - Port: 9000"
        echo "  - Config: /usr/local/etc/php-fpm.d/www.conf"
        echo "  - PHP Version: $(php -v | head -n1)"
    else
        echo "  - Status: Not running"
    fi
}

# Function to show PHP extensions
show_extensions() {
    echo "🔌 PHP Extensions:"
    php -m | sort
}

# Function to show PHP info
show_php_info() {
    echo "📋 PHP Information:"
    php -i | grep -E "(PHP Version|Server API|Configuration File|Loaded Configuration File|PHP SAPI|PHP Extension Build|Zend Extension Build)"
}

# Main execution
case "${1:-start}" in
    start)
        if check_config; then
            start_php_fpm
        else
            exit 1
        fi
        ;;
    reload)
        reload_php_fpm
        ;;
    check)
        check_config
        ;;
    status)
        show_status
        ;;
    extensions)
        show_extensions
        ;;
    info)
        show_php_info
        ;;
    stop)
        echo "🛑 Stopping PHP-FPM..."
        if check_php_fpm; then
            MASTER_PID=$(pgrep -f "php-fpm: master")
            if [ ! -z "$MASTER_PID" ]; then
                kill -QUIT $MASTER_PID
                echo "✅ PHP-FPM stopped"
            fi
        else
            echo "PHP-FPM is not running"
        fi
        ;;
    restart)
        echo "🔄 Restarting PHP-FPM..."
        if check_php_fpm; then
            MASTER_PID=$(pgrep -f "php-fpm: master")
            if [ ! -z "$MASTER_PID" ]; then
                kill -QUIT $MASTER_PID
                sleep 2
            fi
        fi
        start_php_fpm
        ;;
    *)
        echo "Usage: $0 {start|reload|check|status|extensions|info|stop|restart}"
        echo "  start      - Start PHP-FPM service"
        echo "  reload     - Reload configuration"
        echo "  check      - Check configuration validity"
        echo "  status     - Show service status"
        echo "  extensions - Show loaded PHP extensions"
        echo "  info       - Show PHP information"
        echo "  stop       - Stop PHP-FPM service"
        echo "  restart    - Restart PHP-FPM service"
        exit 1
        ;;
esac

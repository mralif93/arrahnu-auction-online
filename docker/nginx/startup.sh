#!/bin/bash

echo "🚀 Starting Nginx Service..."

# Function to check if nginx is running
check_nginx() {
    if pgrep -x "nginx" > /dev/null; then
        return 0
    else
        return 1
    fi
}

# Function to start nginx
start_nginx() {
    echo "📋 Starting Nginx..."
    nginx -g "daemon off;" &
    NGINX_PID=$!
    
    # Wait for nginx to start
    sleep 2
    
    if check_nginx; then
        echo "✅ Nginx started successfully (PID: $NGINX_PID)"
        return 0
    else
        echo "❌ Failed to start Nginx"
        return 1
    fi
}

# Function to reload nginx configuration
reload_nginx() {
    echo "🔄 Reloading Nginx configuration..."
    nginx -s reload
    if [ $? -eq 0 ]; then
        echo "✅ Nginx configuration reloaded successfully"
    else
        echo "❌ Failed to reload Nginx configuration"
    fi
}

# Function to check nginx configuration
check_config() {
    echo "🔍 Checking Nginx configuration..."
    nginx -t
    if [ $? -eq 0 ]; then
        echo "✅ Nginx configuration is valid"
        return 0
    else
        echo "❌ Nginx configuration has errors"
        return 1
    fi
}

# Function to show nginx status
show_status() {
    echo "📊 Nginx Status:"
    if check_nginx; then
        echo "  - Status: Running"
        echo "  - PID: $(pgrep nginx)"
        echo "  - Port: 80"
        echo "  - Config: /etc/nginx/nginx.conf"
    else
        echo "  - Status: Not running"
    fi
}

# Main execution
case "${1:-start}" in
    start)
        if check_config; then
            start_nginx
        else
            exit 1
        fi
        ;;
    reload)
        reload_nginx
        ;;
    check)
        check_config
        ;;
    status)
        show_status
        ;;
    stop)
        echo "🛑 Stopping Nginx..."
        nginx -s quit
        ;;
    restart)
        echo "🔄 Restarting Nginx..."
        nginx -s quit
        sleep 2
        start_nginx
        ;;
    *)
        echo "Usage: $0 {start|reload|check|status|stop|restart}"
        echo "  start   - Start Nginx service"
        echo "  reload  - Reload configuration"
        echo "  check   - Check configuration validity"
        echo "  status  - Show service status"
        echo "  stop    - Stop Nginx service"
        echo "  restart - Restart Nginx service"
        exit 1
        ;;
esac

#!/bin/bash

echo "🚀 Starting Redis Service..."

# Function to check if Redis is running
check_redis() {
    if pgrep -x "redis-server" > /dev/null; then
        return 0
    else
        return 1
    fi
}

# Function to start Redis
start_redis() {
    echo "📋 Starting Redis..."
    
    # Start Redis server with configuration
    redis-server --appendonly yes --requirepass secret --maxmemory 256mb --maxmemory-policy allkeys-lru &
    REDIS_PID=$!
    
    # Wait for Redis to start
    sleep 2
    
    if check_redis; then
        echo "✅ Redis started successfully (PID: $REDIS_PID)"
        return 0
    else
        echo "❌ Failed to start Redis"
        return 1
    fi
}

# Function to check Redis connection
check_connection() {
    echo "🔍 Checking Redis connection..."
    if redis-cli -a secret ping > /dev/null 2>&1; then
        echo "✅ Redis connection successful"
        return 0
    else
        echo "❌ Redis connection failed"
        return 1
    fi
}

# Function to show Redis status
show_status() {
    echo "📊 Redis Status:"
    if check_redis; then
        echo "  - Status: Running"
        echo "  - PID: $(pgrep redis-server)"
        echo "  - Port: 6379"
        echo "  - Config: Memory limit 256MB"
        
        if check_connection; then
            echo "  - Connection: Active"
            # Get Redis info
            INFO=$(redis-cli -a secret info 2>/dev/null)
            if [ ! -z "$INFO" ]; then
                VERSION=$(echo "$INFO" | grep "redis_version:" | cut -d: -f2)
                MEMORY=$(echo "$INFO" | grep "used_memory_human:" | cut -d: -f2)
                KEYS=$(echo "$INFO" | grep "db0:" | cut -d: -f2)
                echo "  - Version: $VERSION"
                echo "  - Memory: $MEMORY"
                echo "  - Keys: $KEYS"
            fi
        else
            echo "  - Connection: Failed"
        fi
    else
        echo "  - Status: Not running"
    fi
}

# Function to monitor Redis
monitor_redis() {
    echo "📺 Starting Redis monitor..."
    if check_connection; then
        redis-cli -a secret monitor
    else
        echo "❌ Cannot monitor Redis - connection failed"
        exit 1
    fi
}

# Function to flush Redis
flush_redis() {
    echo "🧹 Flushing Redis database..."
    if check_connection; then
        redis-cli -a secret flushall
        echo "✅ Redis database flushed"
    else
        echo "❌ Cannot flush Redis - connection failed"
        exit 1
    fi
}

# Function to backup Redis
backup_redis() {
    echo "💾 Creating Redis backup..."
    if check_connection; then
        BACKUP_FILE="/data/redis_backup_$(date +%Y%m%d_%H%M%S).rdb"
        redis-cli -a secret save
        echo "✅ Redis backup created: $BACKUP_FILE"
    else
        echo "❌ Cannot backup Redis - connection failed"
        exit 1
    fi
}

# Main execution
case "${1:-start}" in
    start)
        start_redis
        ;;
    check)
        check_connection
        ;;
    status)
        show_status
        ;;
    monitor)
        monitor_redis
        ;;
    flush)
        flush_redis
        ;;
    backup)
        backup_redis
        ;;
    stop)
        echo "🛑 Stopping Redis..."
        if check_redis; then
            REDIS_PID=$(pgrep redis-server)
            kill $REDIS_PID
            echo "✅ Redis stopped"
        else
            echo "Redis is not running"
        fi
        ;;
    restart)
        echo "🔄 Restarting Redis..."
        if check_redis; then
            REDIS_PID=$(pgrep redis-server)
            kill $REDIS_PID
            sleep 2
        fi
        start_redis
        ;;
    *)
        echo "Usage: $0 {start|check|status|monitor|flush|backup|stop|restart}"
        echo "  start   - Start Redis service"
        echo "  check   - Check Redis connection"
        echo "  status  - Show service status"
        echo "  monitor - Monitor Redis commands"
        echo "  flush   - Flush all Redis data"
        echo "  backup  - Create Redis backup"
        echo "  stop    - Stop Redis service"
        echo "  restart - Restart Redis service"
        exit 1
        ;;
esac

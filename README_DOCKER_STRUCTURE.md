# 🐳 Docker Structure - ArRahnu Auction Online

This document describes the organized Docker structure where each service has its own folder with dedicated configurations and startup scripts.

## 📁 Folder Structure

```
docker/
├── nginx/                    # Nginx service folder
│   ├── Dockerfile           # Nginx container definition
│   ├── nginx.conf           # Main Nginx configuration
│   ├── conf.d/              # Server configurations
│   │   └── app.conf         # Laravel application config
│   └── startup.sh           # Nginx service control script
├── php/                      # PHP-FPM service folder
│   ├── Dockerfile           # PHP container definition
│   ├── php.ini              # PHP configuration
│   └── startup.sh           # PHP-FPM service control script
└── redis/                    # Redis service folder
    └── startup.sh           # Redis service control script
```

## 🚀 Main Startup Script

The main `startup.sh` script provides centralized control for all services:

### **Basic Commands:**
```bash
./startup.sh start          # Start all services
./startup.sh stop           # Stop all services
./startup.sh restart        # Restart all services
./startup.sh status         # Show service status
./startup.sh logs           # View service logs
./startup.sh build          # Build all services
./startup.sh clean          # Clean up containers and volumes
```

### **Individual Service Control:**
```bash
./startup.sh nginx restart  # Restart Nginx only
./startup.sh php status     # Show PHP-FPM status
./startup.sh redis monitor  # Monitor Redis
```

## 🌐 Nginx Service

### **Features:**
- Custom Nginx configuration with security headers
- Gzip compression enabled
- Static file caching
- Health check endpoint at `/health`
- Laravel-specific routing configuration

### **Control Commands:**
```bash
# Inside Nginx container
/usr/local/bin/startup.sh start      # Start Nginx
/usr/local/bin/startup.sh reload     # Reload configuration
/usr/local/bin/startup.sh check      # Check configuration
/usr/local/bin/startup.sh status     # Show status
/usr/local/bin/startup.sh stop       # Stop Nginx
/usr/local/bin/startup.sh restart    # Restart Nginx
```

### **Configuration Files:**
- `nginx.conf` - Main Nginx configuration
- `conf.d/app.conf` - Laravel application server block

## 🐘 PHP-FPM Service

### **Features:**
- PHP 8.2 with FPM
- Redis extension installed
- All required Laravel extensions
- Custom PHP configuration
- Composer pre-installed

### **Control Commands:**
```bash
# Inside PHP container
/usr/local/bin/startup.sh start      # Start PHP-FPM
/usr/local/bin/startup.sh reload     # Reload configuration
/usr/local/bin/startup.sh check      # Check configuration
/usr/local/bin/startup.sh status     # Show status
/usr/local/bin/startup.sh extensions # Show PHP extensions
/usr/local/bin/startup.sh info       # Show PHP information
/usr/local/bin/startup.sh stop       # Stop PHP-FPM
/usr/local/bin/startup.sh restart    # Restart PHP-FPM
```

### **PHP Extensions:**
- pdo_mysql, mbstring, exif, pcntl, bcmath, gd, zip
- redis (for caching and sessions)

## 🔴 Redis Service

### **Features:**
- Redis 7.4 with persistence
- Password protected
- Memory limit: 256MB
- LRU eviction policy
- Health checks enabled

### **Control Commands:**
```bash
# Inside Redis container
/usr/local/bin/startup.sh start      # Start Redis
/usr/local/bin/startup.sh check      # Check connection
/usr/local/bin/startup.sh status     # Show status
/usr/local/bin/startup.sh monitor    # Monitor commands
/usr/local/bin/startup.sh flush      # Flush all data
/usr/local/bin/startup.sh backup     # Create backup
/usr/local/bin/startup.sh stop       # Stop Redis
/usr/local/bin/startup.sh restart    # Restart Redis
```

## 🛠️ Development Workflow

### **1. First Time Setup:**
```bash
# Build and start all services
./startup.sh build
./startup.sh start
```

### **2. Daily Development:**
```bash
# Start services
./startup.sh start

# Check status
./startup.sh status

# View logs
./startup.sh logs

# Stop services
./startup.sh stop
```

### **3. Service Management:**
```bash
# Restart specific service
./startup.sh nginx restart
./startup.sh php reload
./startup.sh redis monitor

# Check individual service status
./startup.sh nginx status
./startup.sh php status
./startup.sh redis status
```

### **4. Troubleshooting:**
```bash
# Rebuild services
./startup.sh build

# Clean up and restart
./startup.sh clean
./startup.sh start

# Check service health
./startup.sh status
```

## 🔧 Configuration

### **Environment Variables:**
All services use the `.env` file for configuration:
- Database settings (Google Cloud SQL)
- Redis settings
- Application settings

### **Ports:**
- **Web Application**: http://localhost:8000
- **Redis**: localhost:6379
- **PHP-FPM**: Internal (9000)

### **Volumes:**
- Application code: `./:/var/www`
- Redis data: `redis_data:/data`

## 📚 Benefits of This Structure

1. **Organization**: Each service has its own folder
2. **Maintainability**: Easy to modify individual services
3. **Control**: Dedicated startup scripts for each service
4. **Flexibility**: Can control services individually or together
5. **Debugging**: Better isolation and troubleshooting
6. **Scalability**: Easy to add new services or modify existing ones

## 🚨 Important Notes

- Always use the startup scripts instead of direct Docker commands
- The main `startup.sh` script provides the recommended workflow
- Individual service scripts are available inside containers for debugging
- Configuration files are copied into containers during build
- Health checks are enabled for all services

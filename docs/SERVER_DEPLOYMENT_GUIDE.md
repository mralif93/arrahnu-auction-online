# 🚀 ArRahnu Auction Online - Server Deployment Guide

## 📋 Overview

This guide covers the complete deployment of the ArRahnu Auction Online application to a production server, including path configurations, security considerations, and optimization for the server environment.

## 🗂️ Server Path Structure

### **Recommended Directory Structure:**
```
/var/www/html/
└── arrahnu-auction-online/
    ├── app/
    ├── bootstrap/
    ├── config/
    ├── database/
    ├── public/          # Web server document root
    ├── resources/
    ├── routes/
    ├── storage/
    │   ├── app/
    │   ├── framework/
    │   │   ├── cache/
    │   │   ├── sessions/
    │   │   └── views/
    │   └── logs/
    ├── vendor/
    ├── .env             # Production environment file
    ├── artisan
    ├── composer.json
    └── composer.lock
```

### **Key Path Considerations:**

1. **Document Root**: `/var/www/html/arrahnu-auction-online/public`
2. **Application Root**: `/var/www/html/arrahnu-auction-online`
3. **Storage Path**: `/var/www/html/arrahnu-auction-online/storage`
4. **Log Files**: `/var/www/html/arrahnu-auction-online/storage/logs`

## 🌐 Web Server Configuration

### **Nginx Configuration (Production)**

The server uses a dedicated Nginx configuration (`app-server.conf`) with:

- **SSL/TLS termination** with Let's Encrypt certificates
- **HTTP to HTTPS redirection**
- **Security headers** and CSP policies
- **Rate limiting** for API and login endpoints
- **Gzip compression** for better performance
- **Static file caching** with proper cache headers

### **PHP-FPM Configuration**

- **Dedicated PHP-FPM pool** for the application
- **Unix socket communication** for better performance
- **Security restrictions** (disabled dangerous functions)
- **Memory and execution limits** optimized for production
- **Error logging** to dedicated log files

## 🔧 Deployment Process

### **1. Automated Deployment**

Use the provided deployment script:

```bash
# Complete deployment (recommended for first-time setup)
sudo ./deploy-server.sh full

# Individual components
sudo ./deploy-server.sh dependencies    # Install dependencies only
sudo ./deploy-server.sh configure      # Configure services only
sudo ./deploy-server.sh ssl           # Configure SSL only
sudo ./deploy-server.sh status        # Check deployment status
```

### **2. Manual Deployment Steps**

If you prefer manual deployment:

#### **A. Server Preparation**
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install -y nginx php8.2-fpm php8.2-mysql php8.2-mbstring \
                    php8.2-xml php8.2-curl php8.2-zip php8.2-gd \
                    php8.2-bcmath php8.2-redis redis-server \
                    mysql-client composer git unzip supervisor
```

#### **B. Application Setup**
```bash
# Create project directory
sudo mkdir -p /var/www/html/arrahnu-auction-online

# Set ownership
sudo chown -R www-data:www-data /var/www/html/arrahnu-auction-online

# Copy application files
# (Upload your application files here)

# Set permissions
sudo find /var/www/html/arrahnu-auction-online -type d -exec chmod 755 {} \;
sudo find /var/www/html/arrahnu-auction-online -type f -exec chmod 644 {} \;
sudo chmod -R 775 /var/www/html/arrahnu-auction-online/storage
sudo chmod -R 775 /var/www/html/arrahnu-auction-online/bootstrap/cache
sudo chmod +x /var/www/html/arrahnu-auction-online/artisan
```

#### **C. Environment Configuration**
```bash
# Copy production environment file
cd /var/www/html/arrahnu-auction-online
sudo cp .env-server-production .env

# Generate application key
sudo -u www-data php artisan key:generate --force

# Install dependencies
sudo -u www-data composer install --no-dev --optimize-autoloader

# Run migrations
sudo -u www-data php artisan migrate --force

# Cache configuration
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
```

## 🔒 Security Considerations

### **1. File Permissions**
```bash
# Application files: 644
# Directories: 755
# Storage and cache: 775
# Executable files: 755

# Secure sensitive files
sudo chmod 600 /var/www/html/arrahnu-auction-online/.env
sudo chown root:root /var/www/html/arrahnu-auction-online/.env
```

### **2. Nginx Security**
- SSL/TLS with strong ciphers
- Security headers (HSTS, CSP, etc.)
- Rate limiting for login and API endpoints
- Blocked access to sensitive files (.env, .git, etc.)
- File upload size limits

### **3. PHP Security**
- Disabled dangerous functions (exec, shell_exec, etc.)
- URL fopen disabled
- Error display disabled in production
- Secure session handling

## 📊 Performance Optimization

### **1. Nginx Optimizations**
- **Gzip compression** for text files
- **Static file caching** with long expiry
- **FastCGI caching** for PHP responses
- **HTTP/2 support** for better performance

### **2. PHP Optimizations**
- **OPcache enabled** with optimized settings
- **Composer optimizations** (--optimize-autoloader)
- **Laravel optimizations** (config, route, view caching)

### **3. Database Optimizations**
- **Connection pooling** via PHP-FPM
- **Query caching** enabled
- **Index optimization** for frequent queries

## 🔍 Monitoring and Logging

### **1. Log Files**
```bash
# Application logs
/var/www/html/arrahnu-auction-online/storage/logs/laravel.log

# Nginx logs
/var/log/nginx/arrahnu.access.log
/var/log/nginx/arrahnu.error.log

# PHP-FPM logs
/var/log/php/arrahnu-auction-online-error.log

# System logs
/var/log/syslog
```

### **2. Monitoring Endpoints**
- **Health check**: `https://arrahnuauction.muamalat.com.my/health`
- **Status check**: `https://arrahnuauction.muamalat.com.my/status`

### **3. Queue Workers**
Supervisor manages queue workers for background processing:
```bash
# Check worker status
sudo supervisorctl status arrahnu-auction-online-worker:*

# Restart workers
sudo supervisorctl restart arrahnu-auction-online-worker:*
```

## 🚨 Troubleshooting

### **Common Issues and Solutions**

#### **1. Permission Errors**
```bash
# Fix ownership
sudo chown -R www-data:www-data /var/www/html/arrahnu-auction-online

# Fix permissions
sudo chmod -R 775 /var/www/html/arrahnu-auction-online/storage
sudo chmod -R 775 /var/www/html/arrahnu-auction-online/bootstrap/cache
```

#### **2. 500 Internal Server Error**
```bash
# Check PHP-FPM logs
sudo tail -f /var/log/php/arrahnu-auction-online-error.log

# Check Laravel logs
sudo tail -f /var/www/html/arrahnu-auction-online/storage/logs/laravel.log

# Check Nginx error logs
sudo tail -f /var/log/nginx/arrahnu.error.log
```

#### **3. Database Connection Issues**
```bash
# Test database connection
cd /var/www/html/arrahnu-auction-online
sudo -u www-data php artisan tinker
# In tinker: DB::connection()->getPdo();
```

#### **4. Redis Connection Issues**
```bash
# Check Redis status
sudo systemctl status redis-server

# Test Redis connection
redis-cli ping
```

## 🔄 Maintenance

### **Regular Maintenance Tasks**

#### **1. Log Rotation**
```bash
# Laravel logs (add to crontab)
0 2 * * * cd /var/www/html/arrahnu-auction-online && php artisan log:clear

# Nginx logs (logrotate handles this automatically)
```

#### **2. Cache Clearing**
```bash
# Clear application caches
cd /var/www/html/arrahnu-auction-online
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan route:clear
sudo -u www-data php artisan view:clear
```

#### **3. SSL Certificate Renewal**
```bash
# Automatic renewal (cron job)
0 12 * * * /usr/bin/certbot renew --quiet
```

## 📁 Environment Files

### **Available Environment Templates:**

1. **`.env-local-development`** - Local Docker development
2. **`.env-server-production`** - Server production deployment
3. **`.env-docker-basic`** - Basic Docker setup

### **Key Configuration Differences:**

| Setting | Local Development | Server Production |
|---------|------------------|-------------------|
| `APP_ENV` | `local` | `production` |
| `APP_DEBUG` | `true` | `false` |
| `APP_URL` | `http://localhost:8000` | `https://arrahnuauction.muamalat.com.my` |
| `FORCE_HTTPS` | `false` | `true` |
| `SESSION_SECURE_COOKIE` | `false` | `true` |
| `REDIS_HOST` | `redis` (Docker) | `localhost` (Server) |
| `MAIL_MAILER` | `log` | `smtp` |

## 🎯 Quick Commands

### **Environment Management**
```bash
# Show available templates
./organize-env.sh templates

# Switch to production environment
cp .env-server-production .env

# Validate environment
./organize-env.sh validate
```

### **Service Management**
```bash
# Restart all services
sudo systemctl restart nginx php8.2-fpm redis-server

# Check service status
sudo systemctl status nginx php8.2-fpm redis-server

# View service logs
sudo journalctl -u nginx -f
sudo journalctl -u php8.2-fpm -f
```

### **Application Management**
```bash
# Deploy updates
cd /var/www/html/arrahnu-auction-online
sudo -u www-data composer install --no-dev --optimize-autoloader
sudo -u www-data php artisan migrate --force
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
sudo systemctl restart php8.2-fpm
```

---

## 📞 Support

For deployment issues or questions:
- Check logs in `/var/www/html/arrahnu-auction-online/storage/logs/`
- Review Nginx error logs: `/var/log/nginx/arrahnu.error.log`
- Monitor system resources: `htop`, `df -h`, `free -m`

This comprehensive guide ensures your ArRahnu Auction Online application is properly deployed with optimal security, performance, and maintainability on your production server! 🚀

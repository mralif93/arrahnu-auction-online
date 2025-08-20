# ArRahnu Auction Online - Docker Deployment Guide

## Overview
This guide provides step-by-step instructions for deploying the ArRahnu Auction Online system using Docker containers. The system is configured to run on the domain `https://arrahnuauction.muamalat.com.my/`.

## Prerequisites
- Docker Engine 20.10+
- Docker Compose 2.0+
- At least 4GB RAM available
- At least 20GB disk space
- SSL certificates for the domain

## System Architecture
The Docker setup includes the following services:
- **PHP Application** (Laravel 10)
- **Nginx Web Server** (with SSL)
- **MySQL Database** (8.0)
- **Redis Cache** (7-alpine)
- **Queue Worker** (Laravel Queue)
- **Scheduler** (Laravel Task Scheduler)

## Quick Start

### 1. Clone and Setup
```bash
git clone <repository-url>
cd arrahnu-auction-online
```

### 2. Environment Configuration
```bash
# Copy the Docker environment file
cp docker.env .env

# Generate application key
docker run --rm -v $(pwd):/app -w /app composer:latest composer install --no-dev --optimize-autoloader
docker run --rm -v $(pwd):/app -w /app php:8.2-cli php artisan key:generate
```

### 3. SSL Certificates
Place your SSL certificates in the `ssl/` directory:
```bash
mkdir -p ssl
# Copy your certificates:
# - arrahnuauction.muamalat.com.my.crt
# - arrahnuauction.muamalat.com.my.key
```

### 4. Build and Start Services
```bash
# Build all services
docker-compose build

# Start all services
docker-compose up -d

# Check service status
docker-compose ps
```

### 5. Database Setup
```bash
# Run migrations
docker-compose exec app php artisan migrate --force

# Seed the database
docker-compose exec app php artisan db:seed --force

# Create admin user
docker-compose exec app php artisan make:admin
```

## Service Configuration

### PHP Application (`app`)
- **Port**: 9000 (internal)
- **Extensions**: MySQL, Redis, GD, ZIP, Intl, OpCache
- **Memory**: 512M
- **Upload Limit**: 100MB

### Nginx Web Server (`nginx`)
- **Ports**: 80 (HTTP), 443 (HTTPS)
- **SSL**: TLS 1.2/1.3
- **Rate Limiting**: API (10 req/s), Login (5 req/min)
- **Security Headers**: HSTS, XSS Protection, CSRF Protection

### MySQL Database (`db`)
- **Port**: 3306
- **Database**: `arrahnu_auction`
- **User**: `arrahnu_user`
- **Password**: `arrahnu_password_2024`

### Redis Cache (`redis`)
- **Port**: 6379
- **Purpose**: Sessions, Cache, Queue

## Environment Variables

### Required Variables
- `APP_KEY`: Laravel application key
- `DB_PASSWORD`: Database password
- `MAIL_PASSWORD`: SMTP password

### Optional Variables
- `APP_DEBUG`: Set to `false` for production
- `LOG_LEVEL`: Logging level (default: warning)
- `REDIS_PASSWORD`: Redis password if needed

## SSL Configuration

### Production SSL
1. Obtain SSL certificates from your CA
2. Place certificates in `ssl/` directory
3. Update domain in `nginx/default.conf`
4. Restart Nginx service

### Development SSL (Self-signed)
The startup script automatically creates self-signed certificates if none exist.

## Security Features

### Rate Limiting
- **API Endpoints**: 10 requests per second
- **Login/Register**: 5 requests per minute
- **Admin Panel**: 10 requests per second

### Security Headers
- HSTS (HTTP Strict Transport Security)
- XSS Protection
- Content Security Policy
- Frame Options

### File Access Control
- Denied access to `.env`, `.log`, `.sql` files
- Protected storage and cache directories
- Secure cookie settings

## Monitoring and Health Checks

### Health Endpoints
- **Application**: `/health`
- **Database**: Built-in connection check
- **Redis**: Connection verification

### Logs
```bash
# View all logs
docker-compose logs

# View specific service logs
docker-compose logs nginx
docker-compose logs app
docker-compose logs db
```

## Backup and Maintenance

### Database Backup
```bash
# Create backup
docker-compose exec db mysqldump -u root -p arrahnu_auction > backup.sql

# Restore backup
docker-compose exec -T db mysql -u root -p arrahnu_auction < backup.sql
```

### Application Updates
```bash
# Pull latest code
git pull origin main

# Rebuild and restart
docker-compose down
docker-compose build --no-cache
docker-compose up -d

# Run migrations
docker-compose exec app php artisan migrate --force
```

## Troubleshooting

### Common Issues

#### 1. Permission Denied
```bash
# Fix storage permissions
docker-compose exec app chown -R www:www storage/
docker-compose exec app chmod -R 775 storage/
```

#### 2. Database Connection Failed
```bash
# Check database service
docker-compose ps db
docker-compose logs db

# Wait for database to be ready
docker-compose exec app php artisan db:show
```

#### 3. SSL Certificate Errors
```bash
# Check certificate files
ls -la ssl/

# Verify Nginx configuration
docker-compose exec nginx nginx -t
```

#### 4. Memory Issues
```bash
# Check resource usage
docker stats

# Increase memory limits in docker-compose.yaml
```

### Performance Optimization

#### 1. Enable OpCache
PHP OpCache is enabled by default with:
- Memory: 128MB
- Max Files: 4000
- Revalidation: Every 2 seconds

#### 2. Redis Optimization
- Session storage
- Cache driver
- Queue processing

#### 3. Nginx Optimization
- Gzip compression
- Static file caching
- Connection pooling

## Production Deployment

### 1. Environment Setup
```bash
# Set production environment
export APP_ENV=production
export APP_DEBUG=false
```

### 2. SSL Certificates
- Use Let's Encrypt or commercial CA
- Enable HSTS
- Configure automatic renewal

### 3. Monitoring
- Set up log aggregation
- Configure alerting
- Monitor resource usage

### 4. Backup Strategy
- Daily database backups
- Weekly application backups
- Off-site storage

## Support and Maintenance

### Regular Tasks
- **Daily**: Check service status and logs
- **Weekly**: Review performance metrics
- **Monthly**: Security updates and patches
- **Quarterly**: Full system backup and testing

### Contact Information
For technical support and maintenance:
- **Email**: support@arrahnuauction.muamalat.com.my
- **Documentation**: [Project Documentation](docs/)
- **Issues**: [GitHub Issues](https://github.com/your-repo/issues)

## License
This deployment configuration is part of the ArRahnu Auction Online system and follows the same licensing terms as the main project.

# ArRahnu Auction Online - Simple Docker Setup

## Overview
This is a simplified Docker setup for the ArRahnu Auction Online system running on `https://arrahnuauction.muamalat.com.my/`.

## Quick Start

### 1. Prerequisites
- Docker Engine
- Docker Compose

### 2. Deploy
```bash
# Deploy everything
./deploy.sh

# Check status
./deploy.sh status

# View logs
./deploy.sh logs
```

### 3. SSL Setup
```bash
# Setup SSL certificates
./setup-ssl.sh
```

## Services

- **PHP App**: Laravel application
- **Nginx**: Web server with SSL
- **MySQL**: Database

## Commands

```bash
# Start services
docker-compose up -d

# Stop services
docker-compose down

# Restart services
docker-compose restart

# View logs
docker-compose logs
```

## Files

- `docker-compose.yaml` - Main Docker configuration
- `nginx/` - Nginx configuration files
- `php/` - PHP configuration files
- `deploy.sh` - Simple deployment script
- `setup-ssl.sh` - SSL certificate setup
- `docker.env` - Environment variables

## Domain
Configured for: `https://arrahnuauction.muamalat.com.my/`

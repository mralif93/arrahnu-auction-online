# ArRahnu Auction Online - Docker Setup Guide

## 🏠 **Local Development (localhost)**

### **Quick Start**
```bash
# Deploy local development environment
./deploy-local.sh
```

### **Configuration**
- **File**: `docker-compose.local.yaml`
- **Domain**: `localhost:8080`
- **Protocol**: HTTP only
- **Environment**: `docker.env.local`
- **Debug Mode**: Enabled
- **Mail**: Logged to files

### **Services**
- **Web**: http://localhost:8080
- **API**: http://localhost:8080/api
- **Admin**: http://localhost:8080/admin
- **Database**: localhost:3307

### **Features**
- ✅ Fast development setup
- ✅ No SSL required
- ✅ Debug mode enabled
- ✅ Local database
- ✅ File-based sessions

---

## 🌐 **Production Deployment**

### **Quick Start**
```bash
# Deploy production environment
./deploy.sh

# Setup SSL certificates
./setup-ssl.sh
```

### **Configuration**
- **File**: `docker-compose.yaml`
- **Domain**: `arrahnuauction.muamalat.com.my`
- **Protocol**: HTTPS with SSL
- **Environment**: `docker.env`
- **Debug Mode**: Disabled
- **Mail**: SMTP configuration

### **Services**
- **Web**: https://arrahnuauction.muamalat.com.my
- **API**: https://arrahnuauction.muamalat.com.my/api
- **Admin**: https://arrahnuauction.muamalat.com.my/admin
- **Database**: Internal network only

### **Features**
- ✅ Production-ready SSL
- ✅ Security headers
- ✅ Rate limiting
- ✅ Production database
- ✅ Redis sessions (optional)

---

## 📁 **File Structure**

```
├── docker-compose.yaml          # Production configuration
├── docker-compose.local.yaml    # Local development
├── docker.env                   # Production environment
├── docker.env.local            # Local environment
├── deploy.sh                   # Production deployment
├── deploy-local.sh             # Local deployment
├── nginx/
│   ├── default.conf            # Production SSL config
│   └── local.conf              # Local HTTP config
└── README_DOCKER_SETUP.md      # This file
```

---

## 🚀 **Usage Examples**

### **Local Development**
```bash
# Start local environment
./deploy-local.sh

# View logs
docker-compose -f docker-compose.local.yaml logs

# Stop services
docker-compose -f docker-compose.local.yaml down
```

### **Production**
```bash
# Deploy production
./deploy.sh

# Setup SSL
./setup-ssl.sh

# View logs
docker-compose logs

# Stop services
docker-compose down
```

---

## 🔧 **Environment Variables**

### **Local (.env from docker.env.local)**
```bash
APP_URL=http://localhost:8080
APP_ENV=local
APP_DEBUG=true
DB_HOST=db
```

### **Production (.env from docker.env)**
```bash
APP_URL=https://arrahnuauction.muamalat.com.my
APP_ENV=production
APP_DEBUG=false
DB_HOST=db
```

---

## 💡 **Tips**

1. **Use local setup** for development and testing
2. **Use production setup** for staging and production servers
3. **Never commit** `.env` files to version control
4. **Test locally** before deploying to production
5. **Use different ports** for local vs production to avoid conflicts

---

## 🆘 **Troubleshooting**

### **Local Issues**
```bash
# Rebuild containers
docker-compose -f docker-compose.local.yaml down
docker-compose -f docker-compose.local.yaml up -d --build

# Check logs
docker-compose -f docker-compose.local.yaml logs
```

### **Production Issues**
```bash
# Check SSL certificates
./setup-ssl.sh test

# Restart services
docker-compose restart

# Check logs
docker-compose logs
```

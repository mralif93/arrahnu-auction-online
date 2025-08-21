#!/bin/bash

echo "🌐 Deploying Domain-Specific Configuration to Fix DNS Issue..."

# Check if running as root
if [ "$EUID" -ne 0 ]; then
    echo "❌ Please run as root (use sudo)"
    exit 1
fi

# Navigate to project directory
cd /opt/arrahnu/bm-gc-repo-arrahnu-stg/ArRahnu_02

echo "📁 Current directory: $(pwd)"

# Check if SSL certificates exist
if [ ! -f "nginx/certs/domain.pem" ] || [ ! -f "nginx/certs/domain.rsa" ]; then
    echo "❌ SSL certificates not found in nginx/certs/"
    echo "   Expected: domain.pem and domain.rsa"
    echo "   Current files:"
    ls -la nginx/certs/
    exit 1
fi

echo "✅ SSL certificates found:"
ls -la nginx/certs/

# Check if Docker containers are running
echo "🐳 Checking Docker containers..."
if ! docker ps | grep -q "arrahnu_02-nginx-1"; then
    echo "❌ Nginx container is not running"
    echo "   Starting containers..."
    docker compose up -d
    sleep 5
fi

# Copy updated nginx configuration to the container
echo "📋 Copying domain-specific nginx configuration to Docker container..."
docker cp nginx/default.conf arrahnu_02-nginx-1:/etc/nginx/conf.d/default.conf

# Test nginx configuration inside the container
echo "🧪 Testing nginx configuration inside container..."
if docker exec arrahnu_02-nginx-1 nginx -t; then
    echo "✅ Nginx configuration is valid"
else
    echo "❌ Nginx configuration test failed"
    exit 1
fi

# Reload nginx inside the container
echo "🔄 Reloading nginx inside container..."
docker exec arrahnu_02-nginx-1 nginx -s reload

echo "✅ Domain-specific configuration deployed successfully!"
echo ""
echo "🌐 Your site should now be accessible at:"
echo "   HTTP:  http://arrahnuauction.sit.muamalat.com.my (redirects to HTTPS)"
echo "   HTTPS: https://arrahnuauction.sit.muamalat.com.my"
echo ""
echo "🔧 What was fixed:"
echo "   - Changed server_name from catch-all (_) to specific domain"
echo "   - DNS resolution should now work properly"
echo "   - No more DNS_PROBE_FINISHED_NXDOMAIN errors"
echo ""
echo "📊 Check container status: docker ps"
echo "📝 View nginx logs: docker logs arrahnu_02-nginx-1"
echo ""
echo "🚀 If you need to restart all services:"
echo "   docker compose down"
echo "   docker compose up -d"
echo ""
echo "💡 Note: Make sure your DNS points to this server's IP address"

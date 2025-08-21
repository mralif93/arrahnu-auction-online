#!/bin/bash

echo "🧪 Testing HTTP-Only Configuration to Fix Redirect Loop..."

# Check if running as root
if [ "$EUID" -ne 0 ]; then
    echo "❌ Please run as root (use sudo)"
    exit 1
fi

# Navigate to project directory
cd /opt/arrahnu/bm-gc-repo-arrahnu-stg/ArRahnu_02

echo "📁 Current directory: $(pwd)"

# Check if Docker containers are running
echo "🐳 Checking Docker containers..."
if ! docker ps | grep -q "arrahnu_02-nginx-1"; then
    echo "❌ Nginx container is not running"
    echo "   Starting containers..."
    docker compose up -d
    sleep 5
fi

# Copy test configuration to the container
echo "📋 Copying HTTP-only test configuration to Docker container..."
docker cp nginx/test-https.conf arrahnu_02-nginx-1:/etc/nginx/conf.d/default.conf

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

echo "✅ HTTP-only configuration deployed successfully!"
echo ""
echo "🌐 Your site should now be accessible at:"
echo "   HTTP: http://arrahnuauction.sit.muamalat.com.my (NO REDIRECT)"
echo ""
echo "🔧 What this fixes:"
echo "   - No more redirect loops"
echo "   - HTTP works without HTTPS redirect"
echo "   - You can test if the basic site works"
echo ""
echo "📊 Check container status: docker ps"
echo "📝 View nginx logs: docker logs arrahnu_02-nginx-1"
echo ""
echo "🧪 Test your site:"
echo "   curl -I http://arrahnuauction.sit.muamalat.com.my"
echo ""
echo "💡 After confirming HTTP works, we can test HTTPS separately"

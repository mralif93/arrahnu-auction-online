#!/bin/bash

echo "🔐 Deploying SSL Configuration for ArRahnu Auction..."

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
    exit 1
fi

echo "✅ SSL certificates found"

# Copy nginx configuration
echo "📋 Copying nginx configuration..."
cp nginx/default.conf /etc/nginx/conf.d/

# Test nginx configuration
echo "🧪 Testing nginx configuration..."
if nginx -t; then
    echo "✅ Nginx configuration is valid"
else
    echo "❌ Nginx configuration test failed"
    exit 1
fi

# Reload nginx
echo "🔄 Reloading nginx..."
nginx -s reload

echo "✅ SSL configuration deployed successfully!"
echo ""
echo "🌐 Your site should now be accessible at:"
echo "   HTTP:  http://arrahnuauction.sit.muamalat.com.my"
echo "   HTTPS: https://arrahnuauction.sit.muamalat.com.my"
echo ""
echo "📊 Check nginx status: systemctl status nginx"
echo "📝 View logs: tail -f /var/log/nginx/error.log"

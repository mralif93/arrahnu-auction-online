#!/bin/bash

echo "🌐 Fixing Web Application - Removing HTTPS Redirect Loop..."

# Check if running as root
if [ "$EUID" -ne 0 ]; then
    echo "❌ Please run as root (use sudo)"
    exit 1
fi

# Navigate to project directory
cd /opt/arrahnu/bm-gc-repo-arrahnu-stg/ArRahnu_02

echo "📁 Current directory: $(pwd)"

# Copy updated nginx configuration
echo "📋 Copying updated nginx configuration (HTTP only)..."
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

echo "✅ Web application fixed! Redirect loop removed."
echo ""
echo "🌐 Your site should now be accessible at:"
echo "   HTTP: http://arrahnuauction.sit.muamalat.com.my"
echo ""
echo "📊 Check nginx status: systemctl status nginx"
echo "📝 View logs: tail -f /var/log/nginx/error.log"
echo ""
echo "🚀 To restart Docker services:"
echo "   docker compose down"
echo "   docker compose up -d"
echo ""
echo "💡 Note: HTTPS is temporarily disabled to fix the redirect loop."
echo "   We can re-enable it later when SSL is properly configured."

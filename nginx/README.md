# Nginx Configuration Guide

This directory contains nginx configuration files for different environments.

## Configuration Files

### `nginx.conf`
Main nginx configuration file with global settings including SSL/TLS configurations.

### `default.conf`
**Catch-all configuration** that works with any domain/IP address.
- Uses `server_name _;` (catch-all)
- Good for development/testing when you don't have a specific domain
- **Note:** This requires SSL certificates to be present in `/etc/nginx/certs/`.

### `local.conf`
Local development configuration (HTTP only, no SSL required).
Use this for local development environments.

### `production.conf`
Production server configuration with full TLS support.
Use this on production servers where SSL certificates are available.

### `domain-specific.conf`
**Template for specific domains** - replace `example.com` with your actual domain.
Use this when you have a specific domain name and want to restrict access.

## DNS Issue Fix

If you're getting `DNS_PROBE_FINISHED_NXDOMAIN` error:

### Option 1: Use `default.conf` (Recommended for testing)
- Works with any domain/IP
- Use `server_name _;` (catch-all)
- Access via IP address or any domain pointing to your server

### Option 2: Use `domain-specific.conf` (For production)
- Replace `example.com` with your actual domain
- Copy to `domain-specific.conf` and rename to your domain
- Ensure DNS points to your server

## SSL Certificate Setup

### For Production/Server:
1. Place your SSL certificates in the `nginx/certs/` directory:
   - `domain.pem` - Your SSL certificate file
   - `domain.rsa` - Your private key file

2. Choose your configuration:
   - **Testing/Development**: Use `default.conf`
   - **Production with domain**: Use `domain-specific.conf` (rename and update domain)

### For Local Development:
1. Use `local.conf` which doesn't require SSL certificates.
2. The server will run on HTTP only (port 80).

## Deployment

### Local Development:
```bash
# Copy local.conf to your nginx conf.d directory
cp nginx/local.conf /etc/nginx/conf.d/
```

### Production Server (Testing):
```bash
# Copy default.conf for catch-all configuration
cp nginx/default.conf /etc/nginx/conf.d/

# Ensure SSL certificates are in place
ls -la /etc/nginx/certs/
# Should show: domain.pem and domain.rsa
```

### Production Server (Specific Domain):
```bash
# Copy and customize domain-specific.conf
cp nginx/domain-specific.conf /etc/nginx/conf.d/your-domain.conf
# Edit the file and replace 'example.com' with your actual domain
```

## SSL Certificate Paths

The configuration expects SSL certificates at:
- Certificate: `/etc/nginx/certs/domain.pem`
- Private Key: `/etc/nginx/certs/domain.rsa`

## Security Features

- HTTP to HTTPS redirect
- Modern TLS protocols (TLS 1.2 and 1.3)
- Strong cipher suites
- Security headers (HSTS, X-Frame-Options, etc.)
- Gzip compression
- Static file caching

## Testing

After configuration changes:
```bash
# Test nginx configuration
nginx -t

# Reload nginx
nginx -s reload
```

## Quick Fix for DNS Issue

If you're still getting DNS errors:

1. **Use `default.conf`** - it works with any domain/IP
2. **Access via IP address** instead of domain name
3. **Check your server's IP** and access directly: `http://YOUR_SERVER_IP`
4. **Ensure nginx is running** and listening on the correct ports

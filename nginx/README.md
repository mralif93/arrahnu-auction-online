# Nginx Configuration Guide

This directory contains nginx configuration files for different environments.

## Configuration Files

### `nginx.conf`
Main nginx configuration file with global settings including SSL/TLS configurations.

### `default.conf`
Default configuration that redirects HTTP to HTTPS and includes TLS support.
**Note:** This requires SSL certificates to be present in `/etc/nginx/certs/`.

### `local.conf`
Local development configuration (HTTP only, no SSL required).
Use this for local development environments.

### `production.conf`
Production server configuration with full TLS support.
Use this on production servers where SSL certificates are available.

## SSL Certificate Setup

### For Production/Server:
1. Place your SSL certificates in the `nginx/certs/` directory:
   - `server.crt` - Your SSL certificate file
   - `server.key` - Your private key file

2. Use either `default.conf` or `production.conf` depending on your needs.

### For Local Development:
1. Use `local.conf` which doesn't require SSL certificates.
2. The server will run on HTTP only (port 80).

## Deployment

### Local Development:
```bash
# Copy local.conf to your nginx conf.d directory
cp nginx/local.conf /etc/nginx/conf.d/
```

### Production Server:
```bash
# Copy production.conf to your nginx conf.d directory
cp nginx/production.conf /etc/nginx/conf.d/

# Ensure SSL certificates are in place
ls -la /etc/nginx/certs/
# Should show: server.crt and server.key
```

## SSL Certificate Paths

The configuration expects SSL certificates at:
- Certificate: `/etc/nginx/certs/server.crt`
- Private Key: `/etc/nginx/certs/server.key`

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

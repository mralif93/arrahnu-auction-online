FROM nginx:1.25-alpine

# Install OpenSSL for certificate generation
RUN apk add --no-cache openssl

# Create SSL directory
RUN mkdir -p /etc/nginx/ssl

# Copy custom configuration
COPY nginx.conf /etc/nginx/nginx.conf
COPY default.conf /etc/nginx/conf.d/default.conf

# Create startup script
COPY startup.sh /startup.sh
RUN chmod +x /startup.sh

# Expose ports
EXPOSE 80 443

# Start Nginx
CMD ["/startup.sh"]

FROM nginx:alpine

# Copy custom nginx configuration
COPY nginx.conf /etc/nginx/nginx.conf
COPY conf.d/ /etc/nginx/conf.d/

# Create necessary directories
RUN mkdir -p /var/log/nginx /var/cache/nginx

# Set proper permissions
RUN chown -R nginx:nginx /var/log/nginx /var/cache/nginx

# Expose port 80
EXPOSE 80

# Copy startup script
COPY startup.sh /usr/local/bin/startup.sh
RUN chmod +x /usr/local/bin/startup.sh

# Start nginx directly (simplified for now)
CMD ["nginx", "-g", "daemon off;"]

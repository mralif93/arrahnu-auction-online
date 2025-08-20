FROM nginx:stable

# Remove default configurations
RUN rm /etc/nginx/conf.d/default.conf
RUN rm /etc/nginx/nginx.conf

# Copy custom configurations
COPY nginx/default.conf /etc/nginx/conf.d/default.conf
COPY nginx/nginx.conf /etc/nginx/nginx.conf

# Create startup script to fix permissions
COPY nginx/start-nginx.sh /start-nginx.sh
RUN chmod +x /start-nginx.sh

# Use custom startup script
CMD ["/startup.sh"]



FROM nginx:stable

# Remove default configurations
RUN rm /etc/nginx/conf.d/default.conf
RUN rm /etc/nginx/nginx.conf

# Copy custom configurations
COPY nginx/default.conf /etc/nginx/conf.d/default.conf
COPY nginx/nginx.conf /etc/nginx/nginx.conf

# Create startup script to fix permissions
COPY nginx/startup.sh /startup.sh
RUN chmod +x /startup.sh

# Use custom startup script
CMD ["/startup.sh"]



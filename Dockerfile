FROM webdevops/php-nginx:8.3

# Set working directory
WORKDIR /app

# Configure Nginx to point to Laravel's public folder
ENV WEB_DOCUMENT_ROOT=/app/public

# Copy all project files to the container
COPY . /app

# Install Laravel dependencies for production
RUN composer install --no-dev --optimize-autoloader

# Give permissions to the application user
RUN chown -R application:application /app/storage /app/bootstrap/cache

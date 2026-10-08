FROM webdevops/php-nginx:8.3

# Set working directory
WORKDIR /app

# Configure Nginx to point to Laravel's public folder
ENV WEB_DOCUMENT_ROOT=/app/public

# Increase upload limits
ENV PHP_UPLOAD_MAX_FILESIZE="10M"
ENV PHP_POST_MAX_SIZE="12M"

# Copy all project files to the container
COPY . /app

# Install Laravel dependencies for production
RUN composer install --no-dev --optimize-autoloader

# Give permissions to the application user
RUN chown -R application:application /app/storage /app/bootstrap/cache


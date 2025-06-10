FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    netcat-openbsd \
    nginx

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy composer files first to leverage Docker cache
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader

# Copy package files for frontend
COPY package.json package-lock.json ./
RUN npm install
RUN npm install terser --save-dev

# Copy the rest of the application
COPY . .

# Generate autoload files
RUN composer dump-autoload --optimize

# Build frontend assets
RUN npm run build

# Set permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Configure Nginx
RUN echo "server {\n\
    listen 80;\n\
    server_name _;\n\
    root /var/www/public;\n\
    index index.php;\n\
    \n\
    # Logging\n\
    access_log /var/log/nginx/access.log;\n\
    error_log /var/log/nginx/error.log;\n\
    \n\
    location / {\n\
        try_files \$uri \$uri/ /index.php?\$query_string;\n\
    }\n\
    \n\
    location ~ \.php$ {\n\
        fastcgi_pass 127.0.0.1:9000;\n\
        fastcgi_index index.php;\n\
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;\n\
        include fastcgi_params;\n\
        fastcgi_intercept_errors on;\n\
        fastcgi_buffer_size 128k;\n\
        fastcgi_buffers 4 256k;\n\
        fastcgi_busy_buffers_size 256k;\n\
    }\n\
    \n\
    location ~ /\.ht {\n\
        deny all;\n\
    }\n\
}" > /etc/nginx/sites-available/default

# Create a script to run migrations and seeding
RUN echo '#!/bin/bash\n\
\n\
# Function to log messages\n\
log() {\n\
    echo "[$(date +"%Y-%m-%d %H:%M:%S")] $1"\n\
}\n\
\n\
# Start logging\n\
log "Starting application setup..."\n\
\n\
# Generate application key if not set\n\
if [ -z "$APP_KEY" ]; then\n\
    log "Generating application key..."\n\
    php artisan key:generate\n\
fi\n\
\n\
# Clear and cache configuration\n\
log "Caching configuration..."\n\
php artisan config:clear\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
\n\
# Wait for database\n\
log "Waiting for database connection..."\n\
while ! nc -z $DB_HOST $DB_PORT; do\n\
    sleep 1\n\
done\n\
log "Database is ready!"\n\
\n\
# Run migrations\n\
log "Running migrations..."\n\
php artisan migrate --force\n\
\n\
# Seed database if needed\n\
if ! php artisan tinker --execute="App\\Models\\Admin::count()" | grep -q "0"; then\n\
    log "Admin already exists, skipping seeding"\n\
else\n\
    log "Seeding database..."\n\
    php artisan db:seed --force\n\
fi\n\
\n\
# Start services\n\
log "Starting Nginx..."\n\
service nginx start\n\
\n\
log "Starting PHP-FPM..."\n\
php-fpm -F\n\
' > /var/www/start.sh

# Make the script executable
RUN chmod +x /var/www/start.sh

# Create log directory and set permissions
RUN mkdir -p /var/log/nginx && \
    chown -R www-data:www-data /var/log/nginx

# Expose port 80
EXPOSE 80

# Start the application
CMD ["/var/www/start.sh"] 
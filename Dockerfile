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

# Copy the rest of the application
COPY . .

# Generate autoload files
RUN composer dump-autoload --optimize

# Build frontend assetss
RUN npm run build

# Set permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Configure Nginx
RUN echo "server {\n\
    listen 80;\n\
    server_name _;\n\
    root /var/www/public;\n\
    index index.php;\n\
    location / {\n\
        try_files \$uri \$uri/ /index.php?\$query_string;\n\
    }\n\
    location ~ \.php$ {\n\
        fastcgi_pass 127.0.0.1:9000;\n\
        fastcgi_index index.php;\n\
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;\n\
        include fastcgi_params;\n\
    }\n\
}" > /etc/nginx/sites-available/default

# Create a script to run migrations and seeding
RUN echo '#!/bin/bash\n\
echo "Waiting for database connection..."\n\
while ! nc -z $DB_HOST $DB_PORT; do\n\
  sleep 1\n\
done\n\
echo "Database is ready!"\n\
php artisan migrate --force\n\
# Only seed if no admin exists\n\
if ! php artisan tinker --execute="App\\Models\\Admin::count()" | grep -q "0"; then\n\
    echo "Admin already exists, skipping seeding"\n\
else\n\
    php artisan db:seed --force\n\
fi\n\
# Start Nginx and PHP-FPM\n\
service nginx start\n\
php-fpm' > /var/www/start.sh

# Make the script executable
RUN chmod +x /var/www/start.sh

# Expose port 80
EXPOSE 80

# Start the application
CMD ["/var/www/start.sh"] 
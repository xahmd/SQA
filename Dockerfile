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
    netcat-openbsd

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

# Create a startup script
RUN echo '#!/bin/bash\n\
echo "Waiting for database connection..."\n\
while ! php artisan db:monitor --timeout=1 > /dev/null 2>&1; do\n\
  echo "Waiting for database connection..."\n\
  sleep 2\n\
done\n\
echo "Database is ready!"\n\
php artisan migrate --force\n\
php-fpm -F' > /var/www/start.sh

# Make the script executable
RUN chmod +x /var/www/start.sh

# Expose port 10000 for Render.com
EXPOSE 10000

# Start the application
CMD ["/var/www/start.sh"] 
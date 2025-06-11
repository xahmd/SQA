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

# Create a simple startup script
RUN echo '#!/bin/bash\n\
php artisan migrate --force\n\
php-fpm' > /var/www/start.sh

# Make the script executable
RUN chmod +x /var/www/start.sh

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start the application
CMD ["/var/www/start.sh"] 
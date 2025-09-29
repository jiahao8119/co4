FROM php:8.2-apache

# Install required system packages first
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    libonig-dev \
    libssl-dev \       
    unzip \
    zip \
    git \
    && docker-php-ext-install pdo pdo_mysql intl \
    && rm -rf /var/lib/apt/lists/*

# Ensure OpenSSL extension is enabled
RUN docker-php-ext-install openssl || true

# Enable Apache rewrite
RUN a2enmod rewrite

# Copy project files
COPY . /var/www/html

# Set working directory
WORKDIR /var/www/html

# Permissions for writable
RUN chown -R www-data:www-data /var/www/html/writable

# Point Apache to public/ folder
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]

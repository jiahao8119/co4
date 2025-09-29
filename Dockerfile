# Use official PHP + Apache
FROM php:8.2-apache

# Enable Apache mod_rewrite (needed by CI4 for routing)
RUN a2enmod rewrite

# Install required PHP extensions
RUN docker-php-ext-install pdo pdo_mysql intl

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html

# Set permissions for writable directory
RUN chown -R www-data:www-data /var/www/html/writable

# Configure Apache for CI4
RUN echo "<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>" > /etc/apache2/conf-available/ci4.conf \
 && a2enconf ci4

# Expose port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]

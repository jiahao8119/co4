FROM php:8.2-apache

# Enable Apache modules
RUN a2enmod rewrite

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql intl

# Copy CodeIgniter project files
COPY . /var/www/html

# Set working directory
WORKDIR /var/www/html

# Permissions for writable/
RUN chown -R www-data:www-data /var/www/html/writable

# Point Apache to public/ folder
RUN echo "DocumentRoot /var/www/html/public" >> /etc/apache2/sites-available/000-default.conf

# Expose port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]

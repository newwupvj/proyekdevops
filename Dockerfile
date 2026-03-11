FROM php:8.2-apache
# Install ekstensi MySQL untuk PHP
RUN docker-php-ext-install mysqli
# Copy semua file ke folder web server
COPY . /var/www/html/
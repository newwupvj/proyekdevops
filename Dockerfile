FROM php:8.2-apache

# 1. Install ekstensi MySQL
RUN docker-php-ext-install mysqli

# 2. Copy semua file ke folder web server
COPY . /var/www/html/

# 3. Beri izin folder agar Apache bisa nulis/baca (penting untuk Railway)
RUN chown -R www-data:www-data /var/www/html

# 4. Beri tahu Apache untuk pakai port yang dikasih Railway secara otomatis
# Kita pakai perintah 'sed' yang lebih aman di satu baris saja
RUN sed -i 's/Listen 80/Listen ${PORT}/g' /etc/apache2/ports.conf && \
    sed -i 's/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/g' /etc/apache2/sites-available/000-default.conf
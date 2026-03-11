FROM php:8.2-cli

# Install ekstensi MySQL
RUN docker-php-ext-install mysqli

# Set folder kerja
WORKDIR /var/www/html
COPY . .

# Jalankan PHP server internal (langsung dengerin port dari Railway)
CMD php -S 0.0.0.0:${PORT}
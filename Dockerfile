FROM php:8.2-apache

RUN docker-php-ext-install mysqli

WORKDIR /var/www/html
COPY --chown=www-data:www-data . .

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
  CMD php -r '$$response = @file_get_contents("http://127.0.0.1/health.php"); exit($$response === false ? 1 : 0);'
FROM php:8.2-fpm-alpine

# Install PostgreSQL client & PDO dependencies
RUN apk add --no-cache libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Set working directory
WORKDIR /var/www/html

EXPOSE 9000
CMD ["php-fpm"]
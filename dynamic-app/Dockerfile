FROM php:8.1-fpm

# Install nginx dan ekstensi PDO MySQL
RUN apt-get update && apt-get install -y \
    nginx \
    default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Salin konfigurasi nginx langsung ke dalam image
COPY nginx.conf /etc/nginx/sites-available/default

WORKDIR /var/www/html

COPY . .

RUN chown -R www-data:www-data /var/www/html

# Expose port 80 (nginx) langsung dari container ini
EXPOSE 80

# Jalankan php-fpm dan nginx bersamaan
CMD service nginx start && php-fpm

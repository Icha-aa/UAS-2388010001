FROM php:8.1-fpm

# Install dependencies sistem dan ekstensi PHP pdo_pgsql yang dibutuhkan Laravel/Dynamic App
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql

# Tentukan working directory di dalam container
WORKDIR /var/www/html

# Salin seluruh file proyek dari laptop ke dalam container
COPY . .

# Beri izin akses ke folder penyimpanan jika diperlukan
RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000
CMD ["php-fpm"]

#fff
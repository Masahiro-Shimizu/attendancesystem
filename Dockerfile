# Laravel公式のphp-apacheイメージなどでもOK
FROM php:8.2-fpm

# 必要なパッケージインストール
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Composerインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

# 権限調整
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

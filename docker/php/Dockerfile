FROM php:8.2-fpm-alpine

# Instaluj zależności systemowe
RUN apk add --no-cache \
    postgresql-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev \
    oniguruma-dev \
    npm \
    nodejs \
    linux-headers \
    rabbitmq-c-dev \
    autoconf \
    g++ \
    make

# Instaluj rozszerzenia PHP
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    pgsql \
    zip \
    bcmath \
    sockets \
    sodium

# Composer 
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# katalog roboczy
WORKDIR /var/www/html

COPY . .
RUN chown -R www-data:www-data /var/www/html
USER www-data
EXPOSE 9000

CMD ["php-fpm"]
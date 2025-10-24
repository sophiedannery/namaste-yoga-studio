# docker/php/Dockerfile
FROM php:8.3-fpm-alpine

# Outils + libs nécessaires aux extensions PHP de Symfony
RUN apk add --no-cache \
    $PHPIZE_DEPS bash git unzip icu-dev oniguruma-dev \
    libpng-dev libjpeg-turbo-dev freetype-dev libzip-dev openssl-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql intl gd zip opcache \
    # Driver MongoDB pour PHP via PECL
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && apk del $PHPIZE_DEPS

# Composer (binaire officiel)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Dossier de travail de l'app
WORKDIR /var/www/html

# Marqueurs pour que Symfony Flex puisse injecter des ajouts si besoin
###> recipes ###
###< recipes ###
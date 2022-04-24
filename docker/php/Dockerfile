FROM php:8.1-fpm-alpine

WORKDIR /var/www/html

RUN docker-php-ext-install pdo pdo_mysql

RUN apk add pcre-dev $PHPIZE_DEPS \
        && pecl install redis \
        && docker-php-ext-enable redis

RUN addgroup -g 1000 laravel && adduser -G laravel -g laravel -s /bin/sh -D laravel

USER laravel
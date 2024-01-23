FROM php:8.3-apache AS build-php

RUN --mount=type=cache,target=/var/cache/apt apt update && apt full-upgrade -qy && apt install git build-essential zip unzip -qy && \
    apt clean -qy && apt autoremove -qy

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN mount=type=cache,target='/usr/local/lib/php' \
    && pecl install xdebug

 RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli && \
    docker-php-ext-install pdo_mysql && docker-php-ext-enable pdo_mysql
FROM php:8.3-fpm

RUN apt-get update && apt-get install -y bash
RUN docker-php-ext-install bcmath

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

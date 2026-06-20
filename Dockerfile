FROM php:8.3-apache

RUN docker-php-ext-install mysqli

WORKDIR /var/www/html
COPY . /var/www/html/

RUN mkdir -p uploads/checked \
    && chown -R www-data:www-data uploads

FROM php:8.2-apache

RUN apt-get update && apt-get install -y libxslt1-dev \
    && docker-php-ext-install xsl

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

FROM php:8.1-fpm

ENV TZ=UTC

RUN apt-get update

# # Install MongoDB extension
# RUN pecl install mongodb \
#     && docker-php-ext-enable mongodb

WORKDIR /var/www/html
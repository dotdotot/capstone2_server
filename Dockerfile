FROM php:8.2-fpm

RUN apt-get update

# Install MongoDB extension
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Copy your application code
COPY . /var/www/html

# Create the text.c file
RUN echo "hello" > /var/www/html/text.c

# Set the working directory
WORKDIR /var/www/html

# Set up additional configurations if needed

# Expose ports and start the application
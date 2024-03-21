FROM composer:2.0 as vendor

WORKDIR /app
COPY composer.json composer.json
COPY composer.lock composer.lock

# Enable the mysqli extension
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

RUN composer clear-cache
RUN composer install \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --no-dev \
    --prefer-dist \
    --ignore-platform-reqs

COPY . .
RUN composer dump-autoload

FROM php:8.1-apache

# Enable Apache modules
RUN a2enmod rewrite

# Setup app files
WORKDIR /var/www/html
COPY . /var/www/html
COPY --from=vendor app/vendor/ /var/www/html/vendor/

# Enable the mysqli extension
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Open port 80
EXPOSE 	80

# Start Apache web server
CMD ["apache2-foreground"]
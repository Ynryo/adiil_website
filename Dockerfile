FROM php:8.3-apache

#install mysqli extension for MySQL connectivity
RUN docker-php-ext-install mysqli

#install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY ./adiil_website /var/www/html

#regenerate autoloader to match current composer.json PSR-4 mapping
RUN composer dump-autoload --no-dev --optimize

EXPOSE 80

CMD ["apache2-foreground"]
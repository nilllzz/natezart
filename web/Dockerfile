FROM php:8.4.2-cli

COPY . /var/www/html
WORKDIR /var/www/html

# Install & enable php extensions
RUN pecl install redis-6.1.0 \
    && pecl install xdebug-3.4.1 \
    && docker-php-ext-enable redis xdebug

CMD [ "php", "-S", "0.0.0.0:80"]
EXPOSE 80

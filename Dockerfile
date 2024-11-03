FROM php:8.2-apache

RUN apt-get update && \
    apt-get install -y libpq-dev unzip && \
    docker-php-ext-install pgsql pdo_pgsql

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/html

RUN mkdir -p /var/www/html/src/assets && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html && \
    chmod -R 775 /var/www/html/src/assets

WORKDIR /var/www/html

EXPOSE 80

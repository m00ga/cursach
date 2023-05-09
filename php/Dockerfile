FROM php:fpm-alpine
RUN mkdir -p /var/www/kursach
RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN mv /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
RUN echo "session.save_path=\"/tmp\"" >> /usr/local/etc/php/php.ini

COPY ./entrypoint.sh /
COPY ./migrate.php /
COPY ./migrations/ /migrations/
ENTRYPOINT ["/entrypoint.sh", "php-fpm", "-F"] 

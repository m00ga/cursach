FROM php:fpm-alpine
RUN mkdir -p /var/www/kursach
RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY ./entrypoint.sh /
COPY ./migrate.php /
COPY ./migrations/ /migrations/
ENTRYPOINT ["/entrypoint.sh", "php-fpm", "-F"] 

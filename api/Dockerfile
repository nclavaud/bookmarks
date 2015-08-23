FROM php:5.6

RUN docker-php-ext-install mbstring bcmath

VOLUME /var/www
WORKDIR /var/www

ENV DEBUG=1
ENV DSN=sqlite:/var/www/database.sqlite

ENTRYPOINT ["/var/www/run"]

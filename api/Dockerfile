FROM php:5.6

RUN apt-get update && \
    apt-get install -y \
        sqlite3 \
        unzip

RUN docker-php-ext-install mbstring bcmath

VOLUME /var/www
WORKDIR /var/www

ENV DEBUG=1
ENV DSN=sqlite:/var/www/database.sqlite

ENTRYPOINT ["/var/www/run"]

FROM php:5.6-cli

VOLUME /var/www
WORKDIR /var/www

ENV DEBUG=1
ENV DSN=sqlite:/var/www/database.sqlite

ENTRYPOINT ["/var/www/run"]

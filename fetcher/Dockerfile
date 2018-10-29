FROM php:5.6

RUN apt-get update && \
    apt-get install -y \
        unzip

RUN docker-php-ext-install mbstring bcmath

VOLUME /var/www
WORKDIR /var/www

ENV RABBITMQ_HOST=rabbitmq
ENV RABBITMQ_PORT=5672
ENV RABBITMQ_USER=guest
ENV RABBITMQ_PASSWORD=guest

ENTRYPOINT ["/var/www/run"]

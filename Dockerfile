FROM php:7.4-apache

COPY . var/www/html

WORKDIR var/www/html


RUN  apt-get install && apt-get update -y
RUN  docker-php-ext-install pdo pdo_mysql mysqli sockets && docker-php-ext-enable  pdo pdo_mysql mysqli sockets


EXPOSE 80

FROM php:7.4-apache

COPY . /var/www/html/

WORKDIR /var/www/html/


RUN  apt-get install && apt-get update -y
RUN  docker-php-ext-install pdo pdo_mysql mysqli sockets && docker-php-ext-enable  pdo pdo_mysql mysqli sockets
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html
RUN echo '<Directory /var/www/html>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf
EXPOSE 80

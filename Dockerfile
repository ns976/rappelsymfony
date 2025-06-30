FROM php:7.4-apache

COPY . /var/www/html/

WORKDIR /var/www/html/


RUN  apt-get install && apt-get update -y
RUN  docker-php-ext-install pdo pdo_mysql mysqli sockets && docker-php-ext-enable  pdo pdo_mysql mysqli sockets

# Définir le DocumentRoot sur /public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
 && echo '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Order Allow,Deny\n\
    Allow from All\n\
</Directory>' >> /etc/apache2/apache2.conf

# Corriger les permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Port pour Render
RUN sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf \
 && sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

CMD ["apache2-foreground"]
EXPOSE 80

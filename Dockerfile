FROM php:7.4-apache

# Installer dépendances
RUN apt-get update -y && apt-get install -y \
    git zip unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mysqli sockets \
    && a2enmod rewrite

# Copier projet
COPY . var/www/html

# Changer DocumentRoot vers /public et config Apache
RUN echo '<VirtualHost *:${PORT}>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Adapter Apache au port Render
RUN sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf

WORKDIR var/www/html

EXPOSE 80

CMD ["apache2-foreground"]

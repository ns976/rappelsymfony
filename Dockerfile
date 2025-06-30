FROM php:7.4-apache

# Installer extensions PHP requises
RUN apt-get update -y && apt-get install -y \
    git zip unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mysqli sockets \
    && a2enmod rewrite

# Installer Composer (depuis image officielle)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copier le code du projet
COPY . /var/www/html/

# Installer les dépendances Symfony
WORKDIR /var/www/html
RUN composer install --no-dev --optimize-autoloader

# Configurer Apache pour Symfony (DocumentRoot /public)
RUN echo '<VirtualHost *:${PORT}>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Corriger permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Adapter Apache pour Render
RUN sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf

EXPOSE 80

CMD ["apache2-foreground"]

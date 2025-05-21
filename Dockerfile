# Imagen base con Apache y PHP
FROM php:8.2-apache

# Copiar archivos del proyecto al contenedor
COPY . /var/www/html/

# Habilitar extensiones necesarias de PHP (como mysqli)
RUN apt-get update && \
    apt-get install -y libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql && \
    a2enmod rewrite

# Dar permisos a Apache
RUN chown -R www-data:www-data /var/www/html

# Habilitar .htaccess si usas
RUN a2enmod rewrite

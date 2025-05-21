# Imagen base con Apache y PHP
FROM php:8.2-apache

# Copiar archivos del proyecto al contenedor
COPY . /var/www/html/

# Habilitar extensiones necesarias de PHP (como mysqli)
RUN docker-php-ext-install mysqli

# Dar permisos a Apache
RUN chown -R www-data:www-data /var/www/html

# Habilitar .htaccess si usas
RUN a2enmod rewrite

#!/bin/bash
# Instalar extensiones PHP necesarias
sudo apt-get update
sudo apt-get install -y libpq-dev
sudo docker-php-ext-install pdo pdo_pgsql
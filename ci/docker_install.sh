#!/bin/bash

# We need to install dependencies only for Docker
[[ ! -e /.dockerenv ]] && exit 0

set -xe

# Set google DNS (fix getcomposer.org resolve issues)
echo nameserver 8.8.8.8 >> /etc/resolv.conf

# Install git (the php image doesn't have it) which is required by composer
apt-get update -yqq
apt-get install -yqq git iputils-ping libzip-dev zip

# Enable zip extension and db drivers
docker-php-ext-install zip mysqli pdo_mysql

# Xdebug
pecl install xdebug && docker-php-ext-enable xdebug

# Install composer
php -r "readfile('https://getcomposer.org/installer');" > composer-setup.php
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
php -r "unlink('composer-setup.php');"
chmod +x /usr/local/bin/composer
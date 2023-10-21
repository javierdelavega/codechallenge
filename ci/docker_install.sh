#!/bin/bash

# We need to install dependencies only for Docker
[[ ! -e /.dockerenv ]] && exit 0

set -xe

# Install git (the php image doesn't have it) which is required by composer
apt-get update -yqq
apt-get install git -yqq

# Install composer

curl --location --output /usr/local/bin/composer "https://getcomposer.org/download/latest-stable/composer.phar"
chmod +x /usr/local/bin/composer

# Run composer install
composer install --ignore-platform-reqs --no-scripts
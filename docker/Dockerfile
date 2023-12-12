FROM php:8.2-fpm as base

RUN apt-get clean
RUN apt-get update

# mysqli pdo_mysql
RUN docker-php-ext-install mysqli pdo_mysql

# Zip Git, y intl
RUN apt-get install -y \
        libzip-dev \
        zip \
        libicu-dev \
        git \
  && docker-php-ext-install zip \
  && docker-php-ext-configure intl \
  && docker-php-ext-install intl

#APCu
RUN pecl install apcu \
    && docker-php-ext-enable apcu \
    && pecl clear-cache

# instalar composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /app/codechallenge

### DEV IMAGE ###
FROM base as dev

RUN rm -f .env.local.php

#Instalar Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash \
&& mv /root/.symfony5/bin/symfony /usr/local/bin/

# Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Scripts auxiliares para ejecutar tests
RUN echo '#!/bin/bash\nphp bin/phpunit --testdox' > /usr/bin/run-test && \
    chmod +x /usr/bin/run-test

RUN echo '#!/bin/bash\nXDEBUG_MODE=coverage php bin/phpunit --testdox --coverage-text' > /usr/bin/run-test-coverage && \
    chmod +x /usr/bin/run-test-coverage

CMD composer install --ignore-platform-reqs --no-scripts \    
    && php bin/console doctrine:database:create --if-not-exists --no-interaction \
    && php bin/console doctrine:migrations:migrate --no-interaction \
    && php bin/console --env=test doctrine:database:drop --if-exists --force \    
    && php bin/console --env=test doctrine:database:create --if-not-exists --no-interaction \
    && php bin/console --env=test doctrine:schema:create --no-interaction \
    && php bin/console doctrine:fixtures:load --purge-exclusions=api_token --purge-exclusions=cart \
    --purge-exclusions=cart_items --purge-exclusions=doctrine_migrations_versions --purge-exclusions=item \
    --purge-exclusions=orders --purge-exclusions=order_line --purge-exclusions=order_order_lines \
    --purge-exclusions=user --no-interaction \
    && php-fpm

#CMD php-fpm

### PROD IMAGE ###
FROM base as prod

COPY ./app /app

CMD composer install --ignore-platform-reqs --no-scripts \
    && php bin/console doctrine:database:create --if-not-exists --no-interaction \
    && php bin/console doctrine:migrations:migrate --no-interaction \
    && APP_ENV=dev php bin/console doctrine:fixtures:load --purge-exclusions=api_token --purge-exclusions=cart \
    --purge-exclusions=cart_items --purge-exclusions=doctrine_migrations_versions --purge-exclusions=item \
    --purge-exclusions=orders --purge-exclusions=order_line --purge-exclusions=order_order_lines \
    --purge-exclusions=user --no-interaction \
    && composer dump-env prod \
    && composer install --no-dev --optimize-autoloader \
    && APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear \
    && chown -R www-data:www-data /app/codechallenge/var \
    && php-fpm

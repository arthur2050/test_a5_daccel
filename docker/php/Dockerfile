FROM php:8.4-fpm

# Системные зависимости и расширения PHP
RUN apt-get update && apt-get install -y \
    zip unzip git curl libicu-dev libzip-dev libonig-dev libpng-dev libpq-dev libxml2-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip opcache

# Установка Xdebug и Redis через PECL
RUN pecl install xdebug redis \
    && docker-php-ext-enable xdebug redis

# Конфигурация Xdebug (для отладки в IDE)
COPY ./docker/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

WORKDIR /var/www/html

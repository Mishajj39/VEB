FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpq-dev \
    libzip-dev \
    zip

# Установка расширений
RUN docker-php-ext-install pdo pdo_mysql zip

WORKDIR /var/www/html

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# Копируем composer.json и устанавливаем зависимости
COPY composer.json /var/www/html/
RUN composer install --ignore-platform-req=php

COPY ./www /var/www/html

RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

CMD ["php-fpm"]
FROM php:8.2-fpm

# Устанавливаем расширения для MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Устанавливаем дополнительные утилиты
RUN apt-get update && apt-get install -y \
    zip unzip git curl \
    && rm -rf /var/lib/apt/lists/*

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer

WORKDIR /var/www/html

# Копируем исходный код
COPY ./code /var/www/html

CMD ["php-fpm"]
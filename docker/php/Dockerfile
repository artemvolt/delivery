FROM php:8.2-fpm
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN apt-get clean && apt-get update
RUN apt-get install -y nano vim wget git unzip zlib1g-dev libpng-dev libzip-dev libpq-dev libicu-dev iputils-ping

RUN docker-php-ext-install bcmath zip gd intl pdo pdo_mysql
RUN docker-php-ext-install pdo_pgsql
WORKDIR /var/www

RUN apt-get install -y protobuf-compiler \
    && pecl install grpc && docker-php-ext-enable grpc
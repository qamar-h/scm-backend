FROM php:8.0-apache

RUN apt-get update

RUN apt-get install -y \
    libonig-dev\
    git \
    zip \
    curl \
    sudo \
    unzip \
    graphviz \
    libicu-dev \
    libzip-dev \
    libbz2-dev \
    libpng-dev \
    libjpeg-dev \
    libmcrypt-dev \
    libreadline-dev \
    libfreetype6-dev \
    default-mysql-client \
    g++

RUN docker-php-ext-install \
    bz2 \
    intl \
    iconv \
    bcmath \
    opcache \
    calendar \
    mbstring \
    pdo_mysql \
    zip

WORKDIR /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite headers

EXPOSE 80

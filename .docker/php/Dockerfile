FROM php:8.0-apache

WORKDIR /var/www/html

RUN apt-get update && \
    apt-get install -y \
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
    g++ && \
    docker-php-ext-install \
    bz2 \
    intl \
    iconv \
    bcmath \
    opcache \
    calendar \
    mbstring \
    pdo_mysql \
    zip && \
    a2enmod rewrite headers && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    useradd -ms /bin/bash the_user -g www-data && \
    chown the_user:www-data -R /var/www/html 
    
USER the_user

COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80

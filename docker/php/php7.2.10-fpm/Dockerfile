FROM php:7.2.10-fpm

RUN apt-get update && apt-get install -my wget gnupg
RUN apt-get install -y apt-transport-https
RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add -
RUN curl https://packages.microsoft.com/config/ubuntu/16.04/prod.list > /etc/apt/sources.list.d/mssql-release.list

RUN apt-get update

RUN apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        zlib1g-dev \
        libicu-dev \
        libpq-dev \
        libzip-dev \
        zip \
        g++

RUN ACCEPT_EULA=Y apt-get -y install msodbcsql17 mssql-tools
RUN apt-get -y install unixodbc-dev

RUN docker-php-ext-install -j$(nproc) iconv
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install -j$(nproc) pdo pdo_mysql
RUN docker-php-ext-install pdo pdo_pgsql

RUN docker-php-ext-configure zip --with-libzip
RUN docker-php-ext-install zip

RUN pecl install sqlsrv pdo_sqlsrv
RUN docker-php-ext-enable sqlsrv pdo_sqlsrv
RUN docker-php-ext-configure intl
RUN docker-php-ext-install -j$(nproc) intl

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

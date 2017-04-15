FROM php:7.1-fpm

RUN apt-get update

# Install env
RUN apt-get install -y git

# Some basic extensions
RUN docker-php-ext-install -j$(nproc) json mbstring opcache pdo pdo_mysql

# Curl
RUN apt-get install -y libcurl4-openssl-dev
RUN docker-php-ext-install -j$(nproc) curl

# Intl
RUN apt-get install -y libicu-dev
RUN docker-php-ext-install -j$(nproc) intl

# Zip
RUN apt-get install -y libzip-dev
RUN docker-php-ext-install -j$(nproc) zip

# xDebug
ARG INSTALL_XDEBUG=false
RUN if [ ${INSTALL_XDEBUG} = true ]; then \
    pecl install xdebug && \
    docker-php-ext-enable xdebug \
;fi

COPY ./conf/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Composer:
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    chmod +x /usr/local/bin/composer

ARG PUID=1000
RUN usermod -u ${PUID} www-data
ARG PROJECT_PATH=/var/www/html
WORKDIR ${PROJECT_PATH}
CMD ["php-fpm"]
EXPOSE 9000
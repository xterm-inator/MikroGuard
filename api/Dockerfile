FROM phpswoole/swoole:5.0-php8.1-alpine

## Set to keep file permissions same as host for mounted volume
#RUN useradd -m -u 1000 user

#RUN apt-get update && apt-get install -y  \
#    default-mysql-client \
#    git \
#    libzip-dev \

RUN apk add --no-cache \
    nodejs \
    npm

RUN docker-php-ext-install bcmath && \
    docker-php-ext-install sockets

# Install Composer
#RUN apt-get install -y curl && \
#    curl -sS https://getcomposer.org/installer | php && \
#    mv composer.phar /usr/local/bin/composer && \
#    composer self-update && \
#    apt-get remove --purge curl -y && \
#    apt-get clean

# Configure PHP
#RUN echo "php_admin_flag[log_errors] = On">>/usr/local/etc/php-fpm.conf
#COPY ./docker/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Install Xdebug
#RUN pecl install xdebug && \
#    docker-php-ext-enable xdebug

WORKDIR /var/www

CMD ["php", "artisan", "octane:start", "--watch", "--poll", "--host=0.0.0.0"]

FROM phpswoole/swoole:6.0-php8.4-alpine

RUN apk add --no-cache \
    linux-headers \
    nodejs \
    npm

RUN docker-php-ext-install bcmath && \
    docker-php-ext-install sockets && \
    docker-php-ext-install pcntl

WORKDIR /var/www

CMD ["php", "artisan", "octane:start", "--watch", "--poll", "--host=0.0.0.0"]

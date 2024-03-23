FROM phpswoole/swoole:5.1-php8.3-alpine

RUN apk add --no-cache \
    linux-headers \
    nodejs \
    npm

RUN docker-php-ext-install bcmath && \
    docker-php-ext-install sockets && \
    docker-php-ext-install pcntl

WORKDIR /var/www

CMD ["php", "artisan", "octane:start", "--watch", "--poll", "--host=0.0.0.0"]

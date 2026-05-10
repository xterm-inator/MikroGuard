FROM phpswoole/swoole:6.1-php8.4

ARG UID=1000
ARG GID=1000

# Set to keep file permissions same as host for mounted volume
RUN groupadd --gid $GID mikroguard
RUN useradd -g mikroguard -m -u $UID mikroguard

RUN apt update && apt install -y \
    nodejs \
    npm

RUN docker-php-ext-install bcmath && \
    docker-php-ext-install sockets && \
    docker-php-ext-install pcntl

WORKDIR /var/www
USER mikroguard
CMD ["php", "artisan", "octane:start", "--watch", "--poll", "--host=0.0.0.0"]

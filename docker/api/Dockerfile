FROM php:7.2.7-cli

RUN apt-get update && apt-get install \
    libicu-dev \
    libxml2-dev \
    -y

RUN docker-php-ext-install -j$(nproc) soap

# Swoole
RUN pecl install swoole && \
    docker-php-ext-enable swoole

# Composer
RUN curl --insecure https://getcomposer.org/composer.phar -o /usr/bin/composer && \
    chmod +x /usr/bin/composer

WORKDIR /app

COPY ./composer*.json ./

CMD ["composer", "install"]

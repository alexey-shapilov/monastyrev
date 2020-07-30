FROM php:7.4-cli-alpine

RUN apk add --no-cache pcre-dev ${PHPIZE_DEPS} \
    && pecl install -o -f xdebug \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable xdebug \
    && apk del pcre-dev ${PHPIZE_DEPS}

WORKDIR /app

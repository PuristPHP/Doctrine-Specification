FROM php:8.3-cli-alpine
RUN apk add --no-cache --update --virtual .build-deps $PHPIZE_DEPS linux-headers \
    && pecl install xdebug-3.3.2 \
    && docker-php-ext-enable xdebug \
    && apk del -f .build-deps \
    && echo "xdebug.mode=coverage" >> $PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini

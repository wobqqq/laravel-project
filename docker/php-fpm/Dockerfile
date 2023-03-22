FROM composer:2.5 AS composer

FROM php:8.2-fpm-alpine3.17

RUN apk update \
    && apk add --no-cache \
        libpng-dev \
        icu-dev \
        libzip-dev \
    && docker-php-ext-install  \
        gd \
        intl \
        pdo_mysql \
        zip \
        bcmath \
    && rm -rf  \
        /var/lib/apt/lists/* \
        /tmp/*  \
        /var/tmp/* \
        /usr/share/doc/* \
        /var/cache/apk/*

ARG APP_ENV=production

RUN if [ "$APP_ENV" = "local" ] ; then \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && apk add --update linux-headers \
    && pecl install xdebug-3.2.0 \
    && docker-php-ext-enable xdebug \
    && apk del -f .build-deps \
    && echo "xdebug.mode=develop,debug,coverage" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.discover_client_host=0" >> /usr/local/etc/php/conf.d/xdebug.ini \
;fi

ARG UID=1000
ARG GID=1000

RUN deluser --remove-home www-data \
    &&addgroup -g $GID www-data \
    && adduser -S -G www-data -u $UID www-data

COPY --chown=www-data:www-data . .

USER www-data

COPY --from=composer /usr/bin/composer /usr/local/bin/composer
COPY --from=composer /tmp/keys.dev.pub /home/www-data/.composer/keys.dev.pub
COPY --from=composer /tmp/keys.tags.pub /home/www-data/.composer/keys.tags.pub

RUN composer install --no-ansi --no-dev --no-interaction --no-plugins --no-progress --no-scripts --optimize-autoloader

EXPOSE 9000

CMD ["php-fpm"]

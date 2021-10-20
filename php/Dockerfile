ARG PHP_VERSION
FROM php:${PHP_VERSION}-fpm-alpine
LABEL maintainer=Frank

ARG PHP_REDIS_VERSION
# ARG PHP_MEMCACHED_VERSION
# ARG PHP_MONGODB_VERSION
# ARG APCU_VERSION
# ARG YAF_VERSION
# ARG YACONF_VERSION
# ARG XDEBUG_VERSION
ARG SWOOLE_VERSION

RUN docker-php-ext-install pdo_mysql && \
set -xe && apk add --no-cache --update --virtual .phpize-deps $PHPIZE_DEPS && \
apk add zlib-dev && apk add libstdc++ && \
# apk add libmemcached-dev && \
pecl install https://pecl.php.net/get/redis-${PHP_REDIS_VERSION}.tgz && docker-php-ext-enable redis && \
# pecl install https://pecl.php.net/get/mongodb-${PHP_MONGODB_VERSION}.tgz && docker-php-ext-enable mongodb && \
# pecl install https://pecl.php.net/get/memcached-${PHP_MEMCACHED_VERSION}.tgz && docker-php-ext-enable memcached && \
# pecl install https://pecl.php.net/get/apcu-${APCU_VERSION}.tgz && docker-php-ext-enable apcu && \
# pecl install https://pecl.php.net/get/yaf-${YAF_VERSION}.tgz && docker-php-ext-enable yaf && \
# pecl install https://pecl.php.net/get/yaconf-${YACONF_VERSION}.tgz && docker-php-ext-enable yaconf && \
# pecl install https://pecl.php.net/get/xdebug-${XDEBUG_VERSION}.tgz && docker-php-ext-enable xdebug && \
pecl install https://pecl.php.net/get/swoole-${SWOOLE_VERSION}.tgz && docker-php-ext-enable swoole && \
apk del .phpize-deps && \
rm -rf /tmp/*

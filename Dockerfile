FROM php:8.3-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        $PHPIZE_DEPS \
        libssl-dev \
        pkg-config \
        unzip \
        git \
    && pecl install mongodb-1.21.0 \
    && docker-php-ext-enable mongodb \
    && docker-php-ext-install pdo_mysql \
    && apt-get purge -y --auto-remove $PHPIZE_DEPS \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/*.conf

WORKDIR /var/www/html
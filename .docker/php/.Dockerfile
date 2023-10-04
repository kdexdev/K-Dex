FROM php:8.0-fpm-alpine

ARG USER
ARG USER_ID
ARG GROUP_ID

WORKDIR /var/www

# Install necessary packages
RUN apk add  --update --no-cache \
    git \
    curl \
    zip \
    bash \
    unzip \
    icu-dev \
    sqlite \
    php8-sqlite3
RUN apk add --no-cache --virtual .build-deps \
    linux-headers \
    $PHPIZE_DEPS

# Install PHP extensions
RUN docker-php-ext-configure intl
RUN docker-php-ext-install pdo pdo_mysql intl

# Install Composer
RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/local/bin --filename=composer

# Install the Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Install and enable Xdebug
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

# Remove the build dependencies for a smaller overall image
RUN apk del .build-deps

# Create the same user account, so file ownership isn't wonky
RUN addgroup -g $GROUP_ID $USER
RUN adduser -s /bin/bash -D -G $USER -u $USER_ID $USER

USER $USER

# Copy Xdebug configuration
COPY ./php/xdebug.ini "${PHP_INI_DIR}/conf.d"

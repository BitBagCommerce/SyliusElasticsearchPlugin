FROM ubuntu:20.04
ARG DEBIAN_FRONTEND=noninteractive
ARG PHP_VERSION=8.1
ENV LC_ALL=C.UTF-8

# Install basic tools
RUN apt-get update && apt-get install -y \
    software-properties-common \
    curl \
    make \
    supervisor \
    unzip \
    python2 \
    g++

# Append NODE, NGINX and PHP repositories
RUN add-apt-repository ppa:ondrej/php \
    && add-apt-repository ppa:ondrej/nginx \
    && curl -sL https://deb.nodesource.com/setup_14.x | bash -

# Install required PHP extensions
RUN apt-get update && apt-get install -y \
    nodejs \
    nginx \
    php${PHP_VERSION} \
    php${PHP_VERSION}-apcu \
    php${PHP_VERSION}-calendar \
    php${PHP_VERSION}-common \
    php${PHP_VERSION}-cli \
    php${PHP_VERSION}-ctype \
    php${PHP_VERSION}-curl \
    php${PHP_VERSION}-dom \
    php${PHP_VERSION}-exif \
    php${PHP_VERSION}-fpm \
    php${PHP_VERSION}-gd \
    php${PHP_VERSION}-intl \
    php${PHP_VERSION}-mbstring \
    php${PHP_VERSION}-mysql \
    php${PHP_VERSION}-opcache \
    php${PHP_VERSION}-pdo \
    php${PHP_VERSION}-pgsql \
    php${PHP_VERSION}-sqlite \
    php${PHP_VERSION}-xml \
    php${PHP_VERSION}-xsl \
    php${PHP_VERSION}-yaml \
    php${PHP_VERSION}-zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename composer

# Cleanup
RUN apt-get remove --purge -y software-properties-common curl && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/* /usr/share/man/*

# Create directory for php-fpm socket
# Link php-fpm binary file without version
# -p Creates missing intermediate path name directories
RUN ln -s /usr/sbin/php-fpm${PHP_VERSION} /usr/sbin/php-fpm && mkdir -p /run/php

# Install yarn
RUN npm install -g yarn && npm cache clean --force

# Initialize config files
COPY .docker/supervisord.conf   /etc/supervisor/conf.d/supervisor.conf
COPY .docker/nginx.conf         /etc/nginx/nginx.conf
COPY .docker/fpm.conf           /etc/php/${PHP_VERSION}/fpm/pool.d/www.conf
COPY .docker/php.ini            /etc/php/${PHP_VERSION}/fpm/php.ini
COPY .docker/php.ini            /etc/php/${PHP_VERSION}/cli/php.ini

WORKDIR /app

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]

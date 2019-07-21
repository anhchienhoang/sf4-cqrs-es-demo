FROM ubuntu:bionic

ARG PHP_VERSION=7.3

ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install --no-install-recommends -yqq \
        software-properties-common \
        apt-utils && \
    add-apt-repository ppa:ondrej/php && \
    apt-get update && \
    apt-get install -yqq --no-install-recommends \
        php${PHP_VERSION}-cli \
        php${PHP_VERSION}-fpm \
        php${PHP_VERSION}-opcache \
        php-amqp \
        php${PHP_VERSION}-mysql \
        php${PHP_VERSION}-pdo \
        php-igbinary \
        php${PHP_VERSION}-curl \
        php${PHP_VERSION}-gd \
        php${PHP_VERSION}-intl \
        php${PHP_VERSION}-json \
        php${PHP_VERSION}-readline \
        php${PHP_VERSION}-zip \
        php${PHP_VERSION}-simplexml \
        php${PHP_VERSION}-mbstring \
        php${PHP_VERSION}-dom \
        php${PHP_VERSION}-xml \
        unzip \
        ca-certificates \
        apt-transport-https \
        net-tools \
        curl \
        nginx \
        git \
        nodejs npm \
        supervisor

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# Remove build requirements for php modules
RUN  apt-get -qy autoremove \
  && apt-get -qy purge $PHPIZE_DEPS \
  && rm -rf /var/lib/apt/lists/*

# Nginx configuration
COPY docker/nginx/conf.d /etc/nginx/conf.d
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/fastcgi_params /etc/nginx/fastcgi_params

# PHP-FPM configuration
RUN rm -rf /etc/php/${PHP_VERSION}/fpm/pool.d/*
COPY docker/php/php-fpm.conf /etc/php/${PHP_VERSION}/fpm/php-fpm.conf
COPY docker/php/pool.d/*.conf /etc/php/${PHP_VERSION}/fpm/pool.d/

COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

RUN install -d -o www-data -g www-data -m 0755 /var/www
RUN chown -R www-data:www-data /var/www
WORKDIR /var/www

# supervisord configuration
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN mkdir -p /etc/nginx/waiting
COPY docker/nginx/waiting/waiting_vhost.conf /etc/nginx/waiting/waiting_vhost.conf
COPY docker/nginx/waiting/nginx_waiting.conf /etc/nginx/nginx_waiting.conf

EXPOSE 8080 8081 22

# Run app with entrypoints
CMD ["bash", "/entrypoint.sh"]


#CMD ["supervisord", "-c", "/etc/supervisor/supervisord.conf", "--nodaemon"]

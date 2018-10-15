#!/bin/bash -x

set -xe

# Setup dependencies for the first time
if [ ! -d "/var/www/vendor" ]; then
    cp /etc/nginx/nginx.conf /etc/nginx/nginx.conf.bk
    cp /etc/nginx/nginx_waiting.conf /etc/nginx/nginx.conf
    /usr/sbin/nginx -g 'daemon on;' &

    # Run composer
    cd /var/www && composer install

    # Run composer
    cd /var/www && composer install

    # Build FE
    cd /var/www && npm install && npm run build

    cp /etc/nginx/nginx.conf.bk /etc/nginx/nginx.conf
    chown -R www-data:www-data /var/www

    killall -9 nginx
fi

# Run migrations
/var/www/bin/console doctrine:migrations:migrate -n

exec $*

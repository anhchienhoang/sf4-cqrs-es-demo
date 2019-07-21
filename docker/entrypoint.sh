#!/bin/bash

set -xe

cp /etc/nginx/nginx.conf /etc/nginx/nginx.conf.bk
cp /etc/nginx/nginx_waiting.conf /etc/nginx/nginx.conf

service nginx restart

sleep 1

# Setup dependencies for the first time
if [[ ! -d "/var/www/vendor" ]]; then
    mkdir /var/www/vendor
    # Run composer
    cd /var/www && composer install
fi

if [[ ! -d "/var/www/node_modules" ]]; then
    # Build FE
    cd /var/www && npm install && npm run build
fi

# Run migrations
php /var/www/bin/console doctrine:migrations:migrate -n

cp /etc/nginx/nginx.conf.bk /etc/nginx/nginx.conf
chown -R www-data:www-data /var/www

kill $(ps aux | grep '[n]ginx' | awk '{print $2}')

service nginx stop

sleep 1

supervisord -c /etc/supervisor/supervisord.conf --nodaemon

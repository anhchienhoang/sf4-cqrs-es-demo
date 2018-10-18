#!/bin/bash -x

set -xe

cp /etc/nginx/nginx.conf /etc/nginx/nginx.conf.bk
cp /etc/nginx/nginx_waiting.conf /etc/nginx/nginx.conf
/usr/sbin/nginx -g 'daemon on;' &

# Setup dependencies for the first time
if [ ! -d "/var/www/vendor" ]; then
    # Run composer
    cd /var/www && composer install

fi

if [ ! -d "/var/www/node_modules" ]; then
    # Build FE
    cd /var/www && npm install && npm run build
fi

# Run migrations
/var/www/bin/console doctrine:migrations:migrate -n

while ! nc -z ${RABBITMQ_HOST} ${RABBITMQ_PORT}; do
    echo "Waiting for Rabbitmq..."
    sleep 3;
done

cp /etc/nginx/nginx.conf.bk /etc/nginx/nginx.conf
chown -R www-data:www-data /var/www

killall -9 nginx

exec $*

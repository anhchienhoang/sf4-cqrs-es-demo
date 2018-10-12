#!/bin/bash -x

set -xe

#Run migrations
/var/www/bin/console doctrine:migrations:migrate -n

cd /var/www && composer install

exec $*
